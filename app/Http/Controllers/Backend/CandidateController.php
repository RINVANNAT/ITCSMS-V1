<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Candidate\CreateCandidateRequest;
use App\Http\Requests\Backend\Candidate\DeleteCandidateRequest;
use App\Http\Requests\Backend\Candidate\EditCandidateRequest;
use App\Http\Requests\Backend\Candidate\StoreCandidateRequest;
use App\Http\Requests\Backend\Candidate\UpdateCandidateRequest;
use App\Models\AcademicYear;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class CandidateController extends Controller
{
    /**
     * @var CandidateRepositoryContract
     */
    protected $candidates;

    /**
     * @param CandidateRepositoryContract $candidateRepo
     */
    public function __construct(
        CandidateRepositoryContract $candidateRepo
    )
    {
        $this->candidates = $candidateRepo;
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

        if($studentBac2_id==0){ // This allow to create candidate manually
            $studentBac2 = null;
        } else {
            $studentBac2 = StudentBac2::where('id',$studentBac2_id)->first();
        }

        $exam = Exam::where('id',$exam_id)->first();

        $degrees = Degree::lists('name_kh','id');
        $genders = Gender::lists('name_kh','id');
        $promotions = Promotion::orderBy('name','desc')->lists('name','id');
        $highSchools = HighSchool::lists('name_kh','id');
        $provinces = Origin::lists('name_kh','id');
        $gdeGrades = GdeGrade::lists('name_en','id');
        $departments = Department::where('is_specialist',true)->where('parent_id',11)->get();
        $academicYears = AcademicYear::orderBy('name_latin','desc')->lists('name_latin','id');

        return view('backend.candidate.popup_create',compact('departments','degrees','genders','promotions','highSchools','provinces','gdeGrades','academicYears','exam','studentBac2'));
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
        $candidate = $this->candidates->create($request->all());

        return view('backend.candidate.popup_success')->withFlashSuccess(trans('alerts.backend.generals.created'));

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
            ->leftJoin('gdeGrades','candidates.bac_total_grade','=','gdeGrades.id')
            ->leftJoin('genders','candidates.gender_id','=','genders.id')
            ->select(['candidates.id','candidates.name_kh','candidates.name_latin','genders.name_kh as gender_name_kh','gdeGrades.name_en as bac_total_grade']);

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
            ->addColumn('action', function ($candidate) {
                return  '<a href="'.route('admin.candidates.edit',$candidate->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.candidates.destroy', $candidate->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
