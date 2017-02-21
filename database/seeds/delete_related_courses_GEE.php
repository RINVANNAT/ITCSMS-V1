<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class delete_related_courses_GEE extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=delete_related_courses_GEE
     *
     * @return void
     */
    public function run()
    {
        $courses = DB::table('courses')
            ->where("department_id",3)
            ->get();

        foreach($courses as $course){
            $course_annuals = DB::table('course_annuals')->where('course_id',$course->id)->get();
            foreach($course_annuals as $course_annual){
                DB::table("course_annual_classes")->where("course_annual_id",$course_annual->id)->delete();
            }

            // Delete course annual later
            DB::table("course_annuals")->where('course_id',$course->id)->delete();

            DB::table("courses")->where('id',$course->id)->delete();
        }

    }
}
