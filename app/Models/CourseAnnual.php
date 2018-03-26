<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class CourseAnnual extends Model
{

	public $table = "course_annuals";
    

	public $fillable = [
		"academic_year_id",
		"employee_id",
		"course_id",
		"semester_id",
        "create_uid",
        "write_uid",
        "degree_id",
        "grade_id",
        "department_id",
        "is_allow_scoring",
        "responsible_department_id",
        "department_option_id",
        "is_counted_creditablility",
        "is_counted_absence",
        "competency_type_id",
        "normal_scoring"
	];

    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
    }

	public function academic_year(){
		return $this->belongsTo('App\Models\AcademicYear');
	}
	public function course(){
		return $this->belongsTo('App\Models\Course');
	}

    public function course_group(){
        return $this->hasMany('App\Models\CourseAnnualClass');
    }

    public function courseAnnualClass(){
        return $this->hasMany('App\Models\CourseAnnualClass');
    }

    public function groups(){
        return $this->hasMany('App\Models\CourseGroup');
    }

	public function exams(){
		return $this->belongsToMany('App\Models\Exam');
	}

    public function employee(){
        return $this->belongsTo('App\Models\Employee');
    }

    /**
     * Need to improve performance of exporting course session timetable.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courseSessions () {
        return $this->hasMany(CourseSession::class);
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

	public function reference_course()
    {
        return $this->belongsTo(Course::class, 'reference_course_id');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

	public static $rules = [
		"academic_year_id" => "Required|numeric",
		"employee_id"=>"Required|numeric",
		"course_id"=>"Required|numeric",
		"semester_id"=>"Required",
	];

}
