<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseAnnualGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=CourseAnnualGroupSeeder
     * @return void
     */
    public function run()
    {
        $courseAnnuals = DB::table('course_annuals')->where('academic_year_id', 2017)->get();

       	foreach($courseAnnuals as $courseAnnual) {
       		$courseAnnualClasses = DB::table('course_annual_classes')->where('course_annual_id', $courseAnnual->id)->get();

       		if($courseAnnualClasses) {

       			foreach($courseAnnualClasses as $class) {
	       			if(($class->group != null) && ($class->group != '')) {

	       				$group =  DB::table('groups')->where('code', $class->group)->first();
	       				DB::table('course_annual_classes')->where('id', $class->id)->update(['group_id' => $group->id]);
	       			} else {
	       				DB::table('course_annual_classes')->where('id', $class->id)->update(['group_id' => null]);
	       			}

       			}
       		}
       	}
    }
}
