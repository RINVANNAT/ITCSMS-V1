<?php

namespace App\Repositories\Backend\Schedule\Timetable;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Models\CourseSession;
use App\Models\Schedule\Timetable\Timetable;
use Carbon\Carbon;

/**
 * Interface TimetableSlotRepositoryContract
 * @package App\Repositories\Backend\Schedule\Timetable
 */
interface TimetableSlotRepositoryContract
{
    /**
     * Create timetable slot.
     *
     * @param Timetable $timetable
     * @param CreateTimetableRequest $request
     * @return mixed
     * @internal param CourseSession $courseSession
     */
    public function create_timetable_slot(Timetable $timetable, CreateTimetableRequest $request);

    /**
     * Get timetable slots by a timetable.
     *
     * @param Timetable $timetable
     * @return mixed
     */
    public function get_timetable_slots(Timetable $timetable);

    /**
     * Get interval Hours.
     *
     * @param Carbon $start
     * @param Carbon $end
     * @return mixed
     */
    public function durations(Carbon $start, Carbon $end);

    /**
     * Set value into remaining column course_session.
     *
     * @return mixed
     */
    public function set_value_into_time_remaining_course_session();
}