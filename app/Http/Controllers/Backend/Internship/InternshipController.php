<?php

namespace App\Http\Controllers\Backend\Internship;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Internship\DeleteInternshipRequest;
use App\Http\Requests\Backend\Internship\StoreInternshipRequest;
use App\Models\AcademicYear;
use App\Models\Internship\Internship;
use App\Models\Internship\InternshipCompany;
use App\Models\Internship\InternshipStudentAnnual;
use App\Models\Student;
use App\Models\StudentAnnual;
use App\Traits\PrintInternshipTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Facades\Datatables;

/**
 * Class InternshipController
 * @package App\Http\Controllers\Backend\Internship
 */
class InternshipController extends Controller
{
    use PrintInternshipTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.internship.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $number = 1;
        $internships = Internship::all();
        if (count($internships)) {
            $number += count($internships);
        }
        $academic_years = AcademicYear::latest()->get();
        $companies = InternshipCompany::select('name as text', '*')->orderBy('name', 'asc')->get();
        return view('backend.internship.create')->with([
            'academic_years' => $academic_years,
            'number' => $number,
            'companies' => $companies
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreInternshipRequest $request
     * @return mixed
     */
    public function store(StoreInternshipRequest $request)
    {
        // dd($request->all());
        $result = array(
            'code' => 1,
            'message' => 'success',
            'data' => []
        );

        $is_name = $request->is_name;
        isset($is_name) ? $is_name = true : $is_name = false;

        try {
            $company = json_decode($request->company);
            if (is_null($company)) {
                $company = InternshipCompany::create([
                    'name' => $request->company,
                    'title' => $request->title,
                    'training_field' => $request->training_field,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'hp' => $request->hot_line,
                    'mail' => $request->e_mail_address,
                    'web' => $request->web
                ]);
                $request['company'] = $company->name;
                $request['company_id'] = $company->id;
            } else {
                if (isset($company->id) && isset($company->name)) {
                    $request['company'] = $company->name;
                    $request['company_id'] = $company->id;
                } else {
                    return message_success("Could not found company!");
                }
            }

            if (array_key_exists('id', $request->all())) {
                $internship = Internship::find($request->id);
                if ($internship instanceof Internship) {
                    $internship->update($request->all());
                    if (count($request->students) > 0) {
                        $internshipStudentAnnuals = InternshipStudentAnnual::where('internship_id', $internship->id)->get();
                        foreach ($internshipStudentAnnuals as $internshipStudentAnnual) {
                            $internshipStudentAnnual->delete();
                        }

                        foreach ($request->students as $studentAnnualId) {
                            InternshipStudentAnnual::create([
                                'internship_id' => $internship->id,
                                'student_annual_id' => $studentAnnualId
                            ]);
                        }
                    }
                }
            } else {
                $lastInternship = Internship::where('academic_year_id', $request->academic_year_id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                if ($lastInternship instanceof Internship) {
                    $request['number'] = $lastInternship->number + 1;
                } else {
                    $request['number'] = 1;
                }

                $internship = Internship::create($request->all());
                if ($internship instanceof Internship) {
                    foreach ($request->students as $studentAnnualId) {
                        InternshipStudentAnnual::create([
                            'internship_id' => $internship->id,
                            'student_annual_id' => $studentAnnualId
                        ]);
                    }
                }
            }

            return redirect()->route('internship.edit', ['internship' => $internship]);

        } catch (\Exception $e) {
            $result['code'] = 0;
            $result['message'] = $e->getMessage();
        }

        return redirect()->route('internship.index')->withFlashDanger($result['message']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Internship $internship
     * @return mixed
     */
    public function edit(Internship $internship)
    {
        try {
            $number = 1;
            $internships = Internship::all();
            if (count($internships)) {
                $number += count($internships);
            }
            $academic_years = AcademicYear::latest()->get();
            $internship = Internship::with('internship_student_annuals', 'internship_company')->find($internship->id);
            $pre_academic_year = null;
            foreach ($internship->internship_student_annuals as $internship_student_annual) {
                $student_annual = StudentAnnual::find($internship_student_annual->student_annual_id);
                $pre_academic_year = AcademicYear::find($student_annual->academic_year_id);
            }
            $companies = InternshipCompany::select('name as text', '*')->orderBy('name', 'asc')->get();

            return view('backend.internship.edit', compact('internship', 'number', 'academic_years', 'pre_academic_year', 'companies'));
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Internship $internship
     * @param DeleteInternshipRequest $request
     * @return mixed
     * @throws \Exception
     */
    public function delete(Internship $internship, DeleteInternshipRequest $request)
    {
        if ($internship instanceof Internship) {
            $internship->delete();
        }
        return redirect()->back()->with('flash_info', 'The operation was execute successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        if ($request->ajax()) {
            $academic_year_id = Input::get('academic_year_id');
            $page = Input::get('page');
            $resultCount = 25;
            $offset = ($page - 1) * $resultCount;

            $students = Student::join('genders', 'genders.id', '=', 'students.gender_id')
                ->join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
                ->join('departments', 'departments.id', '=', 'studentAnnuals.department_id')
                ->where('studentAnnuals.academic_year_id', $academic_year_id)
                ->where(function ($students) {
                    $students->where('students.name_latin', 'ilike', "%" . Input::get("term") . "%")
                        ->orWhere('students.name_kh', 'ilike', "%" . Input::get("term") . "%")
                        ->orWhere('students.id_card', 'ilike', "%" . Input::get("term") . "%");
                })
                ->select([
                    'studentAnnuals.id as id',
                    'students.id_card',
                    'students.name_kh as text',
                    'students.name_latin',
                    'genders.code as gender',
                    'departments.code as department'
                ]);

            $client = $students
                ->orderBy('name_latin')
                ->skip($offset)
                ->take($resultCount)
                ->get();

            $count = Count($students->get());
            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;

            $results = array(
                'results' => $client,
                'pagination' => array(
                    "more" => $morePages
                )
            );
            return response()->json($results);
        }
    }

    /**
     * @return mixed
     */
    public function data()
    {
        $internships = Internship::orderBy('created_at', 'desc')
            ->select('*');
        return Datatables::of($internships)
            ->addColumn('students', function ($internship) {
                $students = array();
                foreach ($internship->internship_student_annuals as $internship_student_annual) {
                    $student_annual = StudentAnnual::find($internship_student_annual->student_annual_id);
                    $student = Student::find($student_annual->student_id);
                    array_push($students, $student);
                }
                $template = '<ul>';
                foreach ($students as $student) {
                    $template .= '<li>' . $student->name_kh . '</li>';
                }
                $template .= '</ul>';

                return $template;
            })
            ->addColumn('company_info', function ($internship) {
                return 'To: <strong>' . $internship->person . '</strong><br/>' .
                    '<strong>' . $internship->company . '</strong><br/>' .
                    '<strong>' . $internship->address . '</strong><br/>' .
                    'Phone: <strong>' . $internship->phone . '</strong><br/>' .
                    'H/P: <strong>' . $internship->hot_line . '</strong><br/>' .
                    'E-Mail: <strong>' . $internship->e_mail_address . '</strong><br/>' .
                    'Web: <strong>' . $internship->web . '</strong>';
            })
            ->addColumn('actions', function ($internship) {
                return '<a href="' . route('internship.edit', $internship) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"></i></a>';
                /*' <a href="' . route('internship.delete', $internship) . '" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"></i></a>';*/
            })
            ->addColumn('checkbox', function ($internship) {
                if (is_null($internship->printed_at)) {
                    return '<input type="checkbox" checked name="internships[]" class="checkbox" data-id=' . $internship->id . '>';
                } else {
                    return '<input type="checkbox" name="internships[]" class="checkbox" data-id=' . $internship->id . '>';
                }
            })
            ->editColumn('printed_at', function ($internship) {
                return '<span class="label label-success">' .
                    $internship->printed_at . '</span>';
            })
            ->make(true);
    }

    /**
     * @return string
     */
    public function getStudents()
    {
        $internship = Internship::with('internship_student_annuals')->find(\request('id'));
        $students = collect();

        foreach ($internship->internship_student_annuals as $internship_student_annual) {
            $student_annual = StudentAnnual::find($internship_student_annual->student_annual_id);
            $student = Student::find($student_annual->student_id);
            $itemStudent = [];
            $itemStudent['id'] = $student_annual->id;
            $itemStudent['text'] = $student->name_kh;
            $students->push($itemStudent);
        }

        return $students->toJson();
    }

    /**
     * @return array
     */
    public function markPrinted()
    {
        if (count(\request('internship_ids')) > 0) {
            $now = Carbon::now();
            foreach (\request('internship_ids') as $item) {
                $internship = Internship::find((int)$item);
                $internship->printed_at = $now;
                $internship->update();
            }
            return [
                'status' => true
            ];
        }
    }

    public function remoteInternshipCompanies(Request $request)
    {
        $this->validate($request, ['q' => 'required']);
        try {
            $result = InternshipCompany::where('name', 'ilike', '%' . $request->q . '%')->select('name as text', 'id')->get();
            return json_encode(['code' => 1, 'status' => 'success', 'results' => $result]);
        } catch (\Exception $exception) {
            return json_encode(['code' => 0, 'message' => $exception->getMessage()]);
        }
    }
}