<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Model;

class Student extends Model
{
    protected $dates = ['dob'];

	public $fillable = [
		"id",
		"id_card",
		"mcs_no",
		"can_id",
		"name_latin",
		"name_kh",
		"dob",
		"photo",
		"radie",
		"observation",
		"phone",
		"email",
		"admission_date",
		"address",
		"address_current",
		'parent_name',
		'parent_occupation',
		'parent_address',
		'parent_phone',
		'pob',
		'gender_id',
		'high_school_id',
		'origin_id',
		'candidate_id',
		"create_uid",
		"created_at",
		"write_uid",
        "full_name_latin"
	];

	public $table = "students";

    protected $appends = [
        'full_name_latin'
    ];

    public function getFullNameLatinAttribute ()
    {
        return strtoupper($this->name_latin);
    }

	public $searchable = [
		"name_latin",
		"full_name_latin",
		"name_kh",
	];

    public function setDobAttribute($value)
    {

        $date = Carbon::createFromFormat('d/m/Y', $value);
        $this->attributes['dob'] = $date->format('Y/m/d');
    }

    public function studentAnnuals(){
        return $this->hasMany('App\Models\StudentAnnual');
    }
    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
    }
	public function gender(){
		return $this->belongsTo('App\Models\Gender');
	}
	public function high_school(){
		return $this->belongsTo('App\Models\HighSchool');
	}
	public function origin(){
		return $this->belongsTo('App\Models\Origin',"origin_id");
	}
	public function pob(){
		return $this->belongsTo('App\Models\Origin',"pob");
	}
	public function academic_year(){
		return $this->belongsTo('App\Models\AcademicYear');
	}
	public function candidate(){
		return $this->belongsTo('App\Models\Candidate');
	}
	public function evalStatus()
	{
		return $this->hasMany('App\Models\StudentEvalStatu');
	}
    public function redoubles(){
        return $this->belongsToMany('App\Models\Redouble',"redouble_student");
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name_latin" => "string",
		"name_kh" => "string",
		"photo" => "string",
		"radie" => "boolean",
		"observation" => "string",
		"phone" => "string",
		"email" => "string",
		"id_card" => "string",
		"address" => "string",
		"address_current" => "string",
		"mcs_no" => "integer"
    ];

	public static $rules = [
	    "name_latin" => "Required",
		"name_kh" => "Required",
		"gender_id" => "Required",
		"id_card" => "required|unique:students"
		//"photo" => 'required|mimes:png,jpg,jpeg'
	];

}
