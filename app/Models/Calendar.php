<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Calendar extends Model
{
    
	public $table = "calendars";
    

	public $fillable = [
	    "event",
		"start",
		"stop"
	];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "event" => "string"
    ];

	public static $rules = [
	    
	];

}
