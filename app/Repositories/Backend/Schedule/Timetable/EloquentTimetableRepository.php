<?php

namespace App\Repositories\Backend\Schedule\Timetable;

use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Models\Schedule\Timetable\Timetable;

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
            ['academic_year_id', $request->academic_year_id],
            ['department_id', $request->department_id],
            ['degree_id', $request->degree_id],
            ['grade_id', $request->grade_id],
            ['option_id', $request->option_id],
            ['semester_id', $request->semester_id],
            ['week_id', $request->week_id],
            ['group_id', $request->group_id]
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

        $newTimetable->academic_year_id = $request->academic_year_id;
        $newTimetable->department_id = $request->department_id;
        $newTimetable->degree_id = $request->degree_id;
        $newTimetable->grade_id = $request->grade_id;
        $request->option_id == null ? null : $newTimetable->option_id = $request->option_id;
        $newTimetable->semester_id = $request->semester_id;
        $newTimetable->week_id = $request->week_id;
        $newTimetable->group_id = $request->group_id;
        $newTimetable->created_uid = auth()->user()->id;
        $newTimetable->updated_uid = auth()->user()->id;

        if ($newTimetable->save()) {
            return $newTimetable;
        }
        return false;
    }
}