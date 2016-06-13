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

    protected $dates = ['date_start','date_end'];

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
        return $this->belongsToMany('App\Models\Room');
    }

    public function employees(){
        return $this->belongsToMany('App\Models\Employee');
    }

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
