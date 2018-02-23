<?php

namespace App\Http\Controllers\Backend\Internship;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Internship\DeleteInternshipRequest;
use App\Http\Requests\Backend\Internship\StoreInternshipRequest;
use App\Models\AcademicYear;
use App\Models\Internship\Internship;
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
        return view('backend.internship.create')->with([
            'academic_years' => $academic_years,
            'number' => $number
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
        $result = array(
            'code' => 1,
            'message' => 'success',
            'data' => []
        );

        try {

            if (array_key_exists('id', $request->all())) {
                $internship = Internship::find($request->id);
                $internship->number = $request->number;
                $internship->ref_number = $request->ref_number;
                $internship->number = $request->number;
                $internship->subject = $request->subject;
                $internship->internship_title = $request->internship_title;
                $internship->date = $request->date;
                $internship->start_date = new Carbon((explode(' - ', $request->period))[0]);
                $internship->end_date = new Carbon((explode(' - ', $request->period))[1]);
                $internship->contact_name = $request->contact_name;
                $internship->contact_detail = $request->contact_detail;

                if ($internship->update()) {
                    if (count($request->students) > 0) {
                        $internshipStudentAnnuals = InternshipStudentAnnual::where('internship_id', $internship->id)->get();
                        foreach ($internshipStudentAnnuals as $internshipStudentAnnual) {
                            $internshipStudentAnnual->delete();
                        }
                    }

                    foreach ($request->students as $studentAnnualId) {
                        $newInternshipStudentAnnual = new InternshipStudentAnnual();
                        $newInternshipStudentAnnual->internship_id = $internship->id;
                        $newInternshipStudentAnnual->student_annual_id = $studentAnnualId;
                        $newInternshipStudentAnnual->save();
                    }
                }
            } else {
                $newInternship = new Internship();
                $newInternship->number = $request->number;
                $newInternship->ref_number = $request->ref_number;
                $newInternship->number = $request->number;
                $newInternship->subject = $request->subject;
                $newInternship->internship_title = $request->internship_title;
                $newInternship->date = $request->date;
                $newInternship->start_date = new Carbon((explode(' - ', $request->period))[0]);
                $newInternship->end_date = new Carbon((explode(' - ', $request->period))[1]);
                $newInternship->contact_name = $request->contact_name;
                $newInternship->contact_detail = $request->contact_detail;
                if ($newInternship->save()) {
                    foreach ($request->students as $studentAnnualId) {
                        $newInternshipStudentAnnual = new InternshipStudentAnnual();
                        $newInternshipStudentAnnual->internship_id = $newInternship->id;
                        $newInternshipStudentAnnual->student_annual_id = $studentAnnualId;
                        $newInternshipStudentAnnual->save();
                    }
                }
            }

        } catch (\Exception $e) {
            $result['code'] = 0;
            $result['message'] = $e->getMessage();
        }

        return redirect()->route('internship.index')->withFlashSuccess('The operation was execute successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Internship $internship
     * @return mixed
     */
    public function edit(Internship $internship)
    {
        $number = 1;
        $internships = Internship::all();
        if (count($internships)) {
            $number += count($internships);
        }
        $academic_years = AcademicYear::latest()->get();
        $internship = Internship::with('internship_student_annuals')->find($internship->id);
        $pre_academic_year = null;
        foreach ($internship->internship_student_annuals as $internship_student_annual) {
            $student_annual = StudentAnnual::find($internship_student_annual->student_annual_id);
            $pre_academic_year = AcademicYear::find($student_annual->academic_year_id);
        }

        return view('backend.internship.edit', compact('internship', 'number', 'academic_years', 'pre_academic_year'));
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
                ->where('students.name_latin', 'ilike', "%" . Input::get("term") . "%")
                ->orWhere('students.name_kh', 'ilike', "%" . Input::get("term") . "%")
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

    public function data()
    {
        $internships = Internship::select(['id', 'internship_title', 'subject', 'contact_name', 'contact_detail'])->latest();
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
                    $template .= '<li>'.$student->name_kh.'</li>';
                }
                $template .= '</ul>';

                return $template;
            })
            ->addColumn('actions', function ($internship) {
                return '<a href="' . route('internship.edit', $internship) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"></i></a>' .
                    ' <a href="' . route('internship.delete', $internship) . '" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"></i></a>';
            })
            ->addColumn('checkbox', function ($internship) {
                return '<input type="checkbox" name="internships[]" checked class="checkbox" data-id=' . $internship->id . '>';
            })
            ->make(true);
    }

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
}