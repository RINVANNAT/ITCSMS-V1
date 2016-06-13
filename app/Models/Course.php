<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Course extends Model
{
    
	public $table = "courses";
    

	public $fillable = [
	    "name_kh",
		"name_en",
		"name_fr",
		"code",
		"time_tp",
		"time_td",
		"time_course",
		"credit",
		"degree_id",
		"grade_id",
		"department_id",
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
		"time_tp" => "integer",
		"time_td" => "integer",
		"time_course" => "integer",
		"credit" => "float"
		
    ];

	public static $rules = [
		"name_en" => "Required",
		"name_fr" => "Required",
		"time_tp" => "Required|numeric",
		"time_td" => "Required|numeric",
		"time_course" => "Required|numeric",
		"degree_id"=>"Required|numeric",
		"grade_id"=>"Required|numeric",
		"department_id"=>"Required|numeric",
		"semester_id"=>"Required|numeric",
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
	

}
