<?php

namespace App\Repositories\Backend\Schedule\Timetable;

use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Models\CourseAnnualClass;
use App\Models\CourseSession;
use App\Models\Group;
use App\Models\Room;
use App\Models\Schedule\Timetable\MergeTimetableSlot;
use App\Models\Schedule\Timetable\Slot;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
     * @param CreateTimetableRequest $request
     * @return mixed
     * @internal param CourseSession $courseSession
     */
    public function create_timetable_slot(Timetable $timetable, CreateTimetableRequest $request)
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
     * Export course sessions.
     *
     * @return mixed
     */
    public function export_course_sessions()
    {
        $flag = true;
        $course_sessions = CourseSession::all();
        foreach ($course_sessions as $course_session) {
            if (count(Slot::where('course_session_id', $course_session->id)->get()) > 0) {
                continue;
            } else {
                $course_annual_classes = CourseAnnualClass::where('course_session_id', $course_session->id)->get();
                foreach ($course_annual_classes as $course_annual_class) {
                    if ($this->export_slot($course_session, $course_annual_class)) {
                        $flag = true;
                    } else {
                        $flag = false;
                    }
                }
                if ($flag == false) {
                    break;
                }
            }
        }
        return $flag;
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
     * @param CreateTimetableRequest $request
     * @return mixed
     */
    public function create_merge_timetable_slot(CreateTimetableRequest $request)
    {
        if (isset($request->start) || isset($request->end)) {
            $newMergeTimetableSlot = new MergeTimetableSlot();
            $newMergeTimetableSlot->start = new Carbon($request->start);
            $newMergeTimetableSlot->end = new Carbon($request->end == null ? $request->start : $request->end);
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
        // find merge timetable match with argument
        $mergeTimetableSlot = MergeTimetableSlot::find($timetableSlot->group_merge_id);
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
                        // find merge timetable slot and then compare with input and the other merge timetable slot
                        $itemMergeTimetableSlot = MergeTimetableSlot::find($itemTimetableSlot->group_merge_id);
                        // compare start and end date && teacher
                        if ((((strtotime($itemMergeTimetableSlot->start) <= strtotime($mergeTimetableSlot->start)) && (strtotime($itemMergeTimetableSlot->end) > strtotime($mergeTimetableSlot->start))) ||
                                ((strtotime($mergeTimetableSlot->end) > strtotime($itemMergeTimetableSlot->start)) && (strtotime($mergeTimetableSlot->end) < strtotime($itemMergeTimetableSlot->end))))
                            && ($itemTimetableSlot->teacher_name == $timetableSlot->teacher_name)
                            && ($timetableSlot->group_merge_id != $itemTimetableSlot->group_merge_id)
                        ) {
                            // find which timetable slot can merge
                            // by compare with start, end, course_annual_id and type of timetable slot
                            if ((strtotime($timetableSlot->start) == strtotime($itemTimetableSlot->start)) && (strtotime($timetableSlot->end) == strtotime($itemTimetableSlot->end)) && ($timetableSlot->type == $itemTimetableSlot->type) && ($timetableSlot->slot->course_annual_id == $itemTimetableSlot->slot->course_annual_id)) {
                                array_push($canMerge, $itemTimetableSlot);
                            } // and can not merge
                            else {
                                array_push($canNotMerge, $itemTimetableSlot);
                            }
                            // if conflict, add into result array.
                            // array_push($result, $itemTimetableSlot);
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
        return $timetableSlot->update();
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
}