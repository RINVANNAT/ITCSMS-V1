<?php

namespace App\Repositories\Backend\Schedule\Timetable;

use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Models\Schedule\Timetable\Timetable;

/**
 * Interface TimetableRepositoryContract
 * @package App\Repositories\Backend\Schedule\Timetable
 */
interface TimetableRepositoryContract
{
    /**
     * Filter course session with the attribute below.
     *
     * @param CreateTimetableRequest $request
     * @return mixed
     */
    public function find_timetable_is_existed(CreateTimetableRequest $request);

    /**
     * Create blank timetable table.
     *
     * @param CreateTimetableRequest $request
     * @return mixed
     */
    public function create_timetable(CreateTimetableRequest $request);

    /**
     * To check timetable slot has half hour.
     *
     * @param Timetable $timetable
     * @return mixed
     */
    public function hasHalfHour(Timetable $timetable);
}