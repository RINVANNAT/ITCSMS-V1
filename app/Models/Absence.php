<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Absence extends Model
{
    
	public $table = "absences";
    

	public $fillable = [
	    "degree_id",
		"grade_id",
		"department_id",
		"academic_year_id",
		"semester_id",
		"course_annual_id",
		"student_annual_id",
		"absence_on"
	];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];

	public static $rules = [
	    "degree_id" => "required|numeric",
		"grade_id" => "required|numeric",
		"department_id" => "required|numeric",
		"academic_year_id" => "required|numeric",
		"semester_id" => "required|numeric",
		"course_annual_id" => "required|numeric",
		"student_annual_id" => "required|numeric",
	];

}
