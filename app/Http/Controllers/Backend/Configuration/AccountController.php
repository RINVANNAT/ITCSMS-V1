<?php

namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    /**
     * @var DepartmentRepositoryContract
     */
    protected $departments;

    /**
     * @param DepartmentRepositoryContract       $departments
     */
    public function __construct(
        DepartmentRepositoryContract $departmentRepo
    )
    {
        $this->departments = $departmentRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.department.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::lists('name_kh','id');
        $schools = School::lists('name_kh','id');
        return view('backend.configuration.department.create',compact('departments','schools'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDepartmentRequest $request)
    {
        $this->departments->create($request->all());
        return redirect()->route('admin.configuration.departments.index')->withFlashSuccess(trans('alerts.backend.roles.created'));
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDepartmentRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getSubDepartments(){

    }

    public function data()
    {
        //$student = Student::join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
        //	->select(['students.id_card','students.name_kh','students.name_latin','studentAnnuals.grade_id']);

        //$studentAnnuals = StudentAnnual::with(['student','grade'])->select(['students.id_card','students.name_kh','students.name_latin','grades.name_kh']);

        $departments = DB::table('departments')
            //->whereNull('parent_id')
            ->select(['id','code','name_kh','name_en','name_fr']);

        $datatables =  app('datatables')->of($departments);


        return $datatables
            ->editColumn('id', '{!! str_limit($id, 60) !!}')
            ->editColumn('code', '{!! str_limit($code, 60) !!}')
            ->editColumn('name_kh', '{!! str_limit($name_kh, 60) !!}')
            ->editColumn('name_en', '{!! str_limit($name_en, 60) !!}')
            ->editColumn('name_fr', '{!! str_limit($name_fr, 60) !!}')
            ->addColumn('action', function ($department) {
                return '<a href="#edit-'.$department->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> '. trans('buttons.general.crud.edit').'</a>';
            })
            ->make(true);
    }

}
