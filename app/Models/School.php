<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class School extends Model
{
    
	public $table = "schools";
    

	public $fillable = [
		"id",
	    "name_kh",
		"name_en",
		"name_fr",
		"code",
		"language",
        "create_uid",
        "write_uid"
	];

    public function departments(){
        return $this->hasMany('App\Models\Department');
    }

    public function degrees(){
        return $this->hasMany('App\Models\Degree');
    }

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
		"code" => "string"
    ];

	public static $rules = [
	    "name_kh" => "Required",
		"name_en" => "Required",
		"name_fr" => "Required",
		"code" => "Required"
	];

}
