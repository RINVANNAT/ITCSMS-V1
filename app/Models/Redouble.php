<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Redouble extends Model
{
    
	public $table = "redoubles";
    

	public $fillable = [
	    "name_en",
		"name_kh",
		"name_fr"
	];

	public function creator(){
		return $this->belongsTo('App\User','create_uid');
	}
	public function lastModifier(){
		return $this->belongsTo('App\User','write_uid');
	}

    public function students(){
        return $this->belongsToMany('App\Models\Student');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name_en" => "string",
		"name_kh" => "string",
		"name_fr" => "string"
    ];

	public static $rules = [
	    "name_en" => "Required"
	];

}
