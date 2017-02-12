<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSession extends Seeder
{
    /**
     * Run the database seeds. This is used to seed data course_annual_classes from the existing course annuals. No need to run this one !
     * php artisan db:seed --class=CourseSession
     * @return void
     */

    public function run()
    {
        $courses = DB::table('course_annuals')
            ->select([
                'course_annuals.id as course_annual_id',
                'time_course', 'time_td', 'time_tp','course_annuals.employee_id'
            ])
            ->get();

        foreach($courses as $course){
            $array = array(
                'time_course'   => $course->time_course,
                'time_td'   => $course->time_td,
                'time_tp'   => $course->time_tp,
                'lecturer_id'   => $course->employee_id,
                'course_annual_id'     => $course->course_annual_id,
                'created_at' => \Carbon\Carbon::now(),
                'create_uid' => 1
            );
            DB::table('course_sessions')->insert($array);
        }
    }
}
