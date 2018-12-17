<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Schedule\Timetable\Slot;
use App\Models\Schedule\Timetable\TimetableGroup;
use App\Models\Schedule\Timetable\TimetableGroupSlot;
use Illuminate\Http\Request;

trait TimetableGroupTrait
{
    public function getGroups(Request $request)
    {
        try {
            $groups = TimetableGroup::select('code', 'id')->get();
            return message_success($groups);
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function getGroupsByTimetableSlot(Request $request)
    {
        $this->validate($request, [
            'slot_id' => 'required'
        ]);

        try {
            $slot = Slot::find($request->slot_id);
            if ($slot instanceof Slot) {
                $timetableGroupSlots = TimetableGroupSlot::join('timetable_groups', 'timetable_group_slots.id', '=', 'timetable_groups.slot_id')
                    ->join('timetable_group_slot_lecturers', 'timetable_group_slot_lecturers.timetable_group_slot_id', '=', 'timetable_group_slots.id')
                    ->join('employees', 'employees.id', '=', 'timetable_group_slot_lecturers.lecturer_id')
                    ->join('genders', 'genders.id', '=', 'employees.gender_id')
                    ->where('timetable_group_slots.id', $slot->id)
                    ->select([
                        'timetable_group_slots.id as slot_id',
                        'timetable_groups.id as group_id',
                        'timetable_groups.code as group_code',
                        'employees.name_kh as lecturer_id',
                        'employees.name_latin as lecturer_id',
                        'employees.id as lecturer_id',
                        'genders.code as gender_code'
                    ])
                    ->get();
                return message_success($timetableGroupSlots);
            }
            return message_error('The course program not found!');
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }
}