<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Account extends Model
{
    
	public $table = "accounts";
    

	public $fillable = [
	    "name",
		"active",
		"description"
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
	    
	];

}
