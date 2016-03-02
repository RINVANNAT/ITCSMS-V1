<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class StudentEvalStatus extends Model
{
    
	public $table = "studentEvalStatuses";
    

	public $fillable = [
	    "name"
	];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name" => "string"
    ];

	public static $rules = [
	    "name" => "string"
	];



}
