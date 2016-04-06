<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class CourseAnnual extends Model
{

	public $table = "courseAnnuals";
    

	public $fillable = [
	    "name",
		"department_id",
		"degree_id",
		"grade_id",
		"academic_year_id",
		"course_id",
		"semester",
        "create_uid",
        "write_uid"
	];

    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
    }


	public function department(){
		return $this->belongsTo('App\Models\Department');
	}
	public function degree(){
		return $this->belongsTo('App\Models\Degree');
	}

	public function grade(){
		return $this->belongsTo('App\Models\Grade');
	}

	public function academic_year(){
		return $this->belongsTo('App\Models\AcademicYear');
	}
	public function course(){
		return $this->belongsTo('App\Models\Course');
	}



    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name" => "string"
    ];

	public static $rules = [

	];

}
