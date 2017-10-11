<?php

if (!function_exists('has_half_hour')) {
    /**
     * @param \App\Models\Schedule\Timetable\Timetable $timetable
     * @return bool
     */
    function has_half_hour(\App\Models\Schedule\Timetable\Timetable $timetable)
    {
        $timetableSlots = $timetable->timetableSlots;
        foreach ($timetableSlots as $timetableSlot) {
            $start = new \Carbon\Carbon($timetableSlot->start);
            $end = new \Carbon\Carbon($timetableSlot->end);
            if (($end->minute - $start->minute) == 30 || ($start->minute - $end->minute) == 30) {
                return true;
            }
        }
        return false;
    }
}
