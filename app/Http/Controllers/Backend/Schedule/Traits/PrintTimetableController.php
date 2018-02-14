<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Group;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\Week;
use App\Repositories\Backend\Schedule\Timetable\EloquentTimetableSlotRepository;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Support\Collection;

/**
 * Class PrintTimetableController
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait PrintTimetableController
{
    protected $timetableSlotRepos;

    public function __construct(EloquentTimetableSlotRepository $timetableSlotRepository)
    {
        $this->timetableSlotRepos = $timetableSlotRepository;
    }

    /**
     * @param EloquentTimetableSlotRepository $timetableSlotRepo
     */
    public function setTimetableSlotRepo($timetableSlotRepo)
    {
        $this->timetableSlotRepos = $timetableSlotRepo;
    }

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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get_template_print()
    {
        $groups = request('groups');
        $weeks = request('weeks');
        $baseTimetableId = request('timetable');

        // find timetable info related with $baseTimetableId
        $infoTimetable = Timetable::find($baseTimetableId);

        // declare set of timetables for return
        $timetables = new Collection();
        $timetablesSlotsLang = new Collection();

        if (count($groups) > 0) {
            foreach ($groups as $group) {
                foreach ($weeks as $week) {
                    $itemTimetable = $this->find_timetable($infoTimetable, $group, $week);
                    if ($itemTimetable instanceof Timetable) {
                        $timetables->push($itemTimetable);
                    }
                }
            }
        } else {
            foreach ($weeks as $week) {
                $itemTimetable = $this->find_timetable($infoTimetable, null, $week);
                if ($itemTimetable instanceof Timetable) {
                    $timetables->push($itemTimetable);
                }
            }
        }

        $student_annual_ids = $this->timetableSlotRepos->find_student_annual_ids($infoTimetable);
        if ($infoTimetable->department_id < 12) {
            $department_languages = array(12, 13);
            foreach ($department_languages as $department_language) {
                $groups = $this->timetableSlotRepos->get_group_student_annual_form_language($department_language, $student_annual_ids, $infoTimetable);
                $timetablesLang = $this->timetableSlotRepos->get_timetables_form_language_by_student_annual($groups[0], $infoTimetable, $department_language);
                $timetableSlotsLang = $this->timetableSlotRepos->get_timetable_slot_language_dept($timetablesLang, $groups[0]);
                $this->timetableSlotRepos->set_timetable_slot_language($timetablesSlotsLang, $timetableSlotsLang[1], $timetableSlotsLang[0]);
            }
        }

        return PDF::loadView('backend.schedule.timetables.popup-template-print', compact('timetables', 'timetablesSlotsLang'))
            ->setPaper('A4', 'landscape')
            ->stream();
    }

    /** Custom Function. */

    /**
     * @param Timetable $timetable
     * @param null $group
     * @param $week
     * @return mixed
     */
    public function find_timetable(Timetable $timetable, $group = null, $week)
    {
        return Timetable::where([
            ['academic_year_id', $timetable->academic_year_id],
            ['department_id', $timetable->department_id],
            ['degree_id', $timetable->degree_id],
            ['option_id', $timetable->option_id],
            ['semester_id', $timetable->semester_id],
            ['group_id', $group],
            ['grade_id', $timetable->grade_id],
            ['week_id', $week]
        ])->first();
    }
}