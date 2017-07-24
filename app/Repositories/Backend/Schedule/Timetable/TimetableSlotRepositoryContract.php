<?php

namespace App\Repositories\Backend\Schedule\Timetable;

use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableSlotRequest;
use App\Models\CourseAnnualClass;
use App\Models\CourseSession;
use App\Models\Schedule\Timetable\Slot;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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
     * @param CreateTimetableSlotRequest $request
     * @return mixed
     * @internal param CourseSession $courseSession
     */
    public function create_timetable_slot(Timetable $timetable, CreateTimetableSlotRequest $request);

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
     * @param CreateTimetableSlotRequest $request
     * @return mixed
     */
    public function create_merge_timetable_slot(CreateTimetableSlotRequest $request);

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
     * @param null $group
     * @param $week
     * @return mixed
     */
    public function copied_timetable(Timetable $timetable, $group = null, $week);

    /**
     * Copied timetable slot.
     *
     * @param Slot $slot
     * @param Timetable $timetable
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function copied_timetable_slot(Slot $slot, Timetable $timetable, TimetableSlot $timetableSlot);

    /**
     * Find timetable by references the other timetable, group and week
     * @param Timetable $timetable
     * @param null $group
     * @param $week
     * @return mixed
     */
    public function find_timetable_by_reference_group_and_week(Timetable $timetable, $group = null, $week);

    /**
     * Find slot by timetable slot.
     *
     * @param TimetableSlot $timetableSlot
     * @param null|$group
     * @return mixed
     */
    public function find_slot_by_reference_timetable_slot(TimetableSlot $timetableSlot, $group = null);

    /**
     * find timetable, if itself.
     *
     * @param Timetable $timetable
     * @param null $group
     * @param $week
     * @return mixed
     */
    public function is_timetable_self(Timetable $timetable, $group = null, $week);

    /**
     * Start cloning.
     *
     * @param Timetable $timetable
     * @param null $group
     * @param $week
     * @param $result
     * @return mixed
     */
    public function is_cloning(Timetable $timetable, $group = null, $week, $result);

    /**
     * Cron job set permission.
     *
     * @return mixed
     */
    public function set_permission_create_timetable();

    /**
     * Get timetable slot with details info.
     *
     * @param Timetable $timetable
     * @param null $by_teacher
     * @return mixed
     */
    public function get_timetable_slot_details(Timetable $timetable, $by_teacher = null);

    /**
     * Get Timetable Slot with Conflict Info.
     *
     * @param Timetable $timetable
     * @param Collection $timetableSlots
     * @param null $by_teacher
     * @return mixed
     */
    public function get_timetable_slot_with_conflict_info(Timetable $timetable, Collection $timetableSlots, $by_teacher = null);

    /**
     * Get all student annuals.
     *
     * @param Timetable $timetable
     * @return mixed
     */
    public function find_student_annual_ids(Timetable $timetable);

    /**
     * Get group student annual from language.
     *
     * @param $department_id
     * @param array $student_annual_ids
     * @param Timetable $timetable
     * @return mixed
     */
    public function get_group_student_annual_form_language($department_id, array $student_annual_ids, Timetable $timetable);

    /**
     * Get group student annual from language.
     *
     * @param $department_id
     * @param array $student_annual_ids
     * @param Timetable $timetable
     * @return mixed
     */
    public function find_group_student_annual_form_language($department_id, array $student_annual_ids, Timetable $timetable);

    /**
     * Get timetables from dept language.
     *
     * @param array $group_students
     * @param Timetable $timetable
     * @param $department_id
     * @return mixed
     */
    public function get_timetables_form_language_by_student_annual(array $group_students, Timetable $timetable, $department_id);

    /**
     * Get timetable slots from language dept.
     *
     * @param Collection $timetables
     * @param array $groupStudentsLanguage
     * @return mixed
     */
    public function get_timetable_slot_language_dept(Collection $timetables, array $groupStudentsLanguage);

    /**
     * Set language timetable slot into TimetableSlots.
     *
     * @param Collection $timetableSlots
     * @param Collection $groupsRoom
     * @param Collection $languageTimetableSlots
     * @return mixed
     */
    public function set_timetable_slot_language(Collection $timetableSlots, Collection $groupsRoom, Collection $languageTimetableSlots);
}
