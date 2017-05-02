<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class GroupStudentAnnualSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=GroupStudentAnnualSeeder
     * @return void
     */
    public function run()
    {


    	$studentAnnuals = DB::table("studentAnnuals")->where('academic_year_id', 2017)->get();

    	foreach($studentAnnuals as $studentAnnual) {
    		if(($studentAnnual->group != null) && ($studentAnnual->group !='')) {
    			$group = DB::table('groups')->where('code', $studentAnnual->group)->first();
    			DB::table("studentAnnuals")->where('id', $studentAnnual->id)->update(['group_id'=> $group->id]);
    		} else {

    			DB::table("studentAnnuals")->where('id', $studentAnnual->id)->update(['group_id'=> null]);
    		}
    	}
        
    }
}
