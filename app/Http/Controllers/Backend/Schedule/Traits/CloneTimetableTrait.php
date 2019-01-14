<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Http\Requests\Backend\Schedule\Timetable\CloneTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\FormCloneTimetableRequest;
use App\Models\Schedule\Timetable\MergeTimetableSlot;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableGroupSession;
use App\Models\Schedule\Timetable\TimetableGroupSlot;
use App\Models\Schedule\Timetable\TimetableSlot;
use App\Models\Schedule\Timetable\Week;
use Illuminate\Support\Facades\DB;

/**
 * Class AjaxCloneTimetableController
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait CloneTimetableTrait
{
    public function clone_timetable_form(FormCloneTimetableRequest $request)
    {
        if (isset($request->semester)) {
            $weeks = Week::where('semester_id', $request->semester)->get();
            return [
                'weeks' => $weeks,
                'groups' => [],
                'status' => 200,
            ];
        }
    }

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
                $modelTimetableSlots = $modelTimetable->timetableSlots;
                // process clone
                $weeks = $request->weeks;
                if (count($weeks) > 0) {
                    foreach ($weeks as $week) {
                        if ($week == $request->week_id) {
                            continue;
                        }
                        $timetable = Timetable::firstOrCreate([
                            'academic_year_id' => $request->academic_year_id,
                            'department_id' => $request->department_id,
                            'degree_id' => $request->degree_id,
                            'option_id' => $request->option_id == null ? null : $request->option_id,
                            'grade_id' => $request->grade_id,
                            'semester_id' => $request->semester_id,
                            'week_id' => $week
                        ]);

                        if ($timetable instanceof Timetable) {
                            $timetableSlots = $timetable->timetableSlots;

                            // before copy, remove and update all old timetable slot back
                            if (count($timetableSlots) > 0) {
                                foreach ($timetableSlots as $timetableSlot) {
                                    if ($timetableSlot instanceof TimetableSlot) {
                                        $groupIds = TimetableGroupSession::where([
                                            'timetable_slot_id' => $timetableSlot->id
                                        ])->pluck('timetable_group_id');

                                        foreach ($groupIds as $groupId) {
                                            $timetableGroupSlot = TimetableGroupSlot::where([
                                                'slot_id' => $timetableSlot->slot_id,
                                                'timetable_group_id' => $groupId
                                            ])->first();
                                            if ($timetableGroupSlot instanceof TimetableGroupSlot) {
                                                $timetableGroupSlot->total_hours_remain += $timetableSlot->durations;
                                                $timetableGroupSlot->update();
                                                TimetableGroupSession::where([
                                                    'timetable_slot_id' => $timetableSlot->id,
                                                    'timetable_group_id' => $groupId
                                                ])->delete();
                                            }
                                        }
                                        $timetableSlot->delete();
                                    }
                                }
                            }

                            // start copy
                            foreach ($modelTimetableSlots as $timetableSlot) {
                                if ($timetableSlot instanceof TimetableSlot) {
                                    $canCopyTimetableSlot = false;
                                    $groupIds = [];
                                    $groups = $timetableSlot->groups;
                                    if (count($groups) > 0) {
                                        foreach ($groups as $group) {
                                            $timetableGroupSlot = TimetableGroupSlot::where([
                                                'slot_id' => $timetableSlot->slot_id,
                                                'timetable_group_id' => $group->id
                                            ])->where('total_hours_remain', '>=', $timetableSlot->durations)->first();

                                            if ($timetableGroupSlot instanceof TimetableGroupSlot) {
                                                $canCopyTimetableSlot = true;
                                                array_push($groupIds, $group->id);
                                            }
                                        }
                                    }

                                    if ($canCopyTimetableSlot) {
                                        $newMergeTimetableSlot = MergeTimetableSlot::create([
                                            'start' => $timetableSlot->start,
                                            'end' => $timetableSlot->end
                                        ]);
                                        $newTimetableSlot = TimetableSlot::create([
                                            'timetable_id' => $timetable->id,
                                            'course_program_id' => $timetableSlot->course_program_id,
                                            'slot_id' => $timetableSlot->slot_id,
                                            'group_merge_id' => $newMergeTimetableSlot->id,
                                            'course_name' => $timetableSlot->course_name,
                                            'type' => $timetableSlot->type,
                                            'durations' => $timetableSlot->durations,
                                            'start' => $timetableSlot->start,
                                            'end' => $timetableSlot->end
                                        ]);
                                        foreach ($groupIds as $groupId) {
                                            $timetableGroupSession = TimetableGroupSession::where([
                                                'timetable_slot_id' => $timetableSlot->id,
                                                'timetable_group_id' => $groupId
                                            ])->first();
                                            if ($timetableGroupSession instanceof TimetableGroupSession) {
                                                TimetableGroupSession::create([
                                                    'timetable_slot_id' => $newTimetableSlot->id,
                                                    'timetable_group_id' => $groupId,
                                                    'room_id' => $timetableGroupSession->room_id
                                                ]);
                                                $timetableGroupSlot = TimetableGroupSlot::where([
                                                    'slot_id' => $timetableSlot->slot_id,
                                                    'timetable_group_id' => $groupId
                                                ])->first();
                                                if ($timetableGroupSlot instanceof TimetableGroupSlot) {
                                                    $timetableGroupSlot->total_hours_remain -= $timetableSlot->durations;
                                                    $timetableGroupSlot->update();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    DB::commit();
                    return message_success([]);
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