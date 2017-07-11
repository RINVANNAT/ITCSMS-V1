<?php namespace App\Utils;
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 03/09/2017
 * Time: 2:37 PM
 */

use Carbon\Carbon;
class DateTimeManager{


    public function calculateTime($startTime, $duration) {

        $timeComponents = explode(":", $startTime);
        $hours = $timeComponents[0] * 60 * 60; // convert hour to second
        $minute = $timeComponents[1]* 60; // convert minute to second
        $durationInSecond = $duration *60*60; // we know duration is an hour ...so convert to second

        $totalSecond = $durationInSecond + $hours + $minute;

        $endTimeInSecond = $totalSecond % 60;


    }


    public static function dbDate($date)
    {
        return Carbon::parse($date)->format('Y-m-d');
    }

    public static function viewDate($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i');
    }

    public static function fullDate($date)
    {
        return Carbon::parse($date)->format('M-d-Y');
    }

    public static function fullDateWithTime($date)
    {
        return Carbon::parse($date)->format('M-d-Y H:i');
    }
}