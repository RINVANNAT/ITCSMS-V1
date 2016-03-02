<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Score extends Model
{
    
	public $table = "scores";
    

	public $fillable = [
	    "score10",
		"score30",
		"score60",
		"reexam",
		"department_id",
        "degree_id",
        "grade_id",
        "academic_year_id",
        "student_annual_id",
		"course_annual_id",
		"create_uid",
        "write_uid",
		"semester_id"
	];

	public function degree(){
		return $this->belongsTo('App\Models\Degree');
	}
	public function department(){
		return $this->belongsTo('App\Models\Department');
	}
	public function grade(){
		return $this->belongsTo('App\Models\Grade');
	}


	/**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];

	public static $rules = [
	    "score10" => "int",
		"score30" => "int",
		"score60" => "int"
	];

}
