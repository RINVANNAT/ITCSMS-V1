<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Promotion extends Model
{
    
	public $table = "promotions";
    

	public $fillable = [
	    "name",
		"observation"
	];

	public function student_annuals(){
		return $this->hasMany('App\Models\StudentAnnual');
	}

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name" => "string",
		"observation" => "string"
    ];

	public static $rules = [
	    
	];

}
