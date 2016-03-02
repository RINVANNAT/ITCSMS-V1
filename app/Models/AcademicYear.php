<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class AcademicYear extends Model
{
    
	public $table = "academicYears";
    

	public $fillable = [
		"id",
	    "name_kh",
		"name_en",
		"name_fr",
		"code",
		"date_start",
		"date_end",
		"description",
        "create_uid",
        "write_uid"
	];

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
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name_kh" => "string",
		"name_en" => "string",
		"name_fr" => "string",
		"code" => "string",
		"description" => "string",
        "create_uid"=>"integer",
        "write_uid"=>"integer"
    ];

	public static $rules = [
	    "name_kh" => "Required",
		"name_en" => "Required",
		"code" => "Required",
		"date_start" => "Required",
		"date_end" => "Required"
	];

	public function scopeLastestAcademicYear($query){
		$query->orderBy('code','desc')->first();
	}

}
