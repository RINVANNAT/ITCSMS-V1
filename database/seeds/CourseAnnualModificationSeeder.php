<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseAnnualModificationSeeder extends Seeder
{
    /**
     * Run the database seeds. This is used to seed data course_annual_classes from the existing course annuals. No need to run this one !
     * php artisan db:seed --class=CourseAnnualModificationSeeder
     * @return void
     */
    public function run()
    {
        $course_annuals = DB::table('course_annuals')
                            ->select([
                                'course_annuals.id',
                                'courses.degree_id',
                                'courses.grade_id',
                                'courses.department_id',
                                'courses.department_option_id',
                            ])
                            ->join('courses','course_annuals.course_id','=','courses.id')
                            ->get();

        foreach($course_annuals as $course_annual){
            $array = array(
                'group' => null,
                'degree_id' => $course_annual->degree_id,
                'grade_id' => $course_annual->grade_id,
                'department_id' => $course_annual->department_id,
                'department_option_id' => $course_annual->department_option_id,
                'course_annual_id' => $course_annual->id,
                'created_at' => \Carbon\Carbon::now(),
                'create_uid' => 1

            );

            DB::table('course_annual_classes')->insert($array);
        }
    }
}
