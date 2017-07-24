<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class CompetencyType extends Model
{
    
	public $table = "competency_types";
    

	public $fillable = [
	    "name",
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
