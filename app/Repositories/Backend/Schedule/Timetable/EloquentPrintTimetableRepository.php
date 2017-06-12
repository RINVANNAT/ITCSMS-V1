<?php

namespace App\Repositories\Backend\Schedule\Timetable;

use App\Models\Schedule\Timetable\Timetable;

/**
 * Class EloquentPrintTimetableRepository
 * @package App\Repositories\Backend\Schedule\Timetable
 */
class EloquentPrintTimetableRepository implements PrintTimetableRepositoryContract
{

    /**
     * Get timetable by group, week and the other properties timetable.
     *
     * @param Timetable $timetable
     * @param null $group
     * @param $week
     * @return mixed
     */
    public function find_timetable(Timetable $timetable, $group = null, $week)
    {
        return Timetable::where([
            ['academic_year_id', $timetable->academic_year_id],
            ['department_id', $timetable->department_id],
            ['degree_id', $timetable->degree_id],
            ['option_id', $timetable->option_id],
            ['semester_id', $timetable->semester_id],
            ['group_id', $timetable->group],
            ['grade_id', $timetable->grade_id],
            ['week_id', $week]
        ])->first();
    }
}