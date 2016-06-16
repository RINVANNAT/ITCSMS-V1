<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class CourseAnnual extends Model
{

	public $table = "course_annuals";
    

	public $fillable = [
	    "name",
		"department_id",
		"degree_id",
		"grade_id",
		"academic_year_id",
		"employee_id",
		"course_id",
		"semester_id",
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
	public function exams(){
		return $this->belongsToMany('App\Models\Exam');
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
		"department_id" => "Required",
		"degree_id" => "Required|numeric",
		"grade_id" => "Required|numeric",
		"academic_year_id" => "Required|numeric",
		"employee_id"=>"Required|numeric",
		"course_id"=>"Required|numeric",
		"semester_id"=>"Required|numeric",
	];

}
