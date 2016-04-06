<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Grade extends Model
{

	public $table = "grades";
    

	public $fillable = [
	    "name_kh",
		"name_en",
		"name_fr",
		"code",
		"description",
        "create_uid",
        "write_uid"
	];

    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
    }

    public function student_annuals(){
        return $this->hasMany('App\Models\StudentAnnual');
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
		"description" => "string"
    ];

	public static $rules = [
	    "name_kh" => "Required",
		"name_en" => "Required",
		"name_fr" => "Required",
		"code" => "Required"
	];

}
