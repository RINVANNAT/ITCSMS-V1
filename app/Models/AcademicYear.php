<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Model;

class AcademicYear extends Model
{
    
	public $table = "academicYears";
    

	public $fillable = [
		"id",
	    "name_kh",
		"name_latin",
		"date_start",
		"date_end",
		"description",
        "create_uid",
        "write_uid"
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

    public function student_annuals(){
        return $this->hasMany('App\Models\StudentAnnual');
    }
    public function scholarship_annuals(){
        return $this->hasMany('App\Models\ScholarshipAnnual');
    }
	public function candidates(){
		return $this->hasMany('App\Models\Candidate');
	}
	public function exams(){
		return $this->hasMany('App\Models\Exam');
	}
    public function creator(){
        return $this->belongsTo('App\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\User','write_uid');
    }

	public function scopeLastestAcademicYear($query){
		$query->orderBy('code','desc')->first();
	}

}
