<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Group;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\Week;

/**
 * Class PrintTimetableController
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait PrintTimetableController
{
    /**
     * Get print timetable page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function print_timetable($id)
    {
        // find timetable
        $timetable = Timetable::find($id);
        // find all timetable slot by academic year and department id.
        // in order to get weeks.
        $allWeeks = Timetable::where([
            ['academic_year_id', $timetable->academic_year_id],
            ['department_id', $timetable->department_id],
            ['semester_id', $timetable->semester_id]
        ])
            ->select('week_id')
            ->groupBy('week_id')
            ->get();

        $allGroups = Timetable::where([
            ['academic_year_id', $timetable->academic_year_id],
            ['department_id', $timetable->department_id],
            ['semester_id', $timetable->semester_id]
        ])
            ->select('group_id')
            ->groupBy('group_id')
            ->get();

        $weeks = array();
        $groups = array();

        if (count($allWeeks) > 0) {
            foreach ($allWeeks as $week) {
                array_push($weeks, Week::find($week->week_id));

            }
        }

        if (count($allGroups) > 0) {
            foreach ($allGroups as $group) {
                if ($group->group_id != null) {
                    array_push($groups, Group::find($group->group_id));
                }
            }
        }

        usort($weeks, function ($a, $b) {
            return $a->id - $b->id;
        });

        usort($groups, function ($a, $b) {
            if (is_numeric($a->code)) {
                return $a->code - $b->code;
            } else {
                return strcmp($a->code, $b->code);
            }
        });

        return view('backend.schedule.timetables.popup-print', compact('weeks', 'groups', 'timetable'));
    }


    public function get_template_print()
    {
        return view('backend.schedule.timetables.popup-template-print');
    }
}