<?php

namespace App\Repositories\Backend\Schedule\Timetable;

use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Models\CourseAnnualClass;
use App\Models\CourseSession;
use App\Models\Room;
use App\Models\Schedule\Timetable\Slot;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
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

    /**
     * Define timetable slot hos conflict room.
     *
     * @param TimetableSlot $timetableSlot
     * @param Room $room
     * @return mixed
     */
    public function is_conflict_room(TimetableSlot $timetableSlot, Room $room);

    /**
     * Define timetable slot has conflict course.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function is_conflict_course(TimetableSlot $timetableSlot);

    /**
     * Define timetable slot has conflict lecturer.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function is_conflict_lecturer(TimetableSlot $timetableSlot);

    /**
     * Get associate with.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function get_conflict_with(TimetableSlot $timetableSlot);

    /**
     * Get room info with building.
     *
     * @param $room_id
     * @return mixed
     */
    public function get_room_info($room_id);

    /**
     * Get group information.
     *
     * @param $group_id
     * @return mixed
     */
    public function get_group_info($group_id);

    /**
     * Check is already merged.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function is_merged(TimetableSlot $timetableSlot);

    /**
     * Export course sessions.
     *
     * @return mixed
     */
    public function export_course_sessions();

    /**
     * Export from course_session to slots.
     *
     * @param CourseSession $courseSession
     * @return mixed
     */
    public function export_slot(CourseSession $courseSession);

    /**
     * Export from course_annual_classes to slot_classes.
     *
     * @param CourseAnnualClass $courseAnnualClass
     * @param Slot $slot
     * @return mixed
     */
    public function export_slot_class(CourseAnnualClass $courseAnnualClass, Slot $slot);

    /**
     * Update course session and remove timetable slot.
     *
     * @param CourseSession $course_session
     * @return mixed
     */
    public function update_course_session(CourseSession $course_session);
}