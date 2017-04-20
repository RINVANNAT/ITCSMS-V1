<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=GroupTableSeeder
     * @return void
     */
    public function run()
    {

    	$degrees = [1,2]; /*--engineer and association--*/
    	$grades = [1,2,3,4,5];/*--year 1 to year 5----*/
    	$departments = [1,2,3,4,5,6,7,8]; /*---[GCA, GCI, GEE, GIC, GIM, GRU, GGG]---*/


    	foreach($departments as $department) {

    		foreach($degrees as $degree) {

 				foreach($grades  as $grade) {

 					$groups = DB::table("studentAnnuals")
			        	->where([

			        			['academic_year_id', 2017],
			        			['department_id', $department],
			        			['degree_id', $degree],
			        			['grade_id', $grade]
			        		])
			        	->select('group')
			        	->groupBy('group')
			        	->get();

			    	if($groups) {

			    		foreach($groups as $group) {

				        	$input = [
				        		'semester_id' => 1,
				        		'code' => $group->group,
				        		'name_en' => '',
				        		'name_kh' => '',
				        		'name_fr' => ''
				        	];

				        		$check = DB::table('groups')->where('code', $group->group)->get();

				        	if(count($check) > 0) {

				        	} else {
				        		DB::table('groups')->insert($input);	
				        	}
			        
			        	}
			    	}
			        
 				}
    		}
    	}

    }
}
