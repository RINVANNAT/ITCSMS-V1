<?php

namespace App\Repositories\Backend\Schedule\Timetable;

use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Models\CourseAnnualClass;
use App\Models\CourseSession;
use App\Models\Group;
use App\Models\Room;
use App\Models\Schedule\Timetable\MergeTimetableSlot;
use App\Models\Schedule\Timetable\Slot;
use App\Models\Schedule\Timetable\SlotClass;
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
        $slot = Slot::find($request->slot_id);
        if ($slot instanceof Slot) {
            $duration = $this->durations(new Carbon($request->start), new Carbon($request->end == null ? $request->start : $request->end));
            if ($slot->time_remaining > 0 && $slot->time_remaining >= $duration) {
                $newTimetableSlot = new TimetableSlot();

                $newTimetableSlot->timetable_id = $timetable->id;
                $newTimetableSlot->course_session_id = $request->course_session_id;
                $request->room_id == null ?: $newTimetableSlot->room_id = $request->room_id;
                $newTimetableSlot->course_name = $request->course_name;
                $newTimetableSlot->teacher_name = $request->teacher_name;
                $newTimetableSlot->type = $request->course_type;
                $newTimetableSlot->start = new Carbon($request->start);
                $newTimetableSlot->end = new Carbon($request->end == null ? $request->start : $request->end);
                $newTimetableSlot->durations = $duration;
                $newTimetableSlot->created_uid = auth()->user()->id;
                $newTimetableSlot->updated_uid = auth()->user()->id;

                if ($newTimetableSlot->save()) {
                    $slot->time_remaining = $slot->time_remaining - $duration;
                    $slot->update();
                    return $newTimetableSlot;
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
     * Set value into remaining column course_session.
     *
     * @return mixed
     */
    public function set_value_into_time_remaining_course_session()
    {
        $course_sessions = CourseSession::whereNull('time_remaining')->get();

        if (count($course_sessions) > 0) {
            foreach ($course_sessions as $course_session) {
                if ($course_session->time_tp > 0) {
                    $course_session->time_remaining = $course_session->time_tp;
                } else if ($course_session->time_td > 0) {
                    $course_session->time_remaining = $course_session->time_td;
                } else {
                    $course_session->time_remaining = $course_session->time_course;
                }
                $course_session->time_used = $course_session->time_remaining;
                $course_session->update();
            }
            return true;
        }
        return false;
    }

    /**
     * Define timetable slot hos conflict room.
     *
     * @param TimetableSlot $timetableSlot
     * @param Room $room
     * @return mixed
     */
    public function is_conflict_room(TimetableSlot $timetableSlot, Room $room = null)
    {
        if ($room != null) {
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
                                    ((strtotime($timetableSlot->end) > strtotime($itemTimetableSlot->start)) && (strtotime($timetableSlot->end) < strtotime($itemTimetableSlot->end)))) && ($itemTimetableSlot->room_id == $room->id)
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
     * Define timetable slot has conflict course.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function is_conflict_course(TimetableSlot $timetableSlot)
    {
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
                        if ((strtotime($itemTimetableSlot->start) <= strtotime($timetableSlot->start)) && (strtotime($itemTimetableSlot->end) > strtotime($timetableSlot->start)) && ($itemTimetableSlot->courseSession->course_annual_id == $timetableSlot->courseSession->course_annual_id)) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * Define timetable slot has conflict lecturer.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function is_conflict_lecturer(TimetableSlot $timetableSlot)
    {
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
                                ((strtotime($timetableSlot->end) > strtotime($itemTimetableSlot->start)) && (strtotime($timetableSlot->end) < strtotime($itemTimetableSlot->end))))
                            && ($itemTimetableSlot->teacher_name == $timetableSlot->teacher_name)
                        ) {
                            if (($itemTimetableSlot->teacher_name == $timetableSlot->teacher_name) && ($itemTimetableSlot->courseSession->course_annual_id == $timetableSlot->courseSession->course_annual_id) && ($timetableSlot->type == $itemTimetableSlot->type) && (strtotime($timetableSlot->start) == strtotime($itemTimetableSlot->start)) && (strtotime($timetableSlot->end) == strtotime($itemTimetableSlot->end))) {
                                return array(['status' => true, 'conflict_with' => $itemTimetableSlot, 'merge' => true]);
                            }
                            return array(['status' => true, 'conflict_with' => $itemTimetableSlot, 'merge' => false]);
                        }
                    }
                }
            }
        }
        return false;
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
     * Check is already merged.
     *
     * @param TimetableSlot $timetableSlot
     * @return mixed
     */
    public function is_merged(TimetableSlot $timetableSlot)
    {
        if (MergeTimetableSlot::where('timetable_slot_id', $timetableSlot->id)->first() instanceof MergeTimetableSlot) {
            return true;
        }
        return false;
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
                    $newSlot = $this->export_slot($course_session);
                    if ($newSlot instanceof Slot) {
                        $this->export_slot_class($course_annual_class, $newSlot);
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

        // Check course session was delete.
        /*$slots = Slot::all();
        if (count($slots)) {
            foreach ($slots as $slot) {
                if (CourseSession::find($slot->course_session_id) instanceof CourseSession) {
                    continue;
                } else {
                    $slot->delete();
                    $timetable_slots = TimetableSlot::where('course_session_id', $slot->course_session_id)->get();
                    if (count($timetable_slots) > 0) {
                        foreach ($timetable_slots as $timetable_slot) {
                            TimetableSlot::find($timetable_slot->id)->delete();
                        }
                    }
                }
            }
        }*/

        if ($flag) {
            return true;
        }
        return false;
    }

    /**
     * Export from course_session to slots.
     *
     * @param CourseSession $course_session
     * @return mixed
     * @internal param CourseSession $courseSession
     */
    public function export_slot(CourseSession $course_session)
    {
        $newSlot = new Slot();
        $newSlot->time_tp = $course_session->time_tp;
        $newSlot->time_td = $course_session->time_td;
        $newSlot->time_course = $course_session->time_course;
        $newSlot->course_annual_id = $course_session->course_annual_id;
        $newSlot->course_session_id = $course_session->id;
        $newSlot->lecturer_id = $course_session->lecturer_id;
        $newSlot->responsible_department_id = $course_session->responsible_department_id;
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

        if ($newSlot->save()) {
            return $newSlot;
        }
        return false;
    }

    /**
     * Export from course_annual_classes to slot_classes.
     *
     * @param CourseAnnualClass $course_annual_class
     * @param Slot $newSlot
     * @return mixed
     */
    public function export_slot_class(CourseAnnualClass $course_annual_class, Slot $newSlot)
    {
        $newSlotClass = new SlotClass();
        $newSlotClass->group = $course_annual_class->group;
        $newSlotClass->degree_id = $course_annual_class->degree_id;
        $newSlotClass->course_annual_id = $course_annual_class->course_annaul_id;
        $newSlotClass->grade_id = $course_annual_class->grade_id;
        $newSlotClass->department_id = $course_annual_class->department_id;
        $newSlotClass->created_uid = auth()->user()->id;
        $newSlotClass->write_uid = $newSlotClass->created_uid;
        $newSlotClass->department_option_id = $course_annual_class->department_option_id;
        $newSlotClass->slot_id = $newSlot->id;
        $newSlotClass->group_id = $course_annual_class->group_id;
        return $newSlotClass->save();
    }

    /**
     * Update course session and remove timetable slot.
     *
     * @param CourseSession $course_session
     * @return mixed
     */
    public function update_course_session(CourseSession $course_session)
    {
        $slots = Slot::where('course_session_id', $course_session->id)->get();
        foreach ($slots as $slot) {
            $timetable_slots = TimetableSlot::where('course_session_id', $slot->course_session_id)->get();
            if (count($timetable_slots) > 0) {
                foreach ($timetable_slots as $timetable_slot) {
                    $timetable_slot->delete();
                }
            }
            if ($this->change_group_on_course_session($course_session, $slot)) {
                $oldSlots = Slot::where('course_session_id', $course_session->id);
                if (count($oldSlots->get()) > 0) {
                    $oldSlots->delete();
                }
                $course_annual_classes = CourseAnnualClass::where('course_annual_id', $course_session->course_annual_id)->get();
                if (count($course_annual_classes) > 0) {
                    foreach ($course_annual_classes as $course_annual_class) {
                        $newSlot = $this->export_slot($course_session);
                        if ($newSlot instanceof Slot) {
                            $this->export_slot_class($course_annual_class, $newSlot);
                        }
                    }
                }
            } else {
                $slot->time_tp = $course_session->time_tp;
                $slot->time_td = $course_session->time_td;
                $slot->time_course = $course_session->time_course;
                $slot->course_annual_id = $course_session->course_annual_id;
                $slot->lecturer_id = $course_session->lecturer_id == null ? null : $course_session->lecturer_id;
                $slot->responsible_department_id = $course_session->responsible_department_id;
                if ($slot->time_tp > 0) {
                    $slot->time_used = $slot->time_tp;
                    $slot->time_remaining = $slot->time_tp;
                } else if ($slot->time_td > 0) {
                    $slot->time_used = $slot->time_td;
                    $slot->time_remaining = $slot->time_td;
                } else {
                    $slot->time_used = $slot->time_course;
                    $slot->time_remaining = $slot->time_course;
                }
                $slot->created_uid = $course_session->create_uid;
                $slot->write_uid = $course_session->write_uid;
                $slot->update();
            }
        }

    }

    /**
     * Course session change group or not.
     *
     * @param CourseSession $course_session
     * @param Slot $slot
     * @return  mixed
     */
    public function change_group_on_course_session(CourseSession $course_session, Slot $slot)
    {
        $course_annual_classes = CourseAnnualClass::where('course_annual_id', $course_session->course_annual_id)->get();
        if (count($course_annual_classes) > 0) {
            $slot_classes = SlotClass::where('course_annual_id', $course_session->course_annual_id)->lists('group_id');
            if (count($slot_classes) > 0) {
                if (count($course_annual_classes) == count($slot_classes)) {
                    foreach ($course_annual_classes as $course_annual_class) {
                        if (in_array($course_annual_class->group_id, $slot_classes)) {
                            continue;
                        } else {
                            return true;
                        }
                    }
                } else {
                    return true;
                }
                return false;
            }
        }
    }
}