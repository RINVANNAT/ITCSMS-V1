<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Commune extends Model
{
    
	public $table = "communes";
    

	public $fillable = [
	    "name_en",
		"name_en",
		"district_id"
	];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [„
        "name_en" => "string",
		"name_en" => "string",
		"district_id" => "integer"
    ];

	public static $rules = [
	    "name_en" => "required"
	];

}
