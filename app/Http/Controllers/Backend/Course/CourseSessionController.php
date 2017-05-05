<?php

namespace App\Http\Controllers\Backend\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Course\CourseSession\StoreCourseSessionRequest;
use App\Http\Requests\Backend\Course\CourseSession\UpdateCourseSessionRequest;
use App\Models\CourseSession;
use App\Repositories\Backend\CourseAnnualClass\CourseAnnualClassRepositoryContract;
use App\Repositories\Backend\CourseSession\CourseSessionRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;


/**
 * Class CourseSessionController
 * @package App\Http\Controllers\Backend\Course
 */
class CourseSessionController extends Controller
{
    /**
     * @var CourseSessionRepositoryContract
     */
    protected $course_sessions;
    protected $courseAnnualClasses;

    /**
     * @param CourseSessionRepositoryContract $course_sessionRepo
     */
    public function __construct(
        CourseSessionRepositoryContract $course_sessionRepo,
        CourseAnnualClassRepositoryContract $courseAnnualClassRepo
    )
    {
        $this->course_sessions = $course_sessionRepo;
        $this->courseAnnualClasses = $courseAnnualClassRepo;
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
        if($request->ajax()){ // This is passing from course_annual/index
            $input = $request->get("data");
            $data = array();
            $data["groups"] = array();

            foreach($input as $key => $ele){
                $val = $ele["value"];
                if($val == "") $val = null;

                if($ele["name"] == "employee"){
                    $data["lecturer_id"] = $val;
                } else if($ele["name"] == "groups[]"){
                    array_push($data["groups"],$val);
                } else {
                    $data[$ele["name"]] = $val;
                }
            }

            $storeCourseSession = $this->course_sessions->create($data);

            if($storeCourseSession) {
                $data = $data + ['course_session_id' => $storeCourseSession->id];
                unset($data['course_annual_id']);/*---we cannot save together of course annual id and course session id */
                $storeCourseAnnualClass = $this->courseAnnualClasses->create($data);

                if($storeCourseAnnualClass) {
                    return Response::json(array("success" => true, "message" => "Sessions are created!"));
                }
            }

        }
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
    public function destroy($id, Request $request)
    {
        if($request->ajax()){
            $this->course_sessions->destroy($id);
            return Response::json(array("success"=> true, "message" => "Session is deleted."));
        } else {
            $this->course_sessions->destroy($id);
            return redirect()->route('admin.configuration.course_sessions.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data(Request $request){
        $course_id = $request->get('course_id');

        $course_annual = DB::table("course_annuals")
            ->where("course_annuals.id",$course_id)
            ->first();

        $groups = DB::table('course_annual_classes')->where([
            ['course_annual_id', $course_id],
            ['course_session_id', null]
        ]);

        if(count($groups->get()) > 1) {
            $groups = $groups->orderBy('group')->lists('group', 'group');
        } else {
            foreach($groups->get() as $group) {
                if($group->group == null) {
                    $groups = DB::table('studentAnnuals')->where([
                        ['department_id', $course_annual->department_id],
                        ['academic_year_id', $course_annual->academic_year_id],
                        ['grade_id', $course_annual->grade_id],
                        ['degree_id', $course_annual->degree_id],
                    ])->orderBy('group')->lists('group', 'group');

                    break;
                }
            }
        }

        asort($groups);

        $course_sessions = CourseSession::leftJoin("employees","employees.id","=","course_sessions.lecturer_id")
            ->leftJoin("course_annuals","course_annuals.id","=","course_sessions.course_annual_id")
            ->where("course_sessions.course_annual_id",$course_id)
            ->with("groups")
            ->select([
                'course_sessions.id',
                'course_sessions.time_course',
                'course_sessions.time_td',
                'course_sessions.time_tp',
                'course_sessions.course_annual_id',
                'course_sessions.lecturer_id',
                'course_annuals.name_kh as name',
                'employees.name_kh as employee'
            ])->get();

        return view("backend.course.courseSession.index",compact("course_sessions","course_annual", "groups"))->render();
    }

//    public function data()
//    {
//
//        $course_sessions = DB::table('course_sessions')
//            ->leftJoin("employees","employees.id","=","course_sessions.lecturer_id")
//            ->leftJoin("course_annuals","course_annuals.id","=","course_sessions.course_annual_id")
//            ->select([
//                'course_sessions.id',
//                'course_sessions.time_course',
//                'course_sessions.time_td',
//                'course_sessions.time_tp',
//                'course_sessions.course_annual_id',
//                'course_annuals.name_kh as name',
//                'employees.name_kh as employee'
//            ]);
//
//        $datatables =  app('datatables')->of($course_sessions);
//
//        if($course_id = $datatables->request->get('course_id')) {
//            $datatables->where('courses.course_annual_id', '=', $course_id);
//        }
//
//        return $datatables
//            ->addColumn('action', function ($course_session) {
//                return  '<a href="#" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
//                        ' <button class="btn btn-xs btn-danger btn-delete" data-remote="#"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
//            })
//            ->make(true);
//    }

}
