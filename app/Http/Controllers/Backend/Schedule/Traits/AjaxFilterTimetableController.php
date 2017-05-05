<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\DepartmentOption;
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
     *
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
        $option_id = request('option') == null ? null : request('option');
        $group_id = request('group') == null ? null : request('group');

        /*dd(request('group'));*/
        $course_sessions = DB::table('course_annuals')
            ->where([
                ['course_annuals.academic_year_id', $academic_year_id],
                ['course_annuals.department_id', $department_id],
                ['course_annuals.degree_id', $degree_id],
                ['course_annuals.grade_id', $grade_id],
                ['course_annuals.semester_id', $semester_id],
                ['course_annuals.department_option_id', $option_id],
            ])
            ->join('course_sessions', 'course_sessions.course_annual_id', '=', 'course_annuals.id')
            ->leftJoin('employees', 'employees.id', '=', 'course_sessions.lecturer_id')
            ->where(function ($query) use ($group_id) {
                $groups = DB::table('course_annual_classes')->where('course_annual_classes.group_id', $group_id)
                    ->lists('course_annual_classes.course_session_id');
                $query->whereIn('course_sessions.id', $groups == null ? null : $groups);
            })
            ->select(
                'course_sessions.id',
                'course_sessions.time_tp as tp',
                'course_sessions.time_td as td',
                'course_sessions.time_course as tc',
                'course_annuals.name_en as course_name',
                'employees.name_latin as teacher_name'
            )
            ->get();

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
        $option_id = request('option') == null ? null : request('option');

        $groups = DB::table('studentAnnuals')
            ->where([
                ['academic_year_id', $academic_year_id],
                ['department_id', $department_id],
                ['degree_id', $degree_id],
                ['grade_id', $grade_id],
                ['department_option_id', $option_id]
            ])
            ->join('groups', 'groups.id', '=', 'studentAnnuals.group_id')
            ->orderBy('groups.code', 'asc')
            ->select('studentAnnuals.group_id as id', 'groups.code as name')
            ->distinct('studentAnnuals.group_id')
            ->get();

        if (count($groups) > 1) {
            return Response::json(['status' => true, 'groups' => $groups]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Search rooms.
     *
     * @return array|string
     */
    public function search_rooms()
    {
        if (array_key_exists('query', request()->all())) {
            if (request('query') != ' ') {
                $rooms = DB::table('rooms')
                    ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
                    ->where('rooms.name', 'like', '%' . request('query') . '%')
                    ->orWhere('buildings.code', 'like', '%' . request('query') . '%')
                    ->select('rooms.id as id', 'rooms.name as name', 'buildings.code as code')
                    ->get();
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
        $this->get_rooms();
    }

    /**
     * Get all rooms.
     *
     * @return mixed
     */
    public function get_rooms()
    {
        $rooms = DB::table('rooms')
            ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->select('rooms.id as id', 'rooms.name as name', 'buildings.code as code')
            ->get();

        return Response::json([
            'status' => true,
            'rooms' => $rooms
        ]);
    }

    /**
     * Get timetable slots.
     *
     * @return mixed
     */
    public function get_timetable_slots()
    {
        dd(\request()->all());
    }
}