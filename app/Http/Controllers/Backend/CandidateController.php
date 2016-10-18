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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Yajra\Datatables\Datatables;
use Symfony\Component\HttpFoundation\Request;

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
        $exam = Exam::where('id',$exam_id)->first();

        if($exam->accept_registration){
            // This exam accept new candidate registration
            $highschool = null;

            if($studentBac2_id==0){ // This allow to create candidate manually
                $studentBac2 = null;
                $highschool = null;
            } else {
                $studentBac2 = StudentBac2::find($studentBac2_id);
                $highschool = HighSchool::where('id',$studentBac2->highschool_id)->lists('id', 'name_kh as name')->toArray();
            }



            $dif = $exam->academic_year_id - 2015;
            if($exam->type_id == 1){ // Engineer
                $promotion_id = config('app.promotions.I.2015')+$dif;
                $promotions = Promotion::where('id',$promotion_id)->orderBy('id','desc')->lists('name','id')->toArray();
            } else if($exam->type_id == 2){
                $promotion_id = config('app.promotions.T.2015')+$dif;
                $promotions = Promotion::where('id',$promotion_id)->orderBy('id','desc')->lists('name','id')->toArray();
            } else {
                $promotions = Promotion::orderBy('id','desc')->lists('name','id')->toArray();
            }

            $degrees = Degree::lists('name_kh','id');
            $genders = Gender::lists('name_kh','id');

            $provinces = Origin::lists('name_kh','id');
            $gdeGrades = GdeGrade::lists('name_en','id');
            $departments = Department::where('is_specialist',true)->where('parent_id',11)->orderBy('code','asc')->get();
            $academicYears = AcademicYear::orderBy('id','desc')->lists('id','id');

            return view('backend.candidate.create',compact('departments','degrees','genders','promotions','provinces','gdeGrades','academicYears','exam','studentBac2','highschool'));
        } else {
            // This exam no longer accept new candidate registration
            return Response::json(array("message"=>"You can not add new candidate to this exam"),422);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCandidateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCandidateRequest $request)
    {
        $exam = Exam::where('id',$request->get('exam_id'))->first();
        if($exam->accept_registration) { // If this exam doesn't allow new registration, we can't accept it
            $result = $this->candidates->create($request->all());

            if($result['status']==true){
                return Response::json($result);
            } else {
                return Response::json($result,422);
            }

        } else {
            return Response::json(array("message"=>"You can modify information of this candidate"),422);
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditCandidateRequest $request, $id)
    {
        $candidate = Candidate::where('id',$id)->with('departments')->first();

        //dd($candidate->departments->toArray());
        $candidate_departments = [];
        foreach($candidate->departments as $candidate_department){
            $candidate_departments[$candidate_department->id] = $candidate_department->pivot->rank;
        }
        //dd($candidate_departments);

        $exam = Exam::where('id',$candidate->exam_id)->first();
        $studentBac2 = StudentBac2::find($candidate->studentBac2_id);

        $degrees = Degree::lists('name_kh','id');
        $genders = Gender::lists('name_kh','id');

        $dif = $exam->academic_year_id - 2015;
        if($exam->type_id == 1){ // Engineer
            $promotion_id = config('app.promotions.I.2015')+$dif;
            $promotions = Promotion::where('id',$promotion_id)->orderBy('id','desc')->lists('name','id')->toArray();
        } else if($exam->type_id == 2){
            $promotion_id = config('app.promotions.T.2015')+$dif;
            $promotions = Promotion::where('id',$promotion_id)->orderBy('id','desc')->lists('name','id')->toArray();
        } else {
            $promotions = Promotion::orderBy('id','desc')->lists('name','id')->toArray();
        }

        $provinces = Origin::lists('name_kh','id');
        $gdeGrades = GdeGrade::lists('name_en','id');
        $departments = Department::where('is_specialist',true)->where('parent_id',11)->orderBy('code','asc')->get();
        $academicYears = AcademicYear::orderBy('name_latin','desc')->lists('name_latin','id');

        if($studentBac2!=null){
            $highschool = HighSchool::where('id',$studentBac2->highschool_id)->lists('id', 'name_kh as name')->toArray();
        } else {
            $highschool = HighSchool::where('id',$candidate->highschool_id)->lists('id', 'name_kh as name')->toArray();
        }


        return view('backend.candidate.edit',compact('highschool','departments','exam','degrees','genders','promotions','provinces','gdeGrades','academicYears','candidate','studentBac2','candidate_departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCandidateRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCandidateRequest $request, $id)
    {
        $exam = Exam::where('id',$request->get('exam_id'))->first();
        $candidate = Candidate::where('id',$id)->first();
        if($exam->accept_registration && $candidate->result != "Pending"){ // If this result is still pending, they can update their info
            $result = $this->candidates->update($id, $request->all());

            if($result['status']==true){
                return Response::json($result);
            } else {
                return Response::json($result,422);
            }
        } else {
            return Response::json(array("message"=>"You cannot modify information of this candidate"),422);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteCandidateRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteCandidateRequest $request, $id)
    {
        $this->candidates->destroy($id);
        if($request->ajax()){
            return json_encode(array("success"=>true));
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
            ->leftJoin('origins','candidates.province_id','=','origins.id')
            ->leftJoin('gdeGrades','candidates.bac_total_grade','=','gdeGrades.id')
            ->leftJoin('genders','candidates.gender_id','=','genders.id')
            ->leftJoin('examRooms','candidates.room_id','=','examRooms.id')
            ->leftJoin('buildings','examRooms.building_id','=','buildings.id')
            ->leftJoin('highSchools','candidates.highschool_id','=','highSchools.id')
            ->where('candidates.active',true)
            ->select([
                'candidates.id',DB::raw("CONCAT(buildings.code,\"examRooms\".name) as room"),
                'candidates.register_id','candidates.name_kh','candidates.name_latin',
                'candidates.highschool_id','highSchools.name_kh as high_school',
                'candidates.exam_id', 'candidates.total_score',
                'genders.name_kh as gender_name_kh','gdeGrades.name_en as bac_total_grade',
                'origins.name_kh as province', 'dob','result','is_paid','is_register'
            ]);

        if($exam_id = Input::get('exam_id')){
            $candidates = $candidates->where('candidates.exam_id',$exam_id);
        }
        if($academic_year_id = Input::get('academic_year_id')){
            $candidates = $candidates->where('academic_year_id',$exam_id);
        }
        if($degree_id = Input::get('degree_id')){
            $candidates = $candidates->where('degree_id',$degree_id);
        }
        if($origin_id = Input::get('origin_id')){
            $candidates = $candidates->where('candidates.province_id',$origin_id);
        }
        if($room_id = Input::get('room_id')){
            $candidates = $candidates->where('candidates.room_id',$room_id);
        }
        if($result = Input::get('result')){
            $candidates = $candidates->where('candidates.result',$result);
        }

        $datatables =  app('datatables')->of($candidates);


        return $datatables
            ->addColumn('number',function($candidate){
                return "";
            })
            ->editColumn('name_latin',function($candidate){
                return strtoupper($candidate->name_latin);
            })
            ->editColumn('register_id',function($candidate){
                return str_pad($candidate->register_id, 4, '0', STR_PAD_LEFT);
            })
            ->editColumn('room',function($candidate){
                if($candidate->room == "" || $candidate->room == null){
                    return " - ";
                } else {
                    return $candidate->room;
                }
            })
            ->editColumn('result',function($candidate){
                if($candidate->result == "Pending"){
                    return '<span class="label label-warning">'.$candidate->result.'</span>';
                } else if($candidate->result == "Pass"){
                    return '<span class="label label-success">'.$candidate->result.'</span>';
                } else if($candidate->result == "Reserve"){
                    return '<span class="label label-info">'.$candidate->result.'</span>';
                } else if($candidate->result == "Fail"){
                    return '<span class="label label-danger">'.$candidate->result.'</span>';
                } else { // Rejected
                    return '<span class="label label-danger">'.$candidate->result.'</span>';
                }
            })
            ->editColumn('dob',function($candidate){
                $date = Carbon::createFromFormat('Y-m-d h:i:s',$candidate->dob);
                return $date->toFormattedDateString();
            })
            ->addColumn('action', function ($candidate) {
                $action = "";

                if($candidate->result == "Pending"){
                    $action = '';
                    if(Auth::user()->allow('edit-exam-candidate')){
                       $action = $action .'<a href="'.route('admin.candidates.edit',$candidate->id).'" class="btn_candidate_edit btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>';
                    }
                    if(Auth::user()->allow('delete-exam-candidate')) {
                        $action = $action. ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.candidates.destroy', $candidate->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
                    }
                } else {
                    if($candidate->is_register){
                        $action = '<span style="color:green"><i class="fa fa-check"></i></span>';
                    } else {//if($candidate->is_paid){
                        if(Auth::user()->allow('register-exam-candidate')) {
                            $action = ' <button class="btn btn-xs btn-register" data-exam="'.$candidate->exam_id.'" data-remote="' . route('admin.candidate.register', $candidate->id) . '"><i class="fa fa-check-circle-o" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.register') . '"></i></button>';
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterCandidateRequest $request, $id){

        $examId = $request->exam_id;
        $exam = Exam::where('id',$examId)->first();
        $candidate_id = $id;
        $studentWithRegisteredStudetn =[];

        if($exam->type_id == config("access.exam.entrance_dut")) {

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


            foreach($getAllDepts as $Dept) {

                $countRegisteredStudents = DB::table('studentAnnuals')
                ->where([
                    ['studentAnnuals.academic_year_id', '=', $exam->academic_year_id],
                    ['studentAnnuals.department_id', '=', $Dept->dept_id],
                    ['studentAnnuals.degree_id', '=', config('access.degrees.degree_associate')]
                ])->count();
                $studentWithRegisteredStudetn[$Dept->dept_name] = $countRegisteredStudents;
            }

            return view('backend.exam.includes.popup_register_student_dut', compact('candidateDepartments', 'examId', 'candidate_id', 'studentWithRegisteredStudetn'));

        } else {

            $candidate = Candidate::where('id',$id)->first();

            $this->studentRepo->register($candidate, $department_id = config('access.departments.department_tc'));

            if($request->ajax()){
                return json_encode(array('success'=>true));
            }
        }
    }


    public function registerStudentDUT($examId, Request $request) {

        $dept_id = $request->department_id;


        $candidate_id = $request->candidate_id;
        $candidate = Candidate::where('id',$candidate_id)->first();
        $this->studentRepo->register($candidate, $dept_id);

        if($request->ajax()){
            return json_encode(array('success'=>true));
        }

    }

}
