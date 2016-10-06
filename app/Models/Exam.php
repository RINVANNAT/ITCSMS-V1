<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Model;

class Exam extends Model
{
    
	public $table = "exams";
    

	public $fillable = [
	    "name",
		"date_start",
		"date_end",
        "description",
        "create_uid",
        "type_id",
        "write_uid",
        "academic_year_id"
	];

    public function setDateStartAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/Y', $value);
        $this->attributes['date_start'] = $date->format('Y/m/d');
    }

    public function setDateEndAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/Y', $value);
        $this->attributes['date_end'] = $date->format('Y/m/d');
    }

    protected $dates = ['date_start','date_end','success_registration_start','success_registration_stop','reserve_registration_start','reserve_registration_stop'];

    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
    }

    public function type(){
        return $this->belongsTo('App\Models\ExamType','type_id');
    }

    public function academicYear(){
        return $this->belongsTo('App\Models\AcademicYear');
    }

    public function rooms(){
        return $this->hasMany('App\Models\ExamRoom');
    }

    public function employees(){
        return $this->belongsToMany('App\Models\Employee');
    }

    public function entranceExamCourses(){
        return $this->hasMany('App\Models\EntranceExamCourse');
    }

//....the relation of temporary employee

    public function rolePerminentStaffExam(){
        return $this->hasMany('App\Models\role_permanent_staff_exams');
    }

    public function roleTemperorayStaffExam(){
        return $this->hasMany('App\Models\role_temporary_staff_exams');
    }

    public function tempEmployees(){
        return $this->belongsToMany('App\Models\TempEmployee');
    }

//.....this is the end 


    public function students(){
        return $this->belongsToMany('App\Models\StudentAnnual');
    }

    public function courses(){
        return $this->belongsToMany('App\Models\CourseAnnual');
    }

    public function candidates(){
        return $this->hasMany('App\Models\Candidate');
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
