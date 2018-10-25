<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class CandidateDepartment extends Model
{
    
	public $table = "candidate_department";
    

	public $fillable = [
	    "candidate_id",
		"department_id",
		"rank",
        "is_success"
	];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

	public static $rules = [
	];

}
