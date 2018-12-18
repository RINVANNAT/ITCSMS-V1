<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Http\Requests\Backend\Schedule\Timetable\AddRoomIntoTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\MoveTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\RemoveTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\ResizeTimetableSlotRequest;
use App\Models\Employee;
use App\Models\Group;
use App\Models\Room;
use App\Models\Schedule\Timetable\MergeTimetableSlot;
use App\Models\Schedule\Timetable\Slot;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableGroup;
use App\Models\Schedule\Timetable\TimetableGroupSession;
use App\Models\Schedule\Timetable\TimetableGroupSessionLecturer;
use App\Models\Schedule\Timetable\TimetableGroupSlot;
use App\Models\Schedule\Timetable\TimetableGroupSlotLecturer;
use App\Models\Schedule\Timetable\TimetableSlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait TimetableSlotTrait
{
    public function get_timetable_slots(CreateTimetableRequest $request)
    {
        try {
            $result = array(
                'code' => 200,
                'message' => 'Successfully',
                'timetable' => null,
                'timetableSlots' => [],
            );

            $timetable = Timetable::where([
                ['academic_year_id', $request->academicYear],
                ['department_id', $request->department],
                ['degree_id', $request->degree],
                ['grade_id', $request->grade],
                ['option_id', $request->option == null ? null : $request->option],
                ['semester_id', $request->semester],
                ['week_id', $request->weekly]
            ])->first();

            if ($timetable instanceof Timetable) {
                $timetableSlots = TimetableSlot::with('groups', 'employee', 'room')
                    ->where('timetable_id', $timetable->id)
                    ->get();

                $testTimetableSlots = $timetable->timetableSlots;


                $found = [];

                foreach ($testTimetableSlots as $testTimetableSlot) {
                    $otherTimetableSlots = TimetableSlot::join('timetables', 'timetables.id', '=', 'timetable_slots.timetable_id')
                        ->whereNotIn('timetables.id', [$timetable->id])
                        ->where([
                            'timetables.academic_year_id' => $request->academicYear,
                            'timetables.semester_id' => $request->semester,
                            'timetables.week_id' => $request->weekly
                        ])
                        ->where(function ($query) use ($testTimetableSlot) {
                            $query->where('start', '<', $testTimetableSlot->start)
                                ->where('end', '>', $testTimetableSlot->start);
                        })
                        ->orWhere(function ($query) use ($testTimetableSlot) {
                            $query->where('start', '<', $testTimetableSlot->end)
                                ->where('end', '>', $testTimetableSlot->end);
                        })
                        ->get();
                    if (count($otherTimetableSlots) > 0) {
                        array_push($found, [$testTimetableSlot->id, $otherTimetableSlots]);
                    }
                }

                if ($timetable instanceof Timetable) {
                    $result['timetable'] = $timetable;
                    $result['timetableSlots'] = $timetableSlots;
                    $result['otherTimetableSlots'] = $otherTimetableSlots;
                    $result['testTimetableSlots'] = $testTimetableSlots;
                    $result['found'] = $found;
                }
            } else {
                $result['timetable'] = null;
                $result['timetableSlots'] = [];
            }
            return $result;
        } catch (\Exception $e) {
            return message_error($e->getMessage());
        }
    }

    public function move_timetable_slot(MoveTimetableSlotRequest $request)
    {
        if (isset($request->timetable_slot_id)) {
            $timetable_slot = TimetableSlot::find($request->timetable_slot_id);
            if ($timetable_slot instanceof TimetableSlot) {
                $start = new Carbon($request->start_date);
                $end = $start->addHours($timetable_slot->durations);
                $timetable_slot->start = new Carbon($request->start_date);
                $timetable_slot->end = $end;
                $timetable_slot->updated_at = Carbon::now();
                if ($timetable_slot->update()) {
                    // update_merge_timetable_slot
                }
                return ['status' => true];
            }
        }
        return ['status' => false];
    }

    public function resize_timetable_slot(ResizeTimetableSlotRequest $request)
    {
        DB::beginTransaction();
        try {
            try {
                $timetable_slot = TimetableSlot::find($request->timetable_slot_id);

                if ($timetable_slot instanceof TimetableSlot) {
                    $groups = $timetable_slot->groups->pluck('id');

                    $old_durations = $timetable_slot->durations;
                    $new_durations = durations(new Carbon($timetable_slot->start), new Carbon($request->end));
                    $interval = $new_durations - $old_durations;

                    $timetable_slot->durations = $new_durations;
                    $timetable_slot->end = new Carbon($request->end);

                    $timetableGroupSlots = TimetableGroupSlot::where('slot_id', $timetable_slot->slot_id)
                        ->whereIn('timetable_group_id', $groups)
                        ->get();

                    $groupUnableResize = [];
                    foreach ($timetableGroupSlots as $timetableGroupSlot) {
                        if (!(($timetableGroupSlot->total_hours_remain > 0) && ($timetableGroupSlot->total_hours_remain >= $interval))) {
                            array_push($groupUnableResize, $timetableGroupSlot->timetable_group_id);
                        }
                    }

                    if (count($groupUnableResize) > 0) {
                        return message_error(Group::whereIn('id', $groupUnableResize)->get());
                    } else {
                        foreach ($timetableGroupSlots as $timetableGroupSlot) {
                            $timetableGroupSlot->total_hours_remain = (float)$timetableGroupSlot->total_hours_remain - (float)$interval;
                            $timetableGroupSlot->update();
                        }
                        $timetable_slot->update();
                        DB::commit();
                        return message_success($timetable_slot);
                    }
                }
            } catch (\Exception $e) {
                return message_error($e->getMessage());
            }
        } catch (\Exception $e) {
            DB::rollback();
            return message_error($e->getMessage());
        }
    }

    public function insert_room_into_timetable_slot(AddRoomIntoTimetableSlotRequest $request)
    {
        DB::beginTransaction();
        try {
            $timetableSlot = TimetableSlot::find($request->timetable_slot_id);
            $room_id = $request->room_id;
            if ($timetableSlot instanceof TimetableSlot) {
                $timetableGroupSessions = TimetableGroupSession::where('timetable_slot_id', $timetableSlot->id)->get();
                if (count($timetableGroupSessions) > 0) {
                    foreach ($timetableGroupSessions as $timetableGroupSession) {
                        $timetableGroupSession->room_id = $room_id;
                        $timetableGroupSession->update();
                    }
                    DB::commit();
                    return message_success([]);
                }
                return message_error('There are 0 records updated');
            }
            return message_error('Could not found timetable slot.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return message_error($exception->getMessage());
        }
    }

    public function remove_timetable_slot(RemoveTimetableSlotRequest $removeTimetableSlotRequest)
    {
        DB::beginTransaction();
        try {
            $timetable_slot_id = $removeTimetableSlotRequest->timetable_slot_id;
            $timetableSlot = TimetableSlot::find($timetable_slot_id);
            if ($timetableSlot instanceof TimetableSlot) {
                $groups = $timetableSlot->groups->pluck('id');
                $timetableGroupSlots = TimetableGroupSlot::where('slot_id', $timetableSlot->slot_id)
                    ->whereIn('timetable_group_id', $groups)
                    ->get();

                foreach ($timetableGroupSlots as $groupSlot) {
                    $groupSlot->total_hours_remain += $timetableSlot->durations;
                    $groupSlot->update();
                }
                TimetableGroupSession::where('timetable_slot_id', $timetableSlot->id)->delete();
                $timetableSlot->delete();
                DB::commit();
                return message_success([]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return message_error($e->getMessage());
        }
    }

    public function assignGroupToTimetableSlot(Request $request)
    {
        DB::beginTransaction();
        $this->validate($request, [
            'timetable_slot_id' => 'required',
            'timetable_group_id' => 'required'
        ]);

        try {
            $timetableSlot = TimetableSlot::find($request->timetable_slot_id);

            if ($timetableSlot instanceof TimetableSlot) {
                $slot = Slot::find($timetableSlot->slot_id);

                $timetableGroupSession = TimetableGroupSession::where([
                    'timetable_slot_id' => $request->timetable_slot_id,
                    'timetable_group_id' => $request->timetable_group_id
                ])->first();

                if (is_null($timetableGroupSession)) {
                    $timetableGroupSession = TimetableGroupSession::create([
                        'timetable_slot_id' => $request->timetable_slot_id,
                        'timetable_group_id' => $request->timetable_group_id
                    ]);
                }

                $timetableGroupSlot = TimetableGroupSlot::where([
                    'slot_id' => $slot->id,
                    'timetable_group_id' => $request->timetable_group_id
                ])->first();

                if (is_null($timetableGroupSlot)) {
                    TimetableGroupSlot::create([
                        'slot_id' => $slot->id,
                        'timetable_group_id' => $request->timetable_group_id,
                        'total_hours' => $slot->total_hours,
                        'total_hours_remain' => ($slot->total_hours - $timetableSlot->durations)  // eliminate hours by default session durations
                    ]);
                }
                DB::commit();
                return message_success($timetableGroupSession);
            }
            return message_error('The timetable slot could not found!');
        } catch (\Exception $exception) {
            DB::rollback();
            return message_error($exception->getMessage());
        }
    }

    public function assign_lecturer_to_timetable_slot(Request $request)
    {
        DB::beginTransaction();
        $this->validate($request, [
            'timetable_slot_id' => 'required',
            'lecturer_id' => 'required'
        ]);
        try {
            $timetableSlot = TimetableSlot::find($request->timetable_slot_id);
            if ($timetableSlot instanceof TimetableSlot) {
                $employee = Employee::find($request->lecturer_id);
                if ($employee instanceof Employee) {
                    $timetableGroupSessionIds = TimetableGroupSession::where('timetable_slot_id', $timetableSlot->id)->pluck('id');
                    if (count($timetableGroupSessionIds) > 0) {
                        foreach ($timetableGroupSessionIds as $timetableGroupSessionId) {
                            TimetableGroupSessionLecturer::firstOrCreate([
                                'timetable_group_session_id' => $timetableGroupSessionId,
                                'lecturer_id' => $employee->id
                            ]);
                        }
                        DB::commit();
                        return message_success([]);
                    }
                    return message_error('There are no one lecturers set');
                }
                return message_error('Could not found lecturer');
            }
            return message_error('Could not found timetable slot.');
        } catch (\Exception $exception) {
            DB::rollback();
            return message_error($exception->getMessage());
        }
    }

    public function removeGroupFromTimetableSlot(Request $request)
    {
        $this->validate($request, [
            'timetable_slot_id' => 'required',
            'timetable_group_id' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $timetableSlot = TimetableSlot::find($request->timetable_slot_id);
            $durations = $timetableSlot->durations;
            if ($timetableSlot instanceof TimetableSlot) {
                $slot = Slot::find($timetableSlot->slot_id);
                if ($slot instanceof Slot) {
                    $timetableGroupSession = TimetableGroupSession::where([
                        'timetable_slot_id' => $request->timetable_slot_id,
                        'timetable_group_id' => $request->timetable_group_id
                    ])->first();

                    if ($timetableGroupSession instanceof TimetableGroupSession) {
                        $timetableGroupSession->delete();
                    }

                    // update timetable group slot
                    $timetableGroupSlot = TimetableGroupSlot::where([
                        'slot_id' => $slot->id,
                        'timetable_group_id' => $request->timetable_group_id
                    ])->first();

                    if ($timetableGroupSlot instanceof TimetableGroupSlot) {
                        $timetableGroupSlot->total_hours_remain += $durations;
                        $timetableGroupSlot->update();
                    }
                    DB::commit();
                    return message_success([]);
                }
            }
            return message_error('Could not delete group!');
        } catch (\Exception $exception) {
            DB::rollback();
            return message_error($exception->getMessage());
        }
    }

    public function assignLecturersToTimetableSlotGroup(Request $request)
    {
        DB::beginTransaction();
        try {
            $timetable_group_slots = $request->timetable_group_slots;
            if (count($timetable_group_slots) > 0) {
                foreach ($timetable_group_slots as $timetable_group_slot) {
                    $timetable_group_slot_id = $timetable_group_slot['timetable_group_slot_id'];
                    $timetable_group_slot_lecturer_ids = $timetable_group_slot['lecturer_ids'];
                    foreach ($timetable_group_slot_lecturer_ids as $lecturer_id) {
                        (new TimetableGroupSlotLecturer())->create([
                            'timetable_group_slot_id' => $timetable_group_slot_id,
                            'lecturer_id' => $lecturer_id
                        ]);
                    }
                }
            }
            DB::commit();
            return message_success([]);
        } catch (\Exception $exception) {
            DB::rollback();
            return message_error($exception->getMessage());
        }
    }

    public function removeLecturersToTimetableSlotGroup(Request $request)
    {
        DB::beginTransaction();
        try {
            // @TODO implement your code here...

            DB::commit();
            return message_success([]);
        } catch (\Exception $exception) {
            DB::rollback();
            return message_error($exception->getMessage());
        }
    }

    public function getGroupByTimetableSlot(Request $request)
    {
        $this->validate($request, [
            'timetable_slot_id' => 'required'
        ]);

        try {
            $timetable_group_sessions = TimetableGroupSession::where('timetable_slot_id', $request->timetable_slot_id)->get();
            $results = [];
            if (count($timetable_group_sessions)) {
                foreach ($timetable_group_sessions as $timetable_group_session) {
                    $newItem = [];
                    $newItem['group'] = TimetableGroup::find($timetable_group_session->timetable_group_id);
                    $newItem['room'] = Room::join('buildings', 'buildings.id', '=', 'rooms.building_id')
                        ->where('rooms.id', $timetable_group_session->room_id)
                        ->select([
                            DB::raw("CONCAT(buildings.code, '-', rooms.name) as code"),
                            'rooms.id as id'
                        ])->first();
                    $timetable_group_session_lecturer = TimetableGroupSessionLecturer::where([
                        'timetable_group_session_id' => $timetable_group_session->id
                    ])->first();
                    if ($timetable_group_session_lecturer instanceof TimetableGroupSessionLecturer) {
                        $newItem['lecturer'] = Employee::join('genders', 'genders.id', '=', 'employees.gender_id')
                            ->where('employees.id', $timetable_group_session_lecturer->lecturer_id)
                            ->select([
                                DB::raw('upper(name_latin) as name_latin'),
                                'employees.name_kh',
                                'employees.id as id',
                                'genders.code as gender_code'
                            ])
                            ->orderBy('name_latin', 'asc')
                            ->first();
                    }

                    array_push($results, $newItem);
                }
            }
            return message_success($results);
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function getEmployees()
    {
        try {
            $employees = Employee::join('genders', 'genders.id', '=', 'employees.gender_id')
                ->select([
                    DB::raw('upper(name_latin) as name_latin'),
                    'employees.name_kh',
                    'employees.id as id',
                    'genders.code as gender_code'
                ])
                ->orderBy('name_latin', 'asc')
                ->get();

            return message_success($employees);
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function assignRoomAndLecturerToGroup(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'timetable_slot_id' => 'required',
                'data' => 'required|array'
            ]);

            $tmpLecturerIds = [];
            if (count($request->data) > 0) {
                $data = $request->data;
                foreach ($data as $item) {
                    array_push($tmpLecturerIds, $item['lecturer']['id']);
                }

                $firstLecturerId = null;
                foreach ($tmpLecturerIds as $key => $lecturerId) {
                    if ($key == 0) {
                        $firstLecturerId = $lecturerId;
                    } else {
                        if ($firstLecturerId != $lecturerId) {
                            return message_error('Could not assign different lecturer');
                        }
                    }
                }
            }


            $timetableSlot = TimetableSlot::find($request->timetable_slot_id);
            if ($timetableSlot instanceof TimetableSlot) {
                TimetableGroupSession::where('timetable_slot_id', $timetableSlot->id)->delete();
                if (count($request->data) > 0) {

                    // assign group to timetable slot group
                    $slot = Slot::find($timetableSlot->slot_id);

                    foreach ($request->data as $data) {
                        if (isset($data['group']['id']) && isset($data['room']['id'])) {

                            $timetableGroupSlot = TimetableGroupSlot::where([
                                'slot_id' => $slot->id,
                                'timetable_group_id' => $data['group']['id']
                            ])->first();

                            if (!($timetableGroupSlot instanceof TimetableGroupSlot)) {
                                TimetableGroupSlot::create([
                                    'slot_id' => $slot->id,
                                    'timetable_group_id' => $data['group']['id'],
                                    'total_hours' => $slot->total_hours,
                                    'total_hours_remain' => $slot->total_hours - $timetableSlot->durations
                                ]);
                            }

                            $timetableGroupSession = TimetableGroupSession::firstOrCreate([
                                'timetable_slot_id' => $request->timetable_slot_id,
                                'timetable_group_id' => $data['group']['id']
                            ]);

                            if ($timetableGroupSession instanceof TimetableGroupSession) {

                                $timetableGroupSession->room_id = $data['room']['id'];
                                $timetableGroupSession->update();

                                if (isset($data['lecturer']['id'])) {
                                    $timetableGroupSessionLecturer = TimetableGroupSessionLecturer::where([
                                        'timetable_group_session_id' => $timetableGroupSession->id,
                                        'lecturer_id' => $data['lecturer']['id']
                                    ])->first();

                                    if (!($timetableGroupSessionLecturer instanceof TimetableGroupSessionLecturer)) {
                                        TimetableGroupSessionLecturer::create([
                                            'timetable_group_session_id' => $timetableGroupSession->id,
                                            'lecturer_id' => $data['lecturer']['id']
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                    DB::commit();
                    return message_success($timetableSlot);
                }
            } else {
                return message_error('Could not found timetable slot.');
            }
        } catch (\Exception $exception) {
            DB::rollback();
            return message_error($exception->getMessage());
        }
    }

    private function createTimetableSlot(Timetable $timetable, CreateTimetableSlotRequest $request)
    {
        DB::beginTransaction();
        try {
            $newMergeTimetableSlot = $this->createMergeTimetableSlot($request);
            if ($newMergeTimetableSlot instanceof MergeTimetableSlot) {
                $slot = Slot::with('groups')->find($request->slot_id);
                if ($slot instanceof Slot) {
                    $start = new Carbon($request->start);
                    $end = new Carbon($request->end == null ? $request->start : $request->end);
                    $duration = $this->durations($start, $end);
                    $groups = $slot->groups->pluck('id');

                    $timetableGroupSlots = TimetableGroupSlot::where('slot_id', $slot->id)
                        ->whereIn('timetable_group_id', $groups)
                        ->where('total_hours_remain', '>', 0)
                        ->where('total_hours_remain', '>=', $duration)
                        ->get();

                    $newTimetableSlot = (new TimetableSlot())->create([
                        'timetable_id' => $timetable->id,
                        'course_program_id' => $request->course_program_id,
                        'room_id' => null,
                        'slot_id' => $slot->id,
                        'group_merge_id' => $newMergeTimetableSlot->id,
                        'course_name' => $request->course_name,
                        'lecturer_id' => null,
                        'type' => $request->course_type,
                        'start' => $start,
                        'end' => $end,
                        'durations' => $duration
                    ]);

                    if (count($timetableGroupSlots) > 0) {
                        // and then create timetable group
                        foreach ($timetableGroupSlots as $timetableGroupSlot) {
                            $timetableGroupSlot->total_hours_remain -= $duration;
                            $timetableGroupSlot->update();

                            // add timetable slot with group on timetable_group_session
                            (new TimetableGroupSession())->create([
                                'timetable_slot_id' => $newTimetableSlot->id,
                                'timetable_group_id' => $timetableGroupSlot->timetable_group_id,
                                'room_id' => $timetableGroupSlot->room_id
                            ]);
                        }
                    }
                    DB::commit();
                    return $newTimetableSlot;
                }
            }
        } catch (\Exception $exception) {
            DB::rollback();
            return message_error($exception->getMessage());
        }
    }

    private function createMergeTimetableSlot(CreateTimetableSlotRequest $request)
    {
        try {
            if (isset($request->start) || isset($request->end)) {
                $newMergeTimetableSlot = new MergeTimetableSlot();
                $newMergeTimetableSlot->start = (new Carbon($request->start))->setTimezone('Asia/Phnom_Penh');
                $newMergeTimetableSlot->end = (new Carbon($request->end == null ? $request->start : $request->end))->setTimezone('Asia/Phnom_Penh');
                if ($newMergeTimetableSlot->save()) {
                    return $newMergeTimetableSlot;
                }
            }
            return false;
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }
}