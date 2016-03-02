<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class StudentMinistry extends Model
{
    
	public $table = "studentMinistries";
    

	public $fillable = [
	    "name_kh",
		"name_latin",
		"ministry_id"
	];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name_kh" => "string",
		"name_latin" => "string",
		"ministry_id" => "integer"
    ];

	public static $rules = [
	    
	];

}
