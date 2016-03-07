<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Support\Facades\Config;

class Scholarship extends Model
{
    
	public $table = "scholarships";
    protected $dates = ['start', 'stop'];

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

    public function setStartAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/Y', $value);
        $this->attributes['start'] = $date->format('Y/m/d');
    }

    public function setStopAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/Y', $value);
        $this->attributes['stop'] = $date->format('Y/m/d');
    }

    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
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
