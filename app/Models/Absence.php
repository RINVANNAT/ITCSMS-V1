<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Absence extends Model
{
    
	public $table = "absences";
    

	public $fillable = [
		"course_annual_id",
		"student_annual_id",
		"num_absence",
		"create_uid"
	];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];

	public static $rules = [
		"course_annual_id" => "required|numeric",
		"student_annual_id" => "required|numeric",
		"num_absence" => "required|numeric"
	];

}
