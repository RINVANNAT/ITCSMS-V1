<?php

namespace App\Repositories\Backend\Schedule\Timetable;

use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Models\Schedule\Timetable\Timetable;
use Carbon\Carbon;

/**
 * Class EloquentTimetableRepository
 * @package App\Repositories\Backend\Schedule\Timetable
 */
class EloquentTimetableRepository implements TimetableRepositoryContract
{

    /**
     * Filter course session with the attribute below.
     *
     * @param CreateTimetableRequest $request
     * @return Timetable|bool
     */
    public function find_timetable_is_existed(CreateTimetableRequest $request)
    {
        $timetable = Timetable::where([
            ['academic_year_id', $request->academicYear],
            ['department_id', $request->department],
            ['degree_id', $request->degree],
            ['grade_id', $request->grade],
            ['option_id', $request->option == null ? null : $request->option],
            ['semester_id', $request->semester],
            ['week_id', $request->weekly],
            ['group_id', $request->group == null ? null : $request->group]
        ])
            ->first();

        if ($timetable instanceof Timetable) {
            return $timetable;
        }
        return false;
    }

    /**
     * Create blank timetable table.
     *
     * @param CreateTimetableRequest $request
     * @return Timetable|bool
     */
    public function create_timetable(CreateTimetableRequest $request)
    {
        $newTimetable = new Timetable();

        $newTimetable->academic_year_id = $request->academicYear;
        $newTimetable->department_id = $request->department;
        $newTimetable->degree_id = $request->degree;
        $newTimetable->grade_id = $request->grade;
        $newTimetable->option_id = $request->option == null ? null : $request->option;
        $newTimetable->semester_id = $request->semester;
        $newTimetable->week_id = $request->weekly;
        $newTimetable->group_id = $request->group == null ? null : $request->group;
        $newTimetable->created_uid = auth()->user()->id;
        $newTimetable->updated_uid = auth()->user()->id;

        if ($newTimetable->save()) {
            return $newTimetable;
        }
        return false;
    }

    /**
     * To check timetable slot has half hour.
     *
     * @param Timetable $timetable
     * @return mixed
     */
    public function hasHalfHour(Timetable $timetable)
    {
        $timetableSlots = $timetable->timetableSlots;
        foreach ($timetableSlots as $timetableSlot) {
            $start = new Carbon($timetableSlot->start);
            $end = new Carbon($timetableSlot->end);
            if (($end->minute - $start->minute) == 30 || ($start->minute - $end->minute) == 30) {
                return true;
            }
        }
        return false;
    }
}