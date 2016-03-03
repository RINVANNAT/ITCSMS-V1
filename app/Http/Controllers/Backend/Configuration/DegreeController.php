<?php namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Department;
use App\Models\School;
use App\Repositories\Backend\Degree\DegreeRepositoryContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DegreeController extends Controller
{
    /**
     * @var DegreeRepositoryContract
     */
    protected $degrees;

    /**
     * @param DegreeRepositoryContract $degreeRepo
     */
    public function __construct(
        DegreeRepositoryContract $degreeRepo
    )
    {
        $this->degrees = $degreeRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.degree.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::lists('name_kh','id')->toArray();
        $schools = School::lists('name_kh','id')->toArray();
        return view('backend.configuration.degree.create',compact('departments','schools'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->degrees->create($request->all());
        return redirect()->route('admin.configuration.degrees.index')->withFlashSuccess(trans('alerts.backend.roles.created'));
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
    public function edit($id)
    {
        $departments = Department::lists('name_kh','id');
        $schools = School::lists('name_kh','id')->toArray();
        $degree = $this->degrees->findOrThrowException($id);
        $selected_departments = $degree->departments->lists('id')->toArray();
        return view('backend.configuration.degree.edit',compact('degree','departments','schools','selected_departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->degrees->update($id, $request->all());
        return redirect()->route('admin.configuration.degrees.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->degrees->destroy($id);
        return redirect()->route('admin.configuration.academicYears.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
    }

    public function data(Request $request)
    {
        //$student = Student::join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
        //	->select(['students.id_card','students.name_kh','students.name_latin','studentAnnuals.grade_id']);

        //$studentAnnuals = StudentAnnual::with(['student','grade'])->select(['students.id_card','students.name_kh','students.name_latin','grades.name_kh']);

        $degrees = DB::table('degrees')
            ->select(['id','code','name_kh','name_en','name_fr']);

        $datatables =  app('datatables')->of($degrees);


        return $datatables
            ->editColumn('code', '{!! str_limit($code, 60) !!}')
            ->editColumn('name_kh', '{!! str_limit($name_kh, 60) !!}')
            ->editColumn('name_en', '{!! str_limit($name_en, 60) !!}')
            ->editColumn('name_fr', '{!! str_limit($name_fr, 60) !!}')
            ->addColumn('action', function ($degree) {
                return  '<a href="'.route('admin.configuration.degrees.edit',$degree->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.degrees.destroy', $degree->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
