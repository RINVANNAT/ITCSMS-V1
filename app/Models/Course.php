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
	    "name_kh" => "Required",
		"name_en" => "Required",
		"name_fr" => "Required",
		"time_tp" => "Required",
		"time_td" => "Required",
		"time_course" => "Required"
	];

}
