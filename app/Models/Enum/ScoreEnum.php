<?php

/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 1/30/17
 * Time: 10:05 PM
 */

namespace App\Models\Enum;
class ScoreEnum
{

    // in database we must create only two semster which will have these two id (1, 2)
    const Midterm_Final = 90;
    const Midterm_30 = 30;
    const Midterm_40 = 40;
    const Midterm_0 = 0;
    const Col_Header = 7;
    const Highest_Score = 100;

    const Name_Mid = 'midterm';
    const Name_Fin = 'final';
    const is_counted_creditability = 1;
    const is_counted_absence = 1;

}