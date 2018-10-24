<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Employee;
use App\Models\Schedule\Timetable\TimetableGroupSession;
use App\Models\Schedule\Timetable\TimetableGroupSessionLecturer;
use App\Models\Schedule\Timetable\TimetableGroupSlotLecturer;
use App\Models\Schedule\Timetable\TimetableSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            // @TODO implement your code here...
            $timetableSlot = TimetableSlot::find($request->timetable_slot_id);
            if ($timetableSlot instanceof TimetableSlot) {
                return message_success($timetableSlot->groups);
            }
            return message_success([]);
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

            $timetableSlot = TimetableSlot::find($request->timetable_slot_id);
            if ($timetableSlot instanceof TimetableSlot) {
                if (count($request->data) > 0) {
                    foreach ($request->data as $data) {
                        if (isset($data['group']['id']) && isset($data['room']['id'])) {
                            $timetableGroupSession = TimetableGroupSession::where([
                                'timetable_slot_id' => $request->timetable_slot_id,
                                'timetable_group_id' => $data['group']['id']
                            ])->first();

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