<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Http\Requests\Backend\Schedule\Timetable\CloneTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\FormCloneTimetableRequest;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\Week;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

/**
 * Class AjaxCloneTimetableController
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait AjaxCloneTimetableController
{
    /**
     * @param CloneTimetableRequest $request
     * @return mixed
     */
    public function cloneTimetable(CloneTimetableRequest $request)
    {
        return Response::json(['status' => true, 'data' => $request->all()]);
    }

    /**
     * Get all weeks by semester.
     *
     * @return mixed
     */
    public function get_all_weeks()
    {
        return Response::json(['weeks' => Week::where('semester_id', request('id'))->get()], 200);
    }

    /**
     * Pass data to clone timetable form.
     *
     * @param FormCloneTimetableRequest $request
     * @return mixed
     */
    public function clone_timetable_form(FormCloneTimetableRequest $request)
    {
        if (isset($request->academicYear) && isset($request->department) && isset($request->degree) && isset($request->grade) && isset($request->semester)) {
            // get all weeks by semester request.
            $weeks = Week::where('semester_id', $request->semester)->get();
            // find groups
            $groups = DB::table('course_annuals')
                ->where([
                    ['academic_year_id', $request->academicYear],
                    ['department_id', $request->department],
                    ['degree_id', $request->degree],
                    ['grade_id', $request->grade],
                    ['department_option_id', $request->option == null ? null : $request->option]
                ])
                ->join('slots', 'slots.course_annual_id', '=', 'course_annuals.id')
                ->join('groups', 'groups.id', '=', 'slots.group_id')
                ->distinct('groups.code')
                ->select('groups.code', 'groups.id')
                ->get();

            usort($groups, function ($a, $b) {
                if (is_numeric($a->code)) {
                    return $a->code - $b->code;
                } else {
                    return strcmp($a->code, $b->code);
                }
            });
            return Response::json(['weeks' => $weeks, 'groups' => $groups], 200);
        }
    }

    /**
     * Clone timetable.
     *
     * @param CloneTimetableRequest $request
     */
    public function clone_timetable(CloneTimetableRequest $request)
    {
        // find timetable by requesting.
        $timetable = Timetable::where([
            ['academic_year_id', $request->academic_year_id],
            ['department_id', $request->department_id],
            ['degree_id', $request->degree_id],
            ['option_id', $request->option_id == null ? null : $request->option_id],
            ['grade_id', $request->grade_id],
            ['semester_id', $request->semester_id],
            ['week_id', $request->week_id],
            ['group_id', $request->group_id == null ? null : $request->group_id]
        ])
            ->first();

        // start clone.
        if($timetable instanceof Timetable){
            if ($this->timetableSlotRepo->clone_timetable($timetable, $request->groups, $request->weeks)) {
                return Response::json(['status' => true], 200);
            }
        }
        return Response::json(['status' => false, 'message' => 'Timetable did not created yet.']);
    }
}