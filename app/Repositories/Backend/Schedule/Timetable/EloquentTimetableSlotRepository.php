<?php

namespace App\Repositories\Backend\Schedule\Timetable;

use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
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
     */
    public function create_timetable_slot(Timetable $timetable, CreateTimetableRequest $request)
    {

        // TODO: Implement create_timetable_slot() method.
        $newTimetableSlot = new TimetableSlot();

        $newTimetableSlot->timetable_id = $timetable->id;
        $newTimetableSlot->course_session_id = $request->course_session_id;
        $request->room_id == null ?: $newTimetableSlot->room_id = $request->room_id;
        $newTimetableSlot->course_name = $request->course_name;
        $newTimetableSlot->teacher_name = $request->teacher_name;
        $newTimetableSlot->type = $request->course_type;
        $newTimetableSlot->durations = 2; // @TODO change that point.
        $newTimetableSlot->start = new Carbon($request->start);
        $newTimetableSlot->end = new Carbon($request->end == null ? $request->start : $request->end);
        $newTimetableSlot->created_uid = auth()->user()->id;
        $newTimetableSlot->updated_uid = auth()->user()->id;

        if ($newTimetableSlot->save()) {
            return true;
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
}