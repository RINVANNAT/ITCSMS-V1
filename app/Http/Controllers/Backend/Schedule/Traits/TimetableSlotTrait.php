<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Employee;
use App\Models\Room;
use App\Models\Schedule\Timetable\Slot;
use App\Models\Schedule\Timetable\TimetableGroup;
use App\Models\Schedule\Timetable\TimetableGroupSession;
use App\Models\Schedule\Timetable\TimetableGroupSessionLecturer;
use App\Models\Schedule\Timetable\TimetableGroupSlot;
use App\Models\Schedule\Timetable\TimetableGroupSlotLecturer;
use App\Models\Schedule\Timetable\TimetableSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait TimetableSlotTrait
{
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
            if(count($request->data) > 0) {
                $data = $request->data;
                foreach ($data as $item) {
                    array_push($tmpLecturerIds, $item['lecturer']['id']);
                }

                $firstLecturerId = null;
                foreach ($tmpLecturerIds as $key => $lecturerId) {
                    if($key == 0) {
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
}