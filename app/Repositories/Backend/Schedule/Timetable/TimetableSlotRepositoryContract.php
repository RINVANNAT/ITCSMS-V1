<?php

namespace App\Repositories\Backend\Schedule\Timetable;

use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Models\CourseAnnualClass;
use App\Models\CourseSession;
use App\Models\Group;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
use App\Models\Schedule\Timetable\Week;
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
     * Define timetable slot hos conflict room.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function is_conflict_room(TimetableSlot $timetableSlot);

    /**
     * Check timetable slot can merge.
     *
     * @param TimetableSlot $timetableSlot
     * @param TimetableSlot $mergedTimetableSlot
     * @return mixed
     */
    public function can_merge(TimetableSlot $timetableSlot, TimetableSlot $mergedTimetableSlot);

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
     * @param CourseAnnualClass $courseAnnualClass
     * @return mixed
     */
    public function export_slot(CourseSession $courseSession, CourseAnnualClass $courseAnnualClass);

    /**
     * Update course session and remove timetable slot.
     *
     * @param CourseSession $course_session
     * @return mixed
     */
    public function update_course_session(CourseSession $course_session);

    /**
     * Create merge timetable slot.
     *
     * @param CreateTimetableRequest $request
     * @return mixed
     */
    public function create_merge_timetable_slot(CreateTimetableRequest $request);

    /**
     * Update merge timetable slot.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function update_merge_timetable_slot(TimetableSlot $timetableSlot);

    /**
     * Check conflict lecturer.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function check_conflict_lecturer(TimetableSlot $timetableSlot);

    /**
     * Update Timetable Slot when merge.
     *
     * @param TimetableSlot $timetableSlot
     * @param integer $group_merge_id
     * @return mixed
     */
    public function update_timetable_slot_when_merge(TimetableSlot $timetableSlot, $group_merge_id);

    /**
     * Find room existed merge timetable slot.
     *
     * @param $group_merge_id
     * @return mixed
     */
    public function find_room_existed_merge_timetable_slot($group_merge_id);

    /**
     * Sort groups.
     *
     * @param array $groups
     * @param $field
     * @return mixed
     */
    public function sort_group($groups = array(), $field);

    /**
     * Clone timetable.
     *
     * @param Timetable $timetable
     * @param array $groups
     * @param array $weeks
     * @return mixed
     */
    public function clone_timetable(Timetable $timetable, $groups = [], $weeks = []);

    /**
     * Copied timetable.
     *
     * @param Timetable $timetable
     * @param Group|null $group
     * @param Week $week
     * @return mixed
     */
    public function copied_timetable(Timetable $timetable, Group $group = null, Week $week);

    /**
     * Copied timetable slot.
     *
     * @param Timetable $timetable
     * @param Timetable $copiedTimetable
     * @return mixed
     */
    public function copied_timetable_slot(Timetable $timetable, Timetable $copiedTimetable);
}
