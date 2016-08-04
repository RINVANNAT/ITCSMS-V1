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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class CandidateController extends Controller
{
    /**
     * @var CandidateRepositoryContract
     */
    protected $candidates;
    protected $studentRepo;

    /**
     * @param CandidateRepositoryContract $candidateRepo
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $exam = Exam::where('id',1)->first();
        $degrees = Degree::lists('name_kh','id');
        $genders = Gender::lists('name_kh','id');
        $promotions = Promotion::orderBy('name','desc')->lists('name','id');
        $highSchools = HighSchool::lists('name_kh','id');
        $provinces = Origin::lists('name_kh','id');
        $gdeGrades = GdeGrade::lists('name_en','id');
        $departments = Department::where('is_specialist',true)->where('parent_id',11)->get();
        $academicYears = AcademicYear::orderBy('name_latin','desc')->lists('name_latin','id');
        return view('backend.candidate.create',compact('departments','degrees','genders','promotions','highSchools','provinces','gdeGrades','academicYears','exam'));
    }

    public function popup_create(CreateCandidateRequest $request)
    {
        $studentBac2_id = Input::get('studentBac2_id');
        $exam_id = Input::get('exam_id');
        $highschool = null;

        if($studentBac2_id==0){ // This allow to create candidate manually
            $studentBac2 = null;
            $highschool = null;
        } else {
            $studentBac2 = StudentBac2::find($studentBac2_id);
            $highschool = HighSchool::find($studentBac2->highschool_id);
        }



        //dd($highschool);
        $exam = Exam::where('id',$exam_id)->first();

        $degrees = Degree::lists('name_kh','id');
        $genders = Gender::lists('name_kh','id');
        $promotions = Promotion::orderBy('id','desc')->lists('name','id');

        //$highSchools = HighSchool::lists('name_kh','id');
        $provinces = Origin::lists('name_kh','id');
        $gdeGrades = GdeGrade::lists('name_en','id');
        $departments = Department::where('is_specialist',true)->where('parent_id',11)->get();
        $academicYears = AcademicYear::orderBy('name_latin','desc')->lists('name_latin','id');

        return view('backend.candidate.popup_create',compact('departments','degrees','genders','promotions','provinces','gdeGrades','academicYears','exam','studentBac2','highschool'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCandidateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCandidateRequest $request)
    {
        $this->candidates->create($request->all());
        return redirect()->route('admin.candidates.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
    }

    public function popup_store(StoreCandidateRequest $request)
    {
        $result = $this->candidates->create($request->all());

        if($request->ajax()){
            if($result['status']==true){
                return Response::json($result);
            } else {
                return Response::json($result,422);
            }

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditCandidateRequest $request, $id)
    {
        $departments = Department::lists('name_kh','id');
        $candidate = $this->candidates->findOrThrowException($id);
        $selected_departments = $candidate->departments->lists('id')->toArray();
        return view('backend.candidate.edit',compact('candidate','departments','selected_departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCandidateRequest $request, $id)
    {
        $this->candidates->update($id, $request->all());
        return redirect()->route('admin.candidates.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
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

    public function data()
    {
        $candidates = DB::table('candidates')
            ->leftJoin('origins','candidates.province_id','=','origins.id')
            ->leftJoin('gdeGrades','candidates.bac_total_grade','=','gdeGrades.id')
            ->leftJoin('genders','candidates.gender_id','=','genders.id')
            ->leftJoin('rooms','candidates.room_id','=','rooms.id')
            ->leftJoin('buildings','rooms.building_id','=','buildings.id')
            ->select([
                'candidates.id',DB::raw("CONCAT(rooms.name,buildings.code) as room"),'candidates.register_id','candidates.name_kh','candidates.name_latin','genders.name_kh as gender_name_kh','gdeGrades.name_en as bac_total_grade',
                'origins.name_kh as province', 'dob','result','is_paid','is_register'
            ]);

        if($exam_id = Input::get('exam_id')){
            $candidates = $candidates->where('exam_id',$exam_id);
        }
        if($academic_year_id = Input::get('academic_year_id')){
            $candidates = $candidates->where('academic_year_id',$exam_id);
        }
        if($degree_id = Input::get('degree_id')){
            $candidates = $candidates->where('degree_id',$degree_id);
        }

        $datatables =  app('datatables')->of($candidates);


        return $datatables
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
                } else if($candidate->result == "Fail"){
                    return '<span class="label label-danger">'.$candidate->result.'</span>';
                } else { // Rejected
                    return '<span class="label label-info">'.$candidate->result.'</span>';
                }
            })
            ->editColumn('dob',function($candidate){
                $date = Carbon::createFromFormat('Y-m-d h:i:s',$candidate->dob);
                return $date->format('d/m/Y');
            })
            ->addColumn('action', function ($candidate) {
                $action = "";

                if($candidate->result == "Pending"){
                    $action = '<a href="'.route('admin.candidates.edit',$candidate->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                        ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.candidates.destroy', $candidate->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
                } else {
                    if($candidate->is_register){
                        $action = '<span style="color:green"><i class="fa fa-check"></i></span>';
                    } else if($candidate->is_paid){
                        $action = ' <button class="btn btn-xs btn-register" data-remote="'.route('admin.candidate.register', $candidate->id) .'"><i class="fa fa-check-circle-o" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.register') . '"></i></button>';
                    }
                }
                return $action;
            })
            ->setRowClass(function ($candidate) {

                return $candidate->result;
            })
            ->make(true);
    }

    public function register(RegisterCandidateRequest $request, $id){
        $candidate = Candidate::where('id',$id)->first();

        $this->studentRepo->register($candidate);

        if($request->ajax()){
            return json_encode(array('success'=>true));
        }

    }

}
