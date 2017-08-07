<?php

namespace App\Http\Controllers\Backend\Traits;
use App\Models\Department;
use App\Models\StudentAnnual;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: thavorac
 * Date: 7/30/17
 * Time: 9:48 PM
 * Description: to get filter value
 */
trait FilteringTrait
{
    public function get_available_class($academic_id){
        $filter = [];
        $students = StudentAnnual::where("academic_year_id",$academic_id)
                                ->join("degrees","degrees.id","=","studentAnnuals.degree_id")
                                ->join("departments","departments.id","=","studentAnnuals.department_id")
                                ->leftJoin("departmentOptions","departmentOptions.id","=","studentAnnuals.department_option_id")
                                ->select([
                                    DB::raw('CONCAT(degrees.code,grade_id,departments.code,"departmentOptions".code) AS class'),
                                    "studentAnnuals.degree_id",
                                    "studentAnnuals.grade_id",
                                    "studentAnnuals.department_id",
                                    "studentAnnuals.department_option_id",
                                    "departments.code as department"
                                ])
                                ->get()
                                ->sortBy(function($item) {
                                    return $item->degree_id."-".$item->department_id."-".$item->grade_id."-".$item->department_option_id;
                                })
                                ->groupBy("class");

        $students->each(function ($item, $key) use (&$filter) {
            $filter[$item[0]->department][$key] = [
                "degree_id" => $item[0]->degree_id,
                "grade_id" => $item[0]->grade_id,
                "department_id" => $item[0]->department_id,
                "department_option_id" => $item[0]->department_option_id,
            ];
        });
        return $filter;
    }
}