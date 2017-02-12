<?php

namespace App\Http\Controllers\Backend\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Course\CourseSession\StoreCourseSessionRequest;
use App\Http\Requests\Backend\Course\CourseSession\UpdateCourseSessionRequest;
use App\Models\CourseSession;
use App\Models\School;
use App\Repositories\Backend\CourseSession\CourseSessionRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CourseSessionController extends Controller
{
    /**
     * @var CourseSessionRepositoryContract
     */
    protected $course_sessions;

    /**
     * @param CourseSessionRepositoryContract $course_sessionRepo
     */
    public function __construct(
        CourseSessionRepositoryContract $course_sessionRepo
    )
    {
        $this->course_sessions = $course_sessionRepo;
    }


    public function create()
    {
        return view('backend.configuration.course_session.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCourseSessionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseSessionRequest $request)
    {
        $this->course_sessions->create($request->all());
        return redirect()->route('admin.configuration.course_sessions.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $course_session = $this->course_sessions->findOrThrowException($id);

        return view('backend.configuration.course_session.edit',compact('course_session'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCourseSessionRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCourseSessionRequest $request, $id)
    {
        $this->course_sessions->update($id, $request->all());
        return redirect()->route('admin.configuration.course_sessions.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->course_sessions->destroy($id);
        return redirect()->route('admin.configuration.course_sessions.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
    }

    public function data()
    {

        $course_sessions = DB::table('course_sessions')
            ->leftJoin("employees","employees.id","=","course_sessions.lecturer_id")
            ->leftJoin("course_annuals","course_annuals.id","=","course_sessions.course_annual_id")
            ->select([
                'course_sessions.id',
                'course_sessions.time_course',
                'course_sessions.time_td',
                'course_sessions.time_tp',
                'course_annuals.name_kh as name',
                'employees.name_kh as employee'
            ]);

        $datatables =  app('datatables')->of($course_sessions);


        return $datatables
            ->addColumn('action', function ($course_session) {
//                return ''; // '.route('admin.configuration.course_sessions.edit',$course_session->id).'///'.route('admin.configuration.course_sessions.destroy', $course_session->id) .'
                return  '<a href="#" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                        ' <button class="btn btn-xs btn-danger btn-delete" data-remote="#"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
