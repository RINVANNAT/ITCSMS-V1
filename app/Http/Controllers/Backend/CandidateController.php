<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Candidate\DeleteCandidateRequest;
use App\Http\Requests\Backend\Candidate\EditCandidateRequest;
use App\Http\Requests\Backend\Candidate\StoreCandidateRequest;
use App\Http\Requests\Backend\Candidate\UpdateCandidateRequest;
use App\Models\Department;
use App\Repositories\Backend\Candidate\CandidateRepositoryContract;
use Illuminate\Support\Facades\DB;

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
        $departments = Department::lists('name_kh','id')->toArray();
        return view('backend.candidate.create',compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCandidateRequest $request)
    {
        $this->candidates->create($request->all());
        return redirect()->route('admin.candidates.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
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
        //$student = Student::join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
        //	->select(['students.id_card','students.name_kh','students.name_latin','studentAnnuals.grade_id']);

        //$studentAnnuals = StudentAnnual::with(['student','grade'])->select(['students.id_card','students.name_kh','students.name_latin','grades.name_kh']);

        $candidates = DB::table('candidates')
            ->select(['id','name_kh','name_latin','gender_id','bac_total_grade']);

        $datatables =  app('datatables')->of($candidates);


        return $datatables
            ->editColumn('name_kh', '{!! str_limit($name_kh, 60) !!}')
            ->editColumn('name_latin', '{!! str_limit($name_latin, 60) !!}')
            ->editColumn('gender_id', '{!! $gender_id !!}')
            ->editColumn('bac_total_grade', '{!! $bac_total_grade !!}')
            ->addColumn('action', function ($candidate) {
                return  '<a href="'.route('admin.candidates.edit',$candidate->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.candidates.destroy', $candidate->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
