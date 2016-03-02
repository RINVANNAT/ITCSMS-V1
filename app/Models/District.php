<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class District extends Model
{
    
	public $table = "districts";
    

	public $fillable = [
	    "name_kh",
		"name_en"
	];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name_kh" => "string",
		"name_en" => "string"
    ];

	public static $rules = [
	    "name_kh" => "required"
	];

}
