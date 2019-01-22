<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Candidate\CreateCandidateRequest;
use App\Http\Requests\Backend\Candidate\DeleteCandidateRequest;
use App\Http\Requests\Backend\Candidate\EditCandidateRequest;
use App\Http\Requests\Backend\Candidate\RegisterCandidateRequest;
use App\Http\Requests\Backend\Candidate\StoreCandidateRequest;
use App\Http\Requests\Backend\Candidate\UpdateCandidateRequest;
use App\Models\AcademicYear;
use App\Models\Candidate;
use App\Models\CandidateDepartment;
use App\Models\Degree;
use App\Models\Department;
use App\Models\Exam;
use App\Models\GdeGrade;
use App\Models\Gender;
use App\Models\HighSchool;
use App\Models\Origin;
use App\Models\Promotion;
use App\Models\StudentBac2;
use App\Repositories\Backend\Candidate\CandidateRepositoryContract;
use App\Repositories\Backend\StudentAnnual\StudentAnnualRepositoryContract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Datatables;

class CandidateController extends Controller
{
    /**
     * @var CandidateRepositoryContract
     */
    protected $candidates;
    protected $studentRepo;

    /**
     * @param CandidateRepositoryContract $candidateRepo
     * @param StudentAnnualRepositoryContract $studentRepo
     */
    public function __construct(
        CandidateRepositoryContract $candidateRepo,
        StudentAnnualRepositoryContract $studentRepo
    )
    {
        $this->candidates = $candidateRepo;
        $this->studentRepo = $studentRepo;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.candidate.index');
    }

    /**
     * Show the form for creating a new resource (Pop Up).
     * @param CreateCandidateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateCandidateRequest $request)
    {
        $studentBac2_id = Input::get('studentBac2_id');
        $exam_id = Input::get('exam_id');
        $exam = Exam::where('id', $exam_id)->first();

        if ($exam->accept_registration) {
            // This exam accept new candidate registration
            $highschool = null;

            if ($studentBac2_id == 0) { // This allow to create candidate manually
                $studentBac2 = null;
                $highschool = null;
            } else {
                $studentBac2 = StudentBac2::find($studentBac2_id);
                $highschool = HighSchool::where('id', $studentBac2->highschool_id)->lists('id', 'name_kh as name')->toArray();
            }


            $dif = $exam->academic_year_id - 2016;
            if ($exam->type_id == 1) { // Engineer
                $promotion_id = config('app.promotions.I.2016') + $dif;
                $promotions = Promotion::where('id', $promotion_id)->orderBy('id', 'desc')->lists('name', 'id')->toArray();
            } else if ($exam->type_id == 2) {
                $promotion_id = config('app.promotions.T.2016') + $dif;
                $promotions = Promotion::where('id', $promotion_id)->orderBy('id', 'desc')->lists('name', 'id')->toArray();
            } else {
                $promotions = Promotion::orderBy('id', 'desc')->lists('name', 'id')->toArray();
            }

            $degrees = Degree::lists('name_kh', 'id');
            $genders = Gender::lists('name_kh', 'id');

            $provinces = Origin::lists('name_kh', 'id');
            $gdeGrades = GdeGrade::lists('name_en', 'id');
            $departments = Department::where('is_specialist', true)->where('parent_id', 11)->where('code', '!=', 'GTR')->orderBy('code', 'asc')->get();
            $academicYears = AcademicYear::orderBy('id', 'desc')->lists('id', 'id');

            return view('backend.candidate.create', compact('departments', 'degrees', 'genders', 'promotions', 'provinces', 'gdeGrades', 'academicYears', 'exam', 'studentBac2', 'highschool'));
        } else {
            // This exam no longer accept new candidate registration
            return Response::json(array("message" => "You can not add new candidate to this exam"), 422);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCandidateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCandidateRequest $request)
    {

        $exam = Exam::where('id', $request->get('exam_id'))->first();
        $data = $request->all();

        if ($exam->accept_registration) { // If this exam doesn't allow new registration, we can't accept it
            if ($request->pass_dept != null && $request->reserve_dept != null) {

                $data['result'] = "Pass";
                $result = $this->candidates->create($data);

                if ($result['status'] == true) {
                    $this->updateCandidateDept($result['candidate_id'], $request->pass_dept, "Pass");
                    $this->updateCandidateDept($result['candidate_id'], $request->reserve_dept, "Reserve");
                    return Response::json($result);
                } else {
                    return Response::json($result, 422);
                }

            } elseif ($request->pass_dept != null && $request->reserve_dept == null) {

                $data['result'] = "Pass";
                $result = $this->candidates->create($data);

                if ($result['status'] == true) {
                    $this->updateCandidateDept($result['candidate_id'], $request->pass_dept, "Pass");
                    return Response::json($result);
                } else {
                    return Response::json($result, 422);
                }


            } elseif ($request->pass_dept == null && $request->reserve_dept != null) {

                $data['result'] = "Reserve";
                $result = $this->candidates->create($data);

                if ($result['status'] == true) {
                    $this->updateCandidateDept($result['candidate_id'], $request->reserve_dept, "Reserve");
                    return Response::json($result);
                } else {
                    return Response::json($result, 422);
                }

            } else {

                $result = $this->candidates->create($request->all());

                if ($result['status'] == true) {
                    return Response::json($result);
                } else {
                    return Response::json($result, 422);
                }
            }
        } else {
            return Response::json(array("message" => "You cannot register new candidate with this examination"), 422);
        }

    }

    private function updateCandidateDept($candidate_id, $department_id, $result)
    {

        $res = DB::table('candidate_department')
            ->where([
                ['candidate_id', '=', $candidate_id],
                ['department_id', '=', $department_id]
            ])
            ->update(array(
                'is_success' => $result
            ));
        return $res;
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditCandidateRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditCandidateRequest $request, $id)
    {
        $candidate = Candidate::where('id', $id)->with('departments')->first();

        //dd($candidate->departments->toArray());
        $candidate_departments = [];
        foreach ($candidate->departments as $candidate_department) {
            $candidate_departments[$candidate_department->id] = $candidate_department->pivot->rank;
        }
        //dd($candidate_departments);

        $exam = Exam::where('id', $candidate->exam_id)->first();
        $studentBac2 = StudentBac2::find($candidate->studentBac2_id);

        $degrees = Degree::lists('name_kh', 'id');
        $genders = Gender::lists('name_kh', 'id');

        $dif = $exam->academic_year_id - 2015;
        if ($exam->type_id == 1) { // Engineer
            $promotion_id = config('app.promotions.I.2015') + $dif;
            $promotions = Promotion::where('id', $promotion_id)->orderBy('id', 'desc')->lists('name', 'id')->toArray();
        } else if ($exam->type_id == 2) {
            $promotion_id = config('app.promotions.T.2015') + $dif;
            $promotions = Promotion::where('id', $promotion_id)->orderBy('id', 'desc')->lists('name', 'id')->toArray();
        } else {
            $promotions = Promotion::orderBy('id', 'desc')->lists('name', 'id')->toArray();
        }

        $provinces = Origin::lists('name_kh', 'id');
        $gdeGrades = GdeGrade::lists('name_en', 'id');
        $departments = Department::where('is_specialist', true)->where('parent_id', 11)->where('code', '!=', 'GTR')->orderBy('code', 'asc')->get();
        $academicYears = AcademicYear::orderBy('name_latin', 'desc')->lists('name_latin', 'id');

        if ($studentBac2 != null) {
            $highschool = HighSchool::where('id', $studentBac2->highschool_id)->lists('id', 'name_kh as name')->toArray();
        } else {
            $highschool = HighSchool::where('id', $candidate->highschool_id)->lists('id', 'name_kh as name')->toArray();
        }


        return view('backend.candidate.edit', compact('highschool', 'departments', 'exam', 'degrees', 'genders', 'promotions', 'provinces', 'gdeGrades', 'academicYears', 'candidate', 'studentBac2', 'candidate_departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCandidateRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCandidateRequest $request, $id)
    {
        $exam = Exam::where('id', $request->get('exam_id'))->first();
        $candidate = Candidate::where('id', $id)->first();

        if ($exam->accept_registration && $candidate->result == "Pending") { // If this result is still pending, they can update their info
            $result = $this->candidates->update($id, $request->all());

            if ($result['status'] == true) {
                return Response::json($result);
            } else {
                return Response::json($result, 422);
            }
        } else {
            return Response::json(array("message" => "You cannot modify information of this candidate"), 422);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteCandidateRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteCandidateRequest $request, $id)
    {
        $this->candidates->destroy($id);
        if ($request->ajax()) {
            return json_encode(array("success" => true));
        } else {
            return redirect()->route('admin.candidates.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    /**
     * Return data for candidate datatable in Exam Management.
     *
     * @return Datatables
     */
    public function data()
    {
        //$exam = Exam::where('id',Input::get('exam_id'))->first();

        $candidates = DB::table('candidates')
            ->leftJoin('origins', 'candidates.province_id', '=', 'origins.id')
            ->leftJoin('gdeGrades', 'candidates.bac_total_grade', '=', 'gdeGrades.id')
            ->leftJoin('genders', 'candidates.gender_id', '=', 'genders.id')
            ->leftJoin('examRooms', 'candidates.room_id', '=', 'examRooms.id')
            ->leftJoin('buildings', 'examRooms.building_id', '=', 'buildings.id')
            ->leftJoin('highSchools', 'candidates.highschool_id', '=', 'highSchools.id')
            ->where('candidates.active', true)
            ->select([
                'candidates.id', DB::raw("CONCAT(buildings.code,\"examRooms\".name) as room"),
                'candidates.register_id', 'candidates.name_kh', 'candidates.name_latin',
                'candidates.highschool_id', 'highSchools.name_kh as high_school',
                'candidates.exam_id', 'candidates.total_score',
                'genders.name_kh as gender_name_kh', 'gdeGrades.name_en as bac_total_grade',
                'origins.name_kh as province', 'dob', 'result', 'is_paid', 'is_register'
            ]);

        if ($exam_id = Input::get('exam_id')) {
            $candidates = $candidates->where('candidates.exam_id', $exam_id);
        }
        if ($academic_year_id = Input::get('academic_year_id')) {
            $candidates = $candidates->where('academic_year_id', $exam_id);
        }
        if ($degree_id = Input::get('degree_id')) {
            $candidates = $candidates->where('degree_id', $degree_id);
        }
        if ($origin_id = Input::get('origin_id')) {
            $candidates = $candidates->where('candidates.province_id', $origin_id);
        }
        if ($room_id = Input::get('room_id')) {
            $candidates = $candidates->where('candidates.room_id', $room_id);
        }
        if ($result = Input::get('result')) {
            $candidates = $candidates->where('candidates.result', $result);
        }

        $datatables = app('datatables')->of($candidates);


        return $datatables
            ->addColumn('number', function ($candidate) {
                return "";
            })
            ->editColumn('name_latin', function ($candidate) {
                return strtoupper($candidate->name_latin);
            })
            ->editColumn('register_id', function ($candidate) {
                return str_pad($candidate->register_id, 4, '0', STR_PAD_LEFT);
            })
            ->editColumn('room', function ($candidate) {
                if ($candidate->room == "" || $candidate->room == null) {
                    return " - ";
                } else {
                    return $candidate->room;
                }
            })
            ->editColumn('result', function ($candidate) {
                if ($candidate->result == "Pending") {
                    return '<span class="label label-warning">' . $candidate->result . '</span>';
                } else if ($candidate->result == "Pass") {
                    return '<span class="label label-success">' . $candidate->result . '</span>';
                } else if ($candidate->result == "Reserve") {
                    return '<span class="label label-info">' . $candidate->result . '</span>';
                } else if ($candidate->result == "Fail") {
                    return '<span class="label label-danger">' . $candidate->result . '</span>';
                } else { // Rejected
                    return '<span class="label label-danger">' . $candidate->result . '</span>';
                }
            })
            ->editColumn('dob', function ($candidate) {
                $date = Carbon::createFromFormat('Y-m-d h:i:s', $candidate->dob);
                return $date->toFormattedDateString();
            })
            ->addColumn('action', function ($candidate) {
                $action = "";

                if ($candidate->result == "Pending") {
                    $action = '';
                    if (Auth::user()->allow('edit-exam-candidate')) {
                        $action = $action . '<a href="' . route('admin.candidates.edit', $candidate->id) . '" class="btn_candidate_edit btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . trans('buttons.general.crud.edit') . '"></i> </a>';
                    }
                    if (Auth::user()->allow('delete-exam-candidate')) {
                        $action = $action . ' <button class="btn btn-xs btn-danger btn-delete" data-remote="' . route('admin.candidates.destroy', $candidate->id) . '"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
                    }
                } else {
                    if ($candidate->is_register) {
                        $action = '<span style="color:green"><i class="fa fa-check"></i></span>';
                    } else {//if($candidate->is_paid){
                        if (Auth::user()->allow('register-exam-candidate')) {
                            $action = ' <button class="btn btn-xs btn-register" data-exam="' . $candidate->exam_id . '" data-candidate="' . $candidate->id . '" data-remote="' . route('admin.candidate.register', $candidate->id) . '"><i class="fa fa-check-circle-o" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.register') . '"></i></button>';
                        }
                    }
                }
                return $action;
            })
            ->setRowClass(function ($candidate) {

                return $candidate->result;
            })
            ->make(true);
    }

    /**
     * Register candidate, to become first year student
     *
     * @param RegisterCandidateRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterCandidateRequest $request, $id)
    {

        //$examId = $request->exam_id;
        //$exam = Exam::where('id',$examId)->first();
        $candidate = Candidate::where('id', $id)->first();
        $dept_id = config('access.departments.department_tc'); // By default, it is foundation department

        if (isset($request->department_id) && $request->department_id != null) {
            $dept_id = $request->department_id;
        }

        $result = $this->studentRepo->register($candidate, $dept_id);
        if ($request->ajax()) {
            return json_encode(array('success' => $result));
        }
    }


    public function requestRegisterStudentDUT(RegisterCandidateRequest $request)
    {
        $examId = $request->exam_id;
        $exam = Exam::where('id', $examId)->first();
        $candidate_id = $request->candidate_id;
        $register_url = route('admin.candidate.register', $candidate_id);

        $candidate = Candidate::where('id', $candidate_id)->first();

        $candidateDepartments = DB::table('candidates')
            ->join('candidate_department', 'candidate_department.candidate_id', '=', 'candidates.id')
            ->join('departments', 'departments.id', '=', 'candidate_department.department_id')
            ->where([
                ['departments.is_specialist', '=', true],
                ['departments.parent_id', '=', 11],
                ['candidates.exam_id', '=', $examId],
                ['candidate_department.candidate_id', '=', $candidate_id]
            ])
            ->select(
                'candidate_department.is_success as result',
                'departments.code as dept_name',
                'departments.id as dept_id',
                'candidates.id as candidate_id'
            )
            ->get();

        $getAllDepts = DB::table('departments')
            ->where([
                ['departments.is_specialist', '=', true],
                ['departments.parent_id', '=', 11]
            ])
            ->select('departments.id as dept_id', 'departments.code as dept_name')
            ->get();


        foreach ($getAllDepts as $Dept) {

            $countRegisteredStudents = DB::table('studentAnnuals')
                ->where([
                    ['studentAnnuals.academic_year_id', '=', $exam->academic_year_id],
                    ['studentAnnuals.department_id', '=', $Dept->dept_id],
                    ['studentAnnuals.degree_id', '=', config('access.degrees.degree_associate')]
                ])->count();
            $studentWithRegisteredStudetn[$Dept->dept_name] = $countRegisteredStudents;
        }

        return view('backend.exam.includes.popup_register_student_dut', compact('candidateDepartments', 'examId', 'candidate_id', 'studentWithRegisteredStudetn', 'register_url', 'candidate'));
    }

    public function register_candidate_department(\Illuminate\Http\Request $request)
    {
        $exam_id = $request->get('exam_id');
        $exam = Exam::find($exam_id);
        if ($exam instanceof Exam) {
            $departments = Department::where('parent_id', 11)
                ->where('is_specialist', true)
                ->orderBy('order')
                ->get();
            $exams = Exam::where('name', 'ilike', "%Entrance%")
                ->where('id', '!=', $exam->id)
                ->orderBy('created_at', 'desc')
                ->get();
            return view('backend.candidate.choose_department', compact('exam', 'departments', 'exams'));
        }
        abort(404);
    }

    public function search_candidate(Request $request)
    {

    }

    public function store_candidate_department(\Illuminate\Http\Request $request)
    {
        $this->validate($request, [
            'from_previous_year' => 'required'
        ]);
        try {
            if ($request->from_previous_year == 'none') {
                $candidate = Candidate::where('register_id', '=', $request->get("candidate_register_id"))->where("candidates.exam_id", "=", $request->get('exam_id'))->first();
            } else {
                // start modify register_id
                $exam = Exam::find($request->from_previous_year);
                $newCandidateRegisterId = (int)((string)$exam->academic_year_id . (string)sprintf("%04d", $request->candidate_register_id));
                if ($exam instanceof Exam) {
                    // find previous year candidate first.
                    $candidate = Candidate::where([
                        'register_id' => $request->candidate_register_id,
                        'exam_id' => $request->from_previous_year,
                    ])->first();
                    if ($candidate instanceof Candidate) {
                        $foundCandidate = Candidate::where([
                            'register_id' => $newCandidateRegisterId,
                            'from_previous_year' => $request->from_previous_year
                        ])->first();
                        if (!($foundCandidate instanceof Candidate)) {
                            $candidate = $candidate->replicate();
                            $candidate->register_id = $newCandidateRegisterId;
                            $candidate->exam_id = $request->exam_id;
                            $candidate->from_previous_year = $request->from_previous_year;
                            $candidate->save();
                        } else {
                            message_success('Candidate already existed!');
                        }
                    }
                } else {
                    return message_error('Could not found exam');
                }
            }
            // Validate candidate
            if (!($candidate instanceof Candidate)) {
                return message_error("This candidate register id is invalid");
            }
            // then find if this candidate already register
            $candidate_departments = DB::table('candidate_department')
                ->where("candidate_department.candidate_id", "=", $candidate->id)
                ->first();
            if ($candidate_departments) {
                // this one already register, send error back
                return message_error("This candidate is already registered");
            } else {
                $choices = $request->get("choice_department");
                DB::beginTransaction();
                foreach ($choices as $priority => $department_id) {
                    if ($priority == "" || $priority < 1 || $priority == null) {
                        DB::rollback();
                        return message_error("Candidate priority is invalid");
                    } else {
                        if ($department_id == 1) {
                            $department_id = 1;
                        } else if ($department_id == 2) {
                            $department_id = 2;
                        } else if ($department_id == 3) {
                            $department_id = 17;
                        } else if ($department_id == 4) {
                            $department_id = 3;
                        } else if ($department_id == 5) {
                            $department_id = 16;
                        } else if ($department_id == 6) {
                            $department_id = 4;
                        } else if ($department_id == 7) {
                            $department_id = 5;
                        } else if ($department_id == 8) {
                            $department_id = 7;
                        } else if ($department_id == 9) {
                            $department_id = 6;
                        }
                        CandidateDepartment::create([
                            "candidate_id" => $candidate->id,
                            "department_id" => $department_id,
                            "rank" => $priority
                        ]);
                    }
                }
                DB::commit();
                return message_success(["Candidate's department is registered"]);
            }
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function list_candidate_department(\Illuminate\Http\Request $request)
    {
        $exam_id = $request->get('exam_id');
        $candidates = DB::table('candidate_department')
            ->leftJoin('candidates', 'candidate_department.candidate_id', '=', 'candidates.id')
            ->leftJoin('departments', 'candidate_department.department_id', '=', 'departments.id')
            ->where('candidates.active', true)
            ->where('candidates.exam_id', $exam_id)
            ->select([
                'candidate_department.*', 'departments.code as department_code',
                'candidates.register_id', 'candidates.name_kh', 'candidates.name_latin',
                'dob', 'result', 'candidates.created_at'
            ])
            ->orderBy('candidates.created_at', 'desc')
            ->get();

        $candidates_by_register_id = collect($candidates)->sortByDesc('created_at')->groupBy('register_id');
        $datatable = [];
        foreach ($candidates_by_register_id as $candidate) {
            $data = array(
                "name_kh" => $candidate->first()->name_kh,
                "name_latin" => $candidate->first()->name_latin,
                "register_id" => $candidate->first()->register_id,
                "dob" => $candidate->first()->dob,
                "result" => $candidate->first()->result,
                "candidate_id" => $candidate->first()->candidate_id
            );
            foreach ($candidate as $choice) {
                if ($choice->rank == 1) {
                    $data["No1"] = $choice->department_code;
                } else if ($choice->rank == 2) {
                    $data["No2"] = $choice->department_code;
                } else if ($choice->rank == 3) {
                    $data["No3"] = $choice->department_code;
                } else if ($choice->rank == 4) {
                    $data["No4"] = $choice->department_code;
                } else if ($choice->rank == 5) {
                    $data["No5"] = $choice->department_code;
                } else if ($choice->rank == 6) {
                    $data["No6"] = $choice->department_code;
                } else if ($choice->rank == 7) {
                    $data["No7"] = $choice->department_code;
                } else if ($choice->rank == 8) {
                    $data["No8"] = $choice->department_code;
                } else if ($choice->rank == 9) {
                    $data["No9"] = $choice->department_code;
                }
            }
            array_push(
                $datatable, $data
            );
        }


        $datatables = app('datatables')->of(collect($datatable));
        return $datatables
            ->addColumn('number', function ($candidate) {
                return "";
            })
            ->editColumn('name_latin', function ($candidate) {
                return strtoupper($candidate['name_latin']);
            })
            ->editColumn('register_id', function ($candidate) {
                return str_pad($candidate['register_id'], 4, '0', STR_PAD_LEFT);
            })
            ->editColumn('result', function ($candidate) {
                if ($candidate['result'] == "Pending") {
                    return '<span class="label label-warning">' . $candidate['result'] . '</span>';
                } else if ($candidate['result'] == "Pass") {
                    return '<span class="label label-success">' . $candidate['result'] . '</span>';
                } else if ($candidate['result'] == "Reserve") {
                    return '<span class="label label-info">' . $candidate['result'] . '</span>';
                } else if ($candidate['result'] == "Fail") {
                    return '<span class="label label-danger">' . $candidate['result'] . '</span>';
                } else { // Rejected
                    return '<span class="label label-danger">' . $candidate['result'] . '</span>';
                }
            })
            ->editColumn('dob', function ($candidate) {
                $date = Carbon::createFromFormat('Y-m-d h:i:s', $candidate['dob']);
                return $date->toFormattedDateString();
            })
            ->addColumn('action', function ($candidate) {
                $action = "";
                $action = $action . ' <button class="btn btn-xs btn-danger btn-delete" data-remote="' . route('admin.candidate.clear_department', $candidate['candidate_id']) . '"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
                return $action;
            })
            ->setRowClass(function ($candidate) {

                return $candidate['result'];
            })
            ->make(true);
    }

    public function clear_department(\Illuminate\Http\Request $request, $id)
    {

        $candidate_departments = CandidateDepartment::where('candidate_id', $id)->delete();
        if ($request->ajax()) {
            return json_encode(array("success" => true));
        } else {
            return redirect()->route('admin.candidates.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function export_chosen_departments(\Illuminate\Http\Request $request)
    {
        $exam = Exam::where('id', $request->get('exam_id'))->first();
        if (!$exam) {
            return redirect()->back()->withFlashDanger("Exam ID is required");
        }
        $academicYear = $exam->academicYear;
        // Get list departments so that we don't need to do query join
        $departments = Department::where('is_specialist', true)
            ->where('parent_id', 11)
            ->select(['id', 'code'])
            ->get()
            ->toArray();
        $departments = collect($departments)->keyBy('id'); // The result format is ['key' => 'code']
        // Get all candidates that have chosen departments in raw format
        $raw_candidates = CandidateDepartment::join('candidates', 'candidate_department.candidate_id', '=', 'candidates.id')
            ->join('genders', 'candidates.gender_id', '=', 'genders.id')
            ->where('candidates.exam_id', $exam->id)
            ->select(
                'candidates.register_id', 'candidates.name_kh',
                'genders.code as gender', 'candidates.dob',
                'candidates.name_latin', 'candidate_department.*',
                'candidates.result', 'candidates.total_score'
            )
            ->get()
            ->toArray();

        $raw_candidates = collect($raw_candidates)->groupBy('register_id');

        // Prepare well structure candidates with the departments above
        $candidates = [];
        foreach ($raw_candidates as $key => $rawCandidate) {
            $tmpData = array(
                'name_kh' => $rawCandidate->first()['name_kh'],
                'name_latin' => $rawCandidate->first()['name_latin'],
                'register_id' => $rawCandidate->first()['register_id'],
                'dob' => Carbon::createFromFormat('Y-m-d H:i:s', $rawCandidate->first()['dob'])->format('d/M/Y'),
                'gender' => $rawCandidate->first()['gender'],
                'result' => $rawCandidate->first()['result'],
                'score' => $rawCandidate->first()['total_score']
            );
            $tmpData['pass'] = '';
            $tmpData['reserve'] = '';
            foreach ($rawCandidate as $chosenDepartment) {
                $tmpData[$chosenDepartment['rank']] = $departments[$chosenDepartment['department_id']]['code'];
                if ($chosenDepartment['is_success'] == 'Pass') {
                    $tmpData['pass'] = $departments[$chosenDepartment['department_id']]['code'];
                } else if ($chosenDepartment['is_success'] == 'Reserve') {
                    $tmpData['reserve'] = $departments[$chosenDepartment['department_id']]['code'];
                }
            }
            array_push($candidates, $tmpData);
        }

        return Excel::create('Candidate Priority Department ' . $academicYear->id, function ($excel) use ($academicYear, $candidates) {
            $excel->setTitle('Candidate Priority Department ' . $academicYear->id);
            $excel->sheet('Candidate Priority Department ', function ($sheet) use ($candidates) {
                // header
                $sheet->mergeCells('A1:E1');
                $sheet->cell('A1', function ($cell) {
                    $cell->setValue('វិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា');
                    $cell->setFont(array(
                        'bold' => true,
                        'size' => 14
                    ));
                });

                $sheet->mergeCells('A3:R3');
                $sheet->cell('A3', function ($cell) {
                    $cell->setValue('បញ្ចើសម្រង់ជម្រើសនិសិ្សត');
                    $cell->setAlignment('center');
                    $cell->setFont(array(
                        'bold' => true,
                        'size' => 16
                    ));
                });

                $sheet->setAutoSize(true);

                $sheet->cell('A6', 'No.');
                $sheet->cell('B6', 'Register ID');
                $sheet->cell('C6', 'Name');
                $sheet->cell('D6', 'Sex');
                $sheet->cell('E6', 'DOB');
                $sheet->cell('F6', 'Result');
                $sheet->cell('G6', 'Score');
                $sheet->cell('H6', '1st choice');
                $sheet->cell('I6', '2nd choice');
                $sheet->cell('J6', '3rd choice');
                $sheet->cell('K6', '4th choice');
                $sheet->cell('L6', '5th choice');
                $sheet->cell('M6', '6th choice');
                $sheet->cell('N6', '7th choice');
                $sheet->cell('O6', '8th choice');
                $sheet->cell('P6', '9th choice');
                $sheet->cell('Q6', 'Pass');
                $sheet->cell('R6', 'Reserve');

                $row = 7;
                $number = 1;
                foreach ($candidates as $candidate) {
                    $sheet->cell('A' . $row, $number);
                    $sheet->cell('B' . $row, $candidate['register_id']);
                    $sheet->cell('C' . $row, strtoupper($candidate['name_latin']));
                    $sheet->cell('D' . $row, $candidate['gender']);
                    $sheet->cell('E' . $row, $candidate['dob']);
                    $sheet->cell('F' . $row, $candidate['result']);
                    $sheet->cell('G' . $row, $candidate['score']);
                    $sheet->cell('H' . $row, $candidate['1']);
                    $sheet->cell('I' . $row, $candidate['2']);
                    $sheet->cell('J' . $row, $candidate['3']);
                    $sheet->cell('K' . $row, $candidate['4']);
                    $sheet->cell('L' . $row, $candidate['5']);
                    $sheet->cell('M' . $row, $candidate['6']);
                    $sheet->cell('N' . $row, $candidate['7']);
                    $sheet->cell('O' . $row, $candidate['8']);
                    $sheet->cell('P' . $row, $candidate['9']);
                    $sheet->cell('Q' . $row, $candidate['pass']);
                    $sheet->cell('R' . $row, $candidate['reserve']);
                    $number += 1;
                    $row += 1;
                }

                $sheet->setBorder('A6:R' . ($row - 1), 'thin');
                $sheet->cells('A6:R' . '6', function ($cells) {
                    $cells->setValignment('center');
                    $cells->setAlignment('center');
                    $cells->setFont(array(
                        'family' => 'Calibri',
                        'size' => '12',
                        'bold' => true
                    ));
                });
            });
        })->export('xlsx');
    }
}
