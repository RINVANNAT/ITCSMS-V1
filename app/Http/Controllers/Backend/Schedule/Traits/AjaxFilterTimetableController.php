<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\DepartmentOption;
use App\Models\Room;
use App\Models\Schedule\Timetable\Week;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

/**
 * Class AjaxFilterTimetableController
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait AjaxFilterTimetableController
{
    /**
     * Filter timetable.
     *
     * @return mixed
     */
    public function filter()
    {
        return Response::json(['status' => true, 'data' => request()->all()]);
    }

    /**
     * Filter courses sessions.
     *
     * @return mixed
     */
    public function filterCoursesSessions()
    {
        return Response::json(['status' => true, 'data' => request()->all()]);
    }

    /**
     * Get weeks by semester.
     * @return array
     * @internal param Request $request
     */
    public function get_weeks()
    {
        $semester_id = \request('semester_id');

        if (isset($semester_id)) {
            return Response::json(['status' => true, 'weeks' => Week::where('semester_id', $semester_id)->get()]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Get options by department.
     *
     * @return mixed
     */
    public function get_options()
    {
        $department_id = \request('department_id');

        if (isset($department_id)) {
            return Response::json(['status' => true, 'options' => DepartmentOption::where('department_id', $department_id)->get()]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Get course sessions.
     *
     * @return mixed
     */
    public function get_course_sessions()
    {
        $academic_year_id = request('academicYear');
        $department_id = request('department');
        $degree_id = request('degree');
        $grade_id = request('grade');
        $semester_id = request('semester');
        $option_id = request('option');

        if (array_key_exists('option', request()->all())) {

            $course_sessions = DB::table('course_sessions')
                ->join('course_annuals', 'course_annuals.id', '=', 'course_sessions.course_annual_id')
//                ->whereIn('course_sessions.course_annual_id', function ($query) use (
//                    $academic_year_id,
//                    $department_id,
//                    $degree_id,
//                    $grade_id,
//                    $semester_id,
//                    $option_id
//                ) {
//                    $query->select('course_annuals.id')
//                        ->from('course_annuals')
//                        ->where([
//                            'course_annuals.academic_year_id' => $academic_year_id,
//                            'course_annuals.department_id' => $department_id,
//                            'course_annuals.degree_id' => $degree_id,
//                            'course_annuals.grade_id' => $grade_id,
//                            'course_annuals.semester_id' => $semester_id,
//                            'course_annuals.department_option_id' => $option_id
//                        ]);
//                })
                ->leftJoin('employees', 'employees.id', '=', 'course_sessions.lecturer_id')
                ->select(
                    'course_sessions.id as id',
                    'course_annuals.name_en as course_name',
                    'employees.name_latin as teacher_name',
                    'course_sessions.time_tp as tp',
                    'course_sessions.time_td as td',
                    'course_sessions.time_course as courses'
                )
                ->orderBy('course_name', 'as', 'asc')
                ->get();
        } else {
            $course_sessions = DB::table('course_sessions')
                ->join('course_annuals', 'course_annuals.id', '=', 'course_sessions.course_annual_id')
                ->whereIn('course_sessions.course_annual_id', function ($query) use (
                    $academic_year_id,
                    $department_id,
                    $degree_id,
                    $grade_id,
                    $semester_id
                ) {
                    $query->select('course_annuals.id')
                        ->from('course_annuals')
                        ->where([
                            'course_annuals.academic_year_id' => $academic_year_id,
                            'course_annuals.department_id' => $department_id,
                            'course_annuals.degree_id' => $degree_id,
                            'course_annuals.grade_id' => $grade_id,
                            'course_annuals.semester_id' => $semester_id
                        ]);
                })
                ->leftJoin('employees', 'employees.id', '=', 'course_sessions.lecturer_id')
                ->select(
                    'course_sessions.id as id',
                    'course_annuals.name_en as course_name',
                    'employees.name_latin as teacher_name',
                    'course_sessions.time_tp as tp',
                    'course_sessions.time_td as td',
                    'course_sessions.time_course as courses'
                )
                ->orderBy('course_name', 'as', 'asc')
                ->get();
        }

        if (count($course_sessions) > 0) {
            return Response::json([
                'status' => true,
                'course_sessions' => $course_sessions
            ]);
        } else {
            return Response::json([
                'status' => false
            ]);
        }
    }

    /**
     * Get groups.
     *
     * @return mixed
     */
    public function get_groups()
    {
        $academic_year_id = request('academicYear');
        $department_id = request('department');
        $degree_id = request('degree');
        $grade_id = request('grade');

        $groups = DB::table('studentAnnuals')
            ->where([
                'academic_year_id' => $academic_year_id,
                'department_id' => $department_id,
                'degree_id' => $degree_id,
                'grade_id' => $grade_id
            ])
            ->select('studentAnnuals.group', 'studentAnnuals.group_id')
            ->distinct('studentAnnuals.group')
            ->get();


        if (count($groups) > 1) {
            return Response::json(['status' => true, 'groups' => $groups]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * @return array|string
     */
    public function search_rooms()
    {
        if (array_key_exists('query', request()->all())) {
            if (request('query') != null) {
                $rooms = Room::where('name', 'like', request('query') . "%")->get();
                if (count($rooms) > 0) {
                    return Response::json([
                        'status' => true,
                        'rooms' => $rooms
                    ]);
                } else {
                    return Response::json(['status' => false]);
                }
            }
        }
        return Response::json([
            'status' => true,
            'rooms' => Room::all()
        ]);
    }
}