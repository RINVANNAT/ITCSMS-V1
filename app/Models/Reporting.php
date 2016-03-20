<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Reporting extends Model
{
    
	public $table = "reporting";
    

	public $fillable = [
	    "title",
        "description",
		"create_uid",
		"write_uid"
	];

	public function creator(){
		return $this->belongsTo('App\Models\Access\User\User','create_uid');
	}
	public function lastModifier(){
		return $this->belongsTo('App\Models\Access\User\User','write_uid');
	}

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

	public static $rules = [
	];

}
