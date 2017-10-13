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


if (!function_exists('fill_out_cell')) {
    /**
     * @param $timetableSlots
     * @return string
     */
    function fill_out_cell($timetableSlots, $str_start, $str_end)
    {
        $cols = '';
        for ($day = 2; $day <= 7; $day++) {
            foreach ($timetableSlots as $slot) {
                if ((new \Carbon\Carbon($slot->start))->day == $day) {
                    if (get_date_str($slot->start) == $str_start) {
                        $cols .= '<td rowspan="' . get_rowspan($slot->start, $slot->end) . '">'
                            . '<p>' . $slot->course_name . '</p>'
                            . '</td>';
                    }
                } else {
                    $cols .= '<td></td>';
                }
            }
        }

        return $cols;
    }
}

if (!function_exists('get_rowspan')) {
    /**
     * @param $start
     * @param $end
     * @return float|int
     */
    function get_rowspan($start, $end)
    {
        $start = (new \Carbon\Carbon($start));
        $end = (new \Carbon\Carbon($end));
        $minute = $end->diffInMinutes($start);
        return ($minute) / (30);
    }
}

if (!function_exists('get_date_str')) {
    /**
     * @param $date
     * @return string
     */
    function get_date_str($date)
    {
        $date = new \Carbon\Carbon($date);
        $hour = $date->hour;
        $minute = $date->minute;
        if ($minute == 30) {
            return $hour . ':' . $minute;
        }
        return '' . $hour . '';
    }
}

if (!function_exists('smis_str_limit')) {
    /**
     * @param $str
     * @param $limit_str
     * @return string
     */
    function smis_str_limit($str, $limit_str)
    {
        if (strlen($str) > $limit_str) {
            return str_limit($str, $limit_str);
        }
        return $str;
    }
}

if (!function_exists('smis_concat_room')) {
    /**
     * @param $room
     * @param $building
     * @param null $symbol
     * @return string
     */
    function smis_concat_str($room, $building, $symbol=null)
    {
        if($symbol == null){
            return $room. '-' .$building;
        }
        return $room . $symbol . $building;
    }
}
