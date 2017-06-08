<?php

namespace App\Repositories\Backend\Schedule\Timetable;

use App\Models\Schedule\Timetable\Timetable;

/**
 * Interface PrintTimetableRepositoryContract
 * @package App\Repositories\Backend\Schedule\Timetable
 */
interface PrintTimetableRepositoryContract
{
    /**
     * Get timetable by group, week and the other properties timetable.
     *
     * @param Timetable $timetable
     * @param null $group
     * @param $week
     * @return mixed
     */
    public function find_timetable(Timetable $timetable, $group = null, $week);
}