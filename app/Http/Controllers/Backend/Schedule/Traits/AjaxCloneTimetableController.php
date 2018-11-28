<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Http\Requests\Backend\Schedule\Timetable\CloneTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\FormCloneTimetableRequest;
use App\Models\Group;
use App\Models\Schedule\Timetable\Slot;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
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
        return message_success($request->all);
        // return Response::json(['status' => true, 'data' => $request->all()]);
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
        if (isset($request->semester)) {
            // get all weeks by semester request.
            $weeks = Week::where('semester_id', $request->semester)->get();
            // find groups
            // $groups = Group::select('groups.code', 'groups.id')->get()->toArray();
            // $groups = $this->timetableSlotRepo->sort_groups($groups);
            return Response::json(['weeks' => $weeks, 'groups' => []], 200);
        }
    }

    /**
     * Clone timetable.
     *
     * @param CloneTimetableRequest $request
     * @return mixed
     */
    public function clone_timetable(CloneTimetableRequest $request)
    {
        DB::beginTransaction();
        try {
            $modelTimetable = Timetable::where([
                ['academic_year_id', $request->academic_year_id],
                ['department_id', $request->department_id],
                ['degree_id', $request->degree_id],
                ['option_id', $request->option_id == null ? null : $request->option_id],
                ['grade_id', $request->grade_id],
                ['semester_id', $request->semester_id],
                ['week_id', $request->week_id]
            ])->first();

            if ($modelTimetable instanceof Timetable) {
                // process clone
                $weeks = $request->weeks;
                if (count($weeks) > 0) {
                    foreach ($weeks as $week) {
                        if ($week == $request->week_id) {
                            continue;
                        } else {
                            $timetable = Timetable::firstOrCreate([
                                'academic_year_id' => $request->academic_year_id,
                                'department_id' => $request->department_id,
                                'degree_id' => $request->degree_id,
                                'option_id' => $request->option_id == null ? null : $request->option_id,
                                'grade_id' => $request->grade_id,
                                'semester_id' => $request->semester_id,
                                'week_id' => $week
                            ])->first();

                            if ($timetable instanceof Timetable) {
                                // update old timetable slots and delete it all
                                $timetableSlots = $timetable->timetableSlots;
                                if (count($timetableSlots) >0) {
                                    foreach ($timetableSlots as $timetableSlot) {
                                        if ($timetableSlot instanceof TimetableSlot) {
                                            if(count($timetableSlot->groups())) {
                                                return message_success($timetableSlot->groups());
                                            }
                                        }
                                    }
                                }
                            }


//                            if ($newTimetable instanceof Timetable) {
//                                $newTimetableSlots = $newTimetable->timetableSlots;
//                                // remove old timetable slot and update slots table
//                                if (count($newTimetableSlots) > 0) {
//                                    foreach ($newTimetableSlots as $newTimetableSlot) {
//                                        $newSlot = Slot::find($newTimetableSlot->slot_id);
//                                        $newSlot->time_remaining += $newTimetableSlot->durations;
//                                        if ($newSlot->update()) {
//                                            $newTimetableSlot->delete();
//                                        }
//                                    }
//                                }
//                            } else {
//                                $newTimetable = Timetable::create([
//                                    'academic_year_id' => $request->academic_year_id,
//                                    'department_id' => $request->department_id,
//                                    'degree_id' => $request->degree_id,
//                                    'option_id' => $request->option_id == null ? null : $request->option_id,
//                                    'grade_id' => $request->grade_id,
//                                    'semester_id' => $request->semester_id,
//                                    'week_id' => $week
//                                ]);
//                            }
//                            // find all timetable slots which we are working on
//                            $timetable_slots = TimetableSlot::where('timetable_id', $timetable->id)->get();
//                            if (count($timetable_slots) > 0) {
//                                foreach ($timetable_slots as $timetable_slot) {
//                                    $slot = Slot::find($timetable_slot->slot_id);
//                                    if (($slot instanceof Slot) && ($slot->time_remaining >= $timetable_slot->durations)) {
//                                        $this->timetableSlotRepo->copied_timetable_slot($slot, $newTimetable, $timetable_slot);
//                                    }
//                                }
//                            }
                        }
                    }
                    DB::commit();
                    return message_success(['message' => 'The timetable was cloned successfully']);
                } else {
                    DB::rollback();
                    return message_error('There are no weeks selected!');
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return message_error($e->getMessage());
        }
    }
}