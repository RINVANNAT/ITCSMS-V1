<?php
/**
 * Created by PhpStorm.
 * User: imac-07
 * Date: 3/27/18
 * Time: 10:34 AM
 */

namespace App\Http\Controllers\Backend\StudentTrait;

use PDF;

trait AverageFinalYearTrait
{
    /**
     * @return mixed
     */
    public function print_average_final_year($type){

        if ($type == "show"){
            return view('backend.studentAnnual.average_final_year');
        }
        return PDF::loadView('backend.studentAnnual.print.average_final_year')->setPaper('a4')->stream();
    }
}