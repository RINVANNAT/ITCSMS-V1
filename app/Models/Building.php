<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Building extends Model
{
    
	public $table = "buildings";
    

	public $fillable = [
	    "name",
		"create_uid",
		"write_uid"
	];

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
        "name" => "string"
    ];

	public static $rules = [
	    "name" => "Required"
	];

}
