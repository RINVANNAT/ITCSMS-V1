<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Schedule\Timetable\TimetableGroupSlotLecturer;
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
}