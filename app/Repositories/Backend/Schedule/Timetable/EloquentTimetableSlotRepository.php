<?php

namespace App\Repositories\Backend\Schedule\Timetable;

use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Models\CourseSession;
use App\Models\Room;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
use Carbon\Carbon;

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
        $courseSession = CourseSession::find($request->course_session_id);
        if ($courseSession instanceof CourseSession) {
            $duration = $this->durations(new Carbon($request->start), new Carbon($request->end == null ? $request->start : $request->end));
            if ($courseSession->time_remaining > 0 && $courseSession->time_remaining >= $duration) {
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
                    $courseSession->time_remaining = $courseSession->time_remaining - $duration;
                    $courseSession->update();
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
                            if (($itemTimetableSlot->start == $timetableSlot->start) && ($itemTimetableSlot->room_id == $room->id)) {
                                return true;
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
                        if (($itemTimetableSlot->start == $timetableSlot->start) && ($itemTimetableSlot->courseSession->course_annual_id == $timetableSlot->courseSession->course_annual_id)) {
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
                        if (($itemTimetableSlot->start == $timetableSlot->start) && ($itemTimetableSlot->teacher_name == $timetableSlot->teacher_name)) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }
}