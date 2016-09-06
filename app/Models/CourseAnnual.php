<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class CourseAnnual extends Model
{

	public $table = "course_annuals";
    

	public $fillable = [
		"department_id",
		"degree_id",
		"grade_id",
		"academic_year_id",
		"employee_id",
		"course_id",
		"semester_id",
        "create_uid",
		"score_percentage_column_1",
		"score_percentage_column_2",
		"score_percentage_column_3",
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

	public function isScoreRuleChange(){


		if ( $this->score_percentage_column_1 == 10 and
			$this->score_percentage_column_2 == 30 and
			$this->score_percentage_column_3 == 60 ){


			$test = false;
		}else {
			$test  = true;
		}

       

		return $test;
	}


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

	public static $rules = [
		"department_id" => "Required",
		"degree_id" => "Required|numeric",
		"grade_id" => "Required|numeric",
		"academic_year_id" => "Required|numeric",
		"employee_id"=>"Required|numeric",
		"course_id"=>"Required|numeric",
		"semester_id"=>"Required",
	];

}
