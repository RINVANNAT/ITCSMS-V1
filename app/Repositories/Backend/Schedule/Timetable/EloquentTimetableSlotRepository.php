<?php

namespace App\Repositories\Backend\Schedule\Timetable;

use Illuminate\Support\Facades\DB;

/**
 * Class EloquentTimetableSlotRepository
 * @package App\Repositories\Backend\Schedule\Timetable
 */
class EloquentTimetableSlotRepository implements TimetableSlotRepositoryContract
{

    /**
     * Filter course session with the attribute below.
     *
     * @param null $academic_year_id
     * @param null $department_id
     * @param null $degree_id
     * @param null $grade_id
     * @param null $option_id
     * @param null $semester_id
     * @param null $week_id
     * @param null $group_id
     * @return mixed
     */
    public function filter_course_sessions(
        $academic_year_id = null,
        $department_id = null,
        $degree_id = null,
        $grade_id = null,
        $option_id = null,
        $semester_id = null,
        $week_id = null,
        $group_id = null
    )
    {
        return DB::table('course_sessions')->get();
    }
}