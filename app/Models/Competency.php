<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Competency extends Model
{
    
	public $table = "competencies";
    

	public $fillable = [
	    "name",
        "competency_type_id",
        "properties",
        "type",
        "calculation_rule",
        "condition_rule",
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
