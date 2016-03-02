<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Support\Facades\Config;

class Scholarship extends Model
{
    
	public $table = "scholarships";
    

	public $fillable = [
	    "name_en",
		"name_fr",
		"name_kh",
		"code",
		"budget",
        "duration",
        "isDroppedUponFail",
        "founder",
        "start",
        "stop",
        "create_uid",
	];


    public function creator(){
        return $this->belongsTo('App\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\User','write_uid');
    }
    public function student_annuals(){
        return $this->belongsToMany('App\Models\StudentAnnual');
    }


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "code" => "string",
    ];

	public static $rules = [
	    "code" => "required",
	];

}
