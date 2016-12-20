<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Configuration extends Model
{
    
	public $table = "configurations";
    

	public $fillable = [
	    "key",
        "value",
		"create_uid",
		"write_uid",
        "created_at",
        "updated_at"
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
        "key" => "string",
        "value" => "string"
    ];

}