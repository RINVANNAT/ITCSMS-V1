<?php

namespace App\Repositories\Backend\Schedule\Timetable;

use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableSlotRequest;
use App\Models\Configuration;
use App\Models\Course;
use App\Models\CourseAnnual;
use App\Models\CourseAnnualClass;
use App\Models\CourseSession;
use App\Models\Group;
use App\Models\Room;
use App\Models\Schedule\Timetable\MergeTimetableSlot;
use App\Models\Schedule\Timetable\Slot;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class EloquentTimetableSlotRepository
 * @package App\Repositories\Backend\Schedule\Timetable
 */
class EloquentTimetableSlotRepository implements TimetableSlotRepositoryContract
{
    /**
     * Create timetable slot.
     *
     * @param Timetable $timetable
     * @param CreateTimetableSlotRequest $request
     * @return mixed
     * @internal param CourseSession $courseSession
     */
    public function create_timetable_slot(Timetable $timetable, CreateTimetableSlotRequest $request)
    {
        // create new group merge
        $newMergeTimetableSlot = $this->create_merge_timetable_slot($request);
        // check new group merge created or not.
        if ($newMergeTimetableSlot instanceof MergeTimetableSlot) {
            // find Slot match with request.
            $slot = Slot::find($request->slot_id);
            if ($slot instanceof Slot) {
                // find durations between two start and end date.
                $duration = $this->durations(new Carbon($request->start), new Carbon($request->end == null ? $request->start : $request->end));
                if ($slot->time_remaining > 0 && $slot->time_remaining >= $duration) {
                    // crate new instance
                    $newTimetableSlot = new TimetableSlot();

                    $newTimetableSlot->timetable_id = $timetable->id;
                    $newTimetableSlot->course_session_id = $request->course_session_id;
                    $request->room_id == null ?: $newTimetableSlot->room_id = $request->room_id;
                    $newTimetableSlot->slot_id = $request->slot_id;
                    $newTimetableSlot->group_merge_id = $newMergeTimetableSlot->id;
                    $newTimetableSlot->course_name = $request->course_name;
                    $newTimetableSlot->teacher_name = $request->teacher_name;
                    $newTimetableSlot->type = $request->course_type;
                    $newTimetableSlot->start = new Carbon($request->start);
                    $newTimetableSlot->end = new Carbon($request->end == null ? $request->start : $request->end);
                    $newTimetableSlot->durations = $duration;
                    $newTimetableSlot->created_uid = auth()->user()->id;
                    $newTimetableSlot->updated_uid = auth()->user()->id;
                    // check new timetable slot created or not.
                    if ($newTimetableSlot->save()) {
                        // update total times of slot
                        $slot->time_remaining = $slot->time_remaining - $duration;
                        $slot->updated_at = Carbon::now();
                        $slot->update();
                        return $newTimetableSlot;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Get timetable slots by a timetable.
     *
     * @param Timetable $timetable
     * @return mixed
     */
    public function get_timetable_slots(Timetable $timetable)
    {
        return TimetableSlot::where('timetable_id', $timetable->id)->get();
    }

    /**
     * Get interval Hours.
     *
     * @param Carbon $start
     * @param Carbon $end
     * @return int
     */
    public function durations(Carbon $start, Carbon $end)
    {
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);
        if (($end->minute > 0 && $start->minute == 0) || ($end->minute == 0 && $start->minute > 0)) {
            return $start->diffInHours($end) + 0.5;
        } else {
            return $start->diffInHours($end);
        }
    }

    /**
     * Get associate with.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function get_conflict_with(TimetableSlot $timetableSlot)
    {
        $info_conflict_with = DB::table('timetables')
            ->join('academicYears', 'academicYears.id', '=', 'timetables.academic_year_id')
            ->join('departments', 'departments.id', '=', 'timetables.department_id')
            ->join('degrees', 'degrees.id', '=', 'timetables.degree_id')
            ->join('grades', 'grades.id', '=', 'timetables.grade_id')
            ->join('semesters', 'semesters.id', '=', 'timetables.semester_id')
            ->leftJoin('departmentOptions', 'departmentOptions.id', '=', 'timetables.option_id')
            ->leftJoin('groups', 'groups.id', '=', 'timetables.group_id')
            ->join('weeks', 'weeks.id', '=', 'timetables.week_id')
            ->where([
                ['timetables.id', $timetableSlot->timetable->id],
                ['groups.id', $timetableSlot->timetable->group_id]
            ])
            ->select(
                'departments.code as department',
                'academicYears.name_latin as academic_year',
                'degrees.code as degree',
                'grades.code as grade',
                'semesters.name_en as semester',
                'departmentOptions.name_en as option',
                'groups.code as group',
                'weeks.name_en as week'
            )
            ->get();
        return $info_conflict_with;
    }

    /**
     * Get room info with building.
     *
     * @param $room_id
     * @return mixed
     */
    public function get_room_info($room_id)
    {
        return Room::find($room_id)
            ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->select('rooms.name', 'buildings.code')
            ->get();
    }

    /**
     * Get group information.
     *
     * @param $group_id
     * @return mixed
     */
    public function get_group_info($group_id)
    {
        return Group::find($group_id);
    }

    /**
     * Export from course_session to slots.
     *
     * @param CourseSession $course_session
     * @param CourseAnnualClass $courseAnnualClass
     * @return bool
     */
    public function export_slot(CourseSession $course_session, CourseAnnualClass $courseAnnualClass)
    {
        $newSlot = new Slot();
        $newSlot->time_tp = $course_session->time_tp;
        $newSlot->time_td = $course_session->time_td;
        $newSlot->time_course = $course_session->time_course;
        $newSlot->course_annual_id = $course_session->course_annual_id;
        $newSlot->course_session_id = $course_session->id;
        $newSlot->lecturer_id = $course_session->lecturer_id;
        $newSlot->responsible_department_id = $course_session->responsible_department_id;
        $newSlot->group_id = $courseAnnualClass->group_id == null ? null : $courseAnnualClass->group_id;
        if ($newSlot->time_tp > 0) {
            $newSlot->time_used = $newSlot->time_tp;
            $newSlot->time_remaining = $newSlot->time_tp;
        } else if ($newSlot->time_td > 0) {
            $newSlot->time_used = $newSlot->time_td;
            $newSlot->time_remaining = $newSlot->time_td;
        } else {
            $newSlot->time_used = $newSlot->time_course;
            $newSlot->time_remaining = $newSlot->time_course;
        }

        $newSlot->created_uid = $course_session->create_uid;
        $newSlot->write_uid = $course_session->write_uid;

        return $newSlot->save();
    }

    /**
     * Update course session and remove timetable slot.
     *
     * @param CourseSession $course_session
     * @return mixed
     */
    public function update_course_session(CourseSession $course_session)
    {
        $slots = Slot::where('course_session_id', $course_session->id);
        $timetable_slots = TimetableSlot::where('course_session_id', $course_session->id);
        if (count($slots->get())) {
            if ($slots->delete() && count($timetable_slots->get())) {
                if ($timetable_slots->delete()) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Conflict with timetable slot merged.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function is_merged(TimetableSlot $timetableSlot)
    {
        $mergeTimetableSlot = MergeTimetableSlot::where('timetable_slot_id', $timetableSlot->id)->first();
        if (count($mergeTimetableSlot) > 0) {
            $mergeTimetableSlots = MergeTimetableSlot::where('group_timetable_slot_id', $mergeTimetableSlot->group_timetable_slot_id)->get();
            if (count($mergeTimetableSlots) > 0) {
                return array(['status' => true, 'mergeTimetableSlots' => $mergeTimetableSlots]);
            }
        }
        return array(['status' => false, 'mergeTimetableSlots' => null]);
    }

    /**
     * Check timetable slot can merge.
     *
     * @param TimetableSlot $timetableSlot
     * @param TimetableSlot $mergedTimetableSlot
     * @return mixed
     */
    public function can_merge(TimetableSlot $timetableSlot, TimetableSlot $mergedTimetableSlot)
    {
        if (($mergedTimetableSlot->teacher_name == $timetableSlot->teacher_name) && ($mergedTimetableSlot->courseSession->course_annual_id == $timetableSlot->courseSession->course_annual_id) && ($timetableSlot->type == $mergedTimetableSlot->type) && (strtotime($timetableSlot->start) == strtotime($mergedTimetableSlot->start)) && (strtotime($timetableSlot->end) == strtotime($mergedTimetableSlot->end))) {
            return array(['status' => true, 'conflict_with' => $mergedTimetableSlot, 'merge' => true]);
        }
        return array(['status' => false, 'conflict_with' => $mergedTimetableSlot, 'merge' => false]);
    }

    /**
     * Create merge timetable slot.
     *
     * @param CreateTimetableSlotRequest $request
     * @return mixed
     */
    public function create_merge_timetable_slot(CreateTimetableSlotRequest $request)
    {
        if (isset($request->start) || isset($request->end)) {
            $newMergeTimetableSlot = new MergeTimetableSlot();
            $newMergeTimetableSlot->start = (new Carbon($request->start))->setTimezone('Asia/Phnom_Penh');
            $newMergeTimetableSlot->end = (new Carbon($request->end == null ? $request->start : $request->end))->setTimezone('Asia/Phnom_Penh');
            if ($newMergeTimetableSlot->save()) {
                return $newMergeTimetableSlot;
            }
        }
        return false;
    }

    /**
     * Update merge timetable slot on start and end field.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function update_merge_timetable_slot(TimetableSlot $timetableSlot)
    {
        // find another timetable slot related with
        $timetableSlots = TimetableSlot::where('group_merge_id', $timetableSlot->group_merge_id)->get();

        if (count($timetableSlots) > 0) {
            if (count($timetableSlots) == 1) {
                $mergeTimetableSlot = MergeTimetableSlot::find($timetableSlot->group_merge_id);
                // just update start and end field.
                if ($mergeTimetableSlot instanceof MergeTimetableSlot) {
                    $mergeTimetableSlot->start = $timetableSlot->start;
                    $mergeTimetableSlot->end = $timetableSlot->end;
                    $mergeTimetableSlot->updated_at = Carbon::now();
                    $mergeTimetableSlot->update();
                    return $mergeTimetableSlot;
                }
            } else {
                // create a new group merge id and update timetable slot.
                $newMergeTimetableSlot = new MergeTimetableSlot();
                $newMergeTimetableSlot->start = $timetableSlot->start;
                $newMergeTimetableSlot->end = $timetableSlot->end;
                if ($newMergeTimetableSlot->save()) {
                    $timetableSlot->group_merge_id = $newMergeTimetableSlot->id;
                    $timetableSlot->update();
                    return $newMergeTimetableSlot;
                }
            }
        }

        return false;
    }

    /**
     * Check conflict lecturer.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function check_conflict_lecturer(TimetableSlot $timetableSlot)
    {
        // find timetable match with argument
        $timetable = $timetableSlot->timetable;

        // find all timetables match with argument
        $timetables = Timetable::where([
            ['academic_year_id', $timetable->academic_year_id],
            ['week_id', $timetable->week_id]
        ])
            ->where('id', '!=', $timetable->id)
            ->get();

        // declare and prepare data
        $result = array();
        $canMerge = array();
        $canNotMerge = array();

        if (count($timetables) > 0) {
            foreach ($timetables as $itemTimetable) {
                if (count($itemTimetable->timetableSlots) > 0) {
                    foreach ($itemTimetable->timetableSlots as $itemTimetableSlot) {
                        if (
                            (((strtotime($timetableSlot->start) > strtotime($itemTimetableSlot->start)) && (strtotime($timetableSlot->start) < strtotime($itemTimetableSlot->end)))
                                ||
                                ((strtotime($timetableSlot->end) > strtotime($itemTimetableSlot->start)) && (strtotime($timetableSlot->end) < strtotime($itemTimetableSlot->end)))
                                ||
                                (strtotime($timetableSlot->start) == strtotime($itemTimetableSlot->start)) && (strtotime($timetableSlot->end) == strtotime($itemTimetableSlot->end)))
                            &&
                            ($timetableSlot->id != $itemTimetableSlot->id)
                            &&
                            ($timetableSlot->teacher_name == $itemTimetableSlot->teacher_name)
                            &&
                            ($timetableSlot->group_merge_id != $itemTimetableSlot->group_merge_id)
                        ) {

                            $currentCourse = Course::find($timetableSlot->slot->course_program_id);
                            $otherCourse = Course::find($itemTimetableSlot->slot->course_program_id);

                            if ((strtotime($timetableSlot->start) == strtotime($itemTimetableSlot->start)) && (strtotime($timetableSlot->end) == strtotime($itemTimetableSlot->end)) && ($timetableSlot->type == $itemTimetableSlot->type) && ($otherCourse->id == $currentCourse->id)) {
                                array_push($canMerge, $itemTimetableSlot);
                            } else {
                                array_push($canNotMerge, $itemTimetableSlot);
                            }
                        }
                    }
                }
            }
        }

        // push those two array into result
        $result['canMerge'] = $canMerge;
        $result['canNotMerge'] = $canNotMerge;

        // check array result, its has many item.
        (count($result['canMerge']) > 0 || count($result['canNotMerge']) > 0) ? $result['status'] = true : $result['status'] = false;

        return $result;
    }

    /**
     * Update Timetable Slot when merge.
     *
     * @param TimetableSlot $timetableSlot
     * @param integer $group_merge_id
     * @return mixed
     */
    public function update_timetable_slot_when_merge(TimetableSlot $timetableSlot, $group_merge_id)
    {
        $timetableSlot->group_merge_id = $group_merge_id;
        $timetableSlot->updated_at = Carbon::now();
        $timetableSlot->update();
    }

    /**
     * Find room existed merge timetable slot.
     *
     * @param $group_merge_id
     * @return mixed
     */
    public function find_room_existed_merge_timetable_slot($group_merge_id)
    {
        $group_id = null;
        // find all timetable slots
        $timetableSlots = TimetableSlot::where('group_merge_id', $group_merge_id)->get();
        if (count($timetableSlots) > 1) {
            foreach ($timetableSlots as $timetableSlot) {
                if ($timetableSlot->room_id != null) {
                    $group_id = $timetableSlot->room_id;
                    break;
                }
            }
        }
        return $group_id;
    }

    /**
     * Define timetable slot hos conflict room.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function is_conflict_room(TimetableSlot $timetableSlot)
    {
        if (isset($timetableSlot->room_id)) {
            $timetable = $timetableSlot->timetable;
            $timetables = Timetable::where([
                ['academic_year_id', $timetable->academic_year_id],
                ['week_id', $timetable->week_id]
            ])
                ->where('id', '!=', $timetable->id)
                ->get();
            if (count($timetables) > 0) {
                foreach ($timetables as $itemTimetable) {
                    if (count($itemTimetable->timetableSlots) > 0) {
                        foreach ($itemTimetable->timetableSlots as $itemTimetableSlot) {
                            if ((((strtotime($itemTimetableSlot->start) <= strtotime($timetableSlot->start)) && (strtotime($itemTimetableSlot->end) > strtotime($timetableSlot->start))) ||
                                    ((strtotime($timetableSlot->end) > strtotime($itemTimetableSlot->start)) && (strtotime($timetableSlot->end) < strtotime($itemTimetableSlot->end)))) && ($itemTimetableSlot->room_id == $timetableSlot->room_id)
                                && ($timetableSlot->group_merge_id != $itemTimetableSlot->group_merge_id)
                            ) {
                                return array(['status' => true, 'conflict_with' => $itemTimetableSlot]);
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * Sort groups.
     *
     * @param array $groups
     * @param $field
     * @return mixed
     */
    public function sort_group($groups = array(), $field)
    {
        usort($groups, function ($a, $b) {
            if (is_numeric($a->code)) {
                return $a->code - $b->code;
            } else {
                return strcmp($a->code, $b->code);
            }
        });
    }

    /**
     * Clone timetable.
     *
     * @param Timetable $timetable
     * @param array $groups
     * @param array $weeks
     * @return mixed
     */
    public function clone_timetable(Timetable $timetable, $groups = [], $weeks = [])
    {
        $result = false;
        if (count($groups) > 0 && count($weeks) > 0) {
            foreach ($groups as $group) {
                foreach ($weeks as $week) {
                    $result = $this->is_cloning($timetable, $group, $week, $result);
                }
            }
        } else {
            foreach ($weeks as $week) {
                $result = $this->is_cloning($timetable, null, $week, $result);
            }
        }
        return $result;
    }

    /**
     * Copied timetable.
     *
     * @param Timetable $timetable
     * @param null $group
     * @param $week
     * @return mixed
     */
    public function copied_timetable(Timetable $timetable, $group = null, $week)
    {
        $newTimetable = new Timetable();
        $newTimetable->academic_year_id = $timetable->academic_year_id;
        $newTimetable->department_id = $timetable->department_id;
        $newTimetable->degree_id = $timetable->degree_id;
        $newTimetable->option_id = $timetable->option_id;
        $newTimetable->grade_id = $timetable->grade_id;
        $newTimetable->semester_id = $timetable->semester_id;
        $newTimetable->week_id = $week;
        $newTimetable->group_id = $group;
        $newTimetable->created_uid = auth()->user()->id;
        $newTimetable->updated_uid = auth()->user()->id;
        $newTimetable->save();

        return $newTimetable;
    }

    /**
     * Copied timetable slot.
     *
     * @param Slot $slot
     * @param Timetable $timetable
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function copied_timetable_slot(Slot $slot, Timetable $timetable, TimetableSlot $timetableSlot)
    {
        $newMergeTimetableSlot = new MergeTimetableSlot();
        $newMergeTimetableSlot->start = $timetableSlot->start;
        $newMergeTimetableSlot->end = $timetableSlot->end;
        if ($newMergeTimetableSlot->save()) {
            $newTimetableSlot = new TimetableSlot();
            $newTimetableSlot->timetable_id = $timetable->id;
            $newTimetableSlot->course_session_id = $slot->course_session_id;
            $newTimetableSlot->slot_id = $slot->id;
            $newTimetableSlot->room_id = $timetableSlot->room_id;
            $newTimetableSlot->group_merge_id = $newMergeTimetableSlot->id;
            $newTimetableSlot->course_name = $timetableSlot->course_name;
            $newTimetableSlot->teacher_name = $timetableSlot->teacher_name;
            $newTimetableSlot->type = $timetableSlot->type;

            $newTimetableSlot->durations = $timetableSlot->durations;
            $slot->time_remaining = $slot->time_remaining - $timetableSlot->durations;
            $slot->update();

            $newTimetableSlot->start = $timetableSlot->start;
            $newTimetableSlot->end = $timetableSlot->end;
            $newTimetableSlot->created_uid = auth()->user()->id;
            $newTimetableSlot->updated_uid = auth()->user()->id;
            $newTimetableSlot->save();
            return true;
        }
        return false;
    }

    /**
     * Find timetable by references the other timetable, group and week
     * @param Timetable $timetable
     * @param null $group
     * @param $week
     * @return mixed
     */
    public function find_timetable_by_reference_group_and_week(Timetable $timetable, $group = null, $week)
    {
        return Timetable::where([
            ['academic_year_id', $timetable->academic_year_id],
            ['department_id', $timetable->department_id],
            ['degree_id', $timetable->degree_id],
            ['option_id', $timetable->option_id],
            ['grade_id', $timetable->grade_id],
            ['semester_id', $timetable->semester_id],
            ['group_id', $group],
            ['week_id', $week]
        ])->first();
    }

    /**
     * Find slot by timetable slot.
     *
     * @param TimetableSlot $timetableSlot
     * @param null $group
     * @return mixed
     */
    public function find_slot_by_reference_timetable_slot(TimetableSlot $timetableSlot, $group = null)
    {
        $slotTimetableSlot = Slot::find($timetableSlot->slot_id);
        return Slot::where([
            ['time_tp', $slotTimetableSlot->time_tp],
            ['time_td', $slotTimetableSlot->time_td],
            ['time_course', $slotTimetableSlot->time_course],
            ['course_annual_id', $slotTimetableSlot->course_annual_id],
            ['course_session_id', $slotTimetableSlot->course_session_id],
            ['group_id', $group],
            ['lecturer_id', $slotTimetableSlot->lecturer_id]
        ])->first();
    }

    /**
     * find timetable, if itself.
     *
     * @param Timetable $timetable
     * @param null $group
     * @param $week
     * @return mixed
     */
    public function is_timetable_self(Timetable $timetable, $group = null, $week)
    {
        return Timetable::where([
            ['id', $timetable->id],
            ['academic_year_id', $timetable->academic_year_id],
            ['department_id', $timetable->department_id],
            ['degree_id', $timetable->degree_id],
            ['option_id', $timetable->option_id],
            ['grade_id', $timetable->grade_id],
            ['semester_id', $timetable->semester_id],
            ['group_id', $group],
            ['group_id', $group == null ? null : $timetable->group_id],
            ['week_id', $week]
        ])->first();
    }

    /**
     * Start cloning.
     *
     * @param Timetable $timetable
     * @param null $group
     * @param $week
     * @param $result
     * @return mixed
     */
    public function is_cloning(Timetable $timetable, $group = null, $week, $result)
    {
        $itself_timetable = $this->is_timetable_self($timetable, $group, $week);
        if ($itself_timetable instanceof Timetable) {
            $result = true;
        } else {
            // find timetable before create.
            // if existed, no create and then delete all TimetableSlot
            $findTimetable = $this->find_timetable_by_reference_group_and_week($timetable, $group, $week);
            if ($findTimetable instanceof Timetable) {
                foreach ($findTimetable->timetableSlots as $timetableSlot) {
                    // by timetable slot can access and update slot
                    $slot = Slot::find($timetableSlot->slot_id);
                    $slot->time_remaining = $slot->time_remaining + $timetableSlot->durations;
                    $slot->update();
                    // and then delete timetable slot
                    $timetableSlot->delete();
                }
                // add new timetable slots
                foreach ($timetable->timetableSlots as $timetableSlot) {
                    // find slot match with timetableSlot
                    $slot = $this->find_slot_by_reference_timetable_slot($timetableSlot, $group);
                    // loop all timetable slot.
                    // create merge group id before copy timetable slot.
                    if (($slot instanceof Slot) && ($slot->time_remaining >= $timetableSlot->durations)) {
                        $result = $this->copied_timetable_slot($slot, $findTimetable, $timetableSlot);
                    }
                }

            } else {
                // create timetable
                $newTimetable = $this->copied_timetable($timetable, $group, $week);
                if ($newTimetable instanceof Timetable) {
                    foreach ($timetable->timetableSlots as $timetableSlot) {
                        $slot = $this->find_slot_by_reference_timetable_slot($timetableSlot, $group);
                        // loop all timetable slot.
                        // create merge group id before copy timetable slot.
                        if (($slot instanceof Slot) && ($slot->time_remaining >= $timetableSlot->durations)) {
                            $result = $this->copied_timetable_slot($slot, $newTimetable, $timetableSlot);
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Cron job set permission.
     *
     * @return mixed
     */
    public function set_permission_create_timetable()
    {
        Log::info('Cron Set Schedule Assignment Timetable was executed.');

        $now = Carbon::now('Asia/Phnom_Penh');
        $departments = Configuration::where('key', 'like', 'timetable_%')->get();
        foreach ($departments as $department) {

            if ((strtotime($now) >= strtotime($department->created_at)) && (strtotime($now) <= strtotime($department->updated_at))) {
                $department->description = 'true';
                $department->timestamps = false;
                $department->update();
            } elseif (strtotime($now) > strtotime($department->updated_at)) {
                $department->description = 'finished';
                $department->timestamps = false;
                $department->update();
            } else {
                $department->description = 'false';
                $department->timestamps = false;
                $department->update();
            }
        }
    }

    /**
     * Get timetable slot with details info.
     *
     * @param Timetable $timetable
     * @param null $by_teacher
     * @return mixed
     */
    public function get_timetable_slot_details(Timetable $timetable, $by_teacher = null)
    {
        $timetable_slots = TimetableSlot::where('timetable_id', $timetable->id)
            ->leftJoin('rooms', 'rooms.id', '=', 'timetable_slots.room_id')
            ->leftJoin('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->where(function ($query) use ($by_teacher) {
                if ($by_teacher != null) {
                    $query->where('timetable_slots.teacher_name', '=', $by_teacher);
                }
            })
            ->select(
                'timetable_slots.id',
                'timetable_slots.course_name as title',
                'timetable_slots.course_name',
                'timetable_slots.teacher_name',
                'timetable_slots.type as course_type',
                'timetable_slots.start',
                'timetable_slots.end',
                'buildings.code as building',
                'rooms.name as room'
            )->get();

        return $timetable_slots;
    }

    /**
     * Get Timetable Slot with Conflict Info.
     *
     * @param Timetable $timetable
     * @param Collection $timetableSlots
     * @param null $by_teacher
     * @return mixed
     */
    public function get_timetable_slot_with_conflict_info(Timetable $timetable, Collection $timetableSlots, $by_teacher = null)
    {
        $timetable_slots = $this->get_timetable_slot_details($timetable, $by_teacher);

        // dd($timetable_slots);

        foreach ($timetable_slots as $timetable_slot) {
            if (($timetable_slot instanceof TimetableSlot) && is_object($timetable_slot)) {
                // convert from array object to collection object.
                $itemTimetableSlot = TimetableSlot::find($timetable_slot->id);
                $timetableSlot = new Collection($itemTimetableSlot);

                // find and prepare groups to render when timetable slot merge together
                $groups = array();
                // find all timetable slot has group_merge_id the same
                $timetableSlotHasTheSameGroupMergeId = TimetableSlot::where('group_merge_id', $itemTimetableSlot->group_merge_id)->get();
                if (count($timetableSlotHasTheSameGroupMergeId) > 0) {
                    foreach ($timetableSlotHasTheSameGroupMergeId as $item) {
                        array_push($groups, Group::find($item->slot->group_id));
                    }
                } else {
                    $groups = [];
                }
                // check conflict lecturer.
                // dd('beforeCheck');
                $dataLecturer = $this->check_conflict_lecturer($itemTimetableSlot);

                // check conflict room.
                if ($this->is_conflict_room($itemTimetableSlot)[0]['status'] == true) {
                    $timetableSlot->put('conflict_room', true);
                } else {
                    $timetableSlot->put('conflict_room', false);
                }


                // push data to item timetable slot
                $timetableSlot->put('conflict_lecturer', $dataLecturer);
                $timetableSlot->put('building', $timetable_slot->building);
                // sort group before push
                usort($groups, function ($a, $b) {
                    if (is_numeric($a->code)) {
                        return $a->code - $b->code;
                    } else {
                        return strcmp($a->code, $b->code);
                    }
                });
                // push groups array into item timetable slot
                $timetableSlot->put('groups', $groups);
                $timetableSlot->put('room', $timetable_slot->room);

                // push timetable slot to output
                $timetableSlots->push($timetableSlot);
            }
        }
        return $timetableSlots;
    }

    /**
     * Get all student annuals.
     *
     * @param Timetable $timetable
     * @return mixed
     */
    public function find_student_annual_ids(Timetable $timetable)
    {
        $group = $timetable->group_id == null ? null : $timetable->group_id;
        return DB::table('group_student_annuals')
            ->leftJoin('studentAnnuals', 'studentAnnuals.id', '=', 'group_student_annuals.student_annual_id')
            ->where([
                ['studentAnnuals.academic_year_id', $timetable->academic_year_id],
                ['studentAnnuals.department_id', $timetable->department_id],
                ['studentAnnuals.degree_id', $timetable->degree_id],
                ['studentAnnuals.grade_id', $timetable->grade_id],
                ['studentAnnuals.department_option_id', $timetable->option_id == null ? null : $timetable->option_id]
            ])
            ->where(function ($query) use ($group) {
                if ($group != null) {
                    $query->where('group_student_annuals.group_id', $group);
                }
            })
            ->orderBy('studentAnnuals.id')
            ->distinct('studentAnnuals.id')
            ->lists('studentAnnuals.id');
    }

    /**
     * Get group student annual from language.
     *
     * @param $department_id
     * @param array $student_annual_ids
     * @param Timetable $timetable
     * @return mixed
     */
    public function get_group_student_annual_form_language($department_id, array $student_annual_ids, Timetable $timetable)
    {
        $groups = array();
        $groupStudentsLanguage = DB::table('group_student_annuals')
            ->whereIn('student_annual_id', $student_annual_ids)
            ->where([
                ['department_id', $department_id],
                ['semester_id', $timetable->semester_id]
            ])
            ->orderBy('group_id')
            ->distinct('group_id')
            ->lists('group_id');
        foreach ($groupStudentsLanguage as $item) {
            $group = Group::find($item);
            if ($group instanceof Group) {
                array_push($groups, $group);
            }
        }
        return array($groupStudentsLanguage, $groups);
    }

    /**
     * Get timetable slot from dept language.
     *
     * @param array $group_students
     * @param Timetable $timetable
     * @param $department_id
     * @return mixed
     */
    public function get_timetables_form_language_by_student_annual(array $group_students, Timetable $timetable, $department_id)
    {
        //dd($group_students);
        $timetables = new Collection();
        foreach ($group_students as $group_student) {
            $getTimetable = Timetable::where([
                ['academic_year_id', $timetable->academic_year_id],
                ['department_id', $department_id],
                ['degree_id', $timetable->degree_id],
                ['option_id', $timetable->option_id == null ? null : $timetable->option_id],
                ['grade_id', $timetable->grade_id],
                ['semester_id', $timetable->semester_id],
                ['week_id', $timetable->week_id],
                ['group_id', $group_student],
                ['completed', true]
            ])->first();

            if ($getTimetable instanceof Timetable) {
                $timetables->push($getTimetable);
            }
        }

        return $timetables;
    }

    /**
     * Get timetable slots from language dept.
     *
     * @param Collection $timetables
     * @param array $groupStudentsLanguage
     * @return mixed
     */
    public function get_timetable_slot_language_dept(Collection $timetables, array $groupStudentsLanguage)
    {
        $timetableSlots = new Collection();
        $groupAndRoom = new Collection();
        $tmp = new Collection();
        //$groupsHasCreatedTimetable = array();

        foreach ($timetables as $timetable) {
            $getTimetableSlotsLanguage = $this->get_timetable_slot_details($timetable);
            if (count($getTimetableSlotsLanguage) > 0) {
                foreach ($getTimetableSlotsLanguage as $item) {
                    $newGroupAndRoom = array();

                    $newGroupAndRoom['room'] = $item->room;
                    $newGroupAndRoom['building'] = $item->building;
                    $newGroupAndRoom['group'] = Group::find($timetable->group_id)->code;

                    //array_push($groupsHasCreatedTimetable, $timetable->group_id);
                    $tmp->push($newGroupAndRoom);
                    $timetableSlots->push($item);
                }
            }
        }

        /*$groupsHasNoCreatedTimetable = collect($groupStudentsLanguage)->diff($groupsHasCreatedTimetable);

        // set those properties to above groups.
        foreach ($groupsHasNoCreatedTimetable as $item){
            $newGroupAndRoom = array();
            $newGroupAndRoom['room'] = null;
            $newGroupAndRoom['building'] = null;
            $newGroupAndRoom['group'] = Group::find($item)->code;

            $tmp->push($newGroupAndRoom);
        }*/

        foreach (collect($tmp)->sortBy('group') as $item) {
            $groupAndRoom->push($item);
        }

        // find group language has not create timetable yet.


        return array(collect($timetableSlots)->keyBy('start'), $groupAndRoom);
    }

    /**
     * Set language timetable slot into TimetableSlots.
     *
     * @param Collection $timetableSlots
     * @param Collection $groupsRoom
     * @param Collection $languageTimetableSlots
     * @return mixed
     */
    public function set_timetable_slot_language(Collection $timetableSlots, Collection $groupsRoom, Collection $languageTimetableSlots)
    {
        // pass timetable slots.
        foreach ($languageTimetableSlots as $item) {
            $item->teacher_name = '';
            $timetableSlot = new Collection($item->toArray());
            $timetableSlot->put('slotsForLanguage', $groupsRoom);
            $timetableSlots->push($timetableSlot);
        }
        return $timetableSlots;
    }

    /**
     * Get group student annual from language.
     *
     * @param $department_id
     * @param array $student_annual_ids
     * @param Timetable $timetable
     * @return mixed
     */
    public function find_group_student_annual_form_language($department_id, array $student_annual_ids, Timetable $timetable)
    {
        $groups = array();
        $groupStudentsLanguage = DB::table('group_student_annuals')
            ->whereIn('student_annual_id', $student_annual_ids)
            ->where([
                ['department_id', $department_id],
                ['semester_id', $timetable->semester_id]
            ])
            ->orderBy('group_id')
            ->distinct('group_id')
            ->lists('group_id');
        foreach ($groupStudentsLanguage as $item) {
            $group = Group::find($item);
            if ($group instanceof Group) {
                array_push($groups, $group);
            }
        }
        return array($groupStudentsLanguage, $groups);
    }
}