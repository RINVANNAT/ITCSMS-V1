<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Model;

class Employee extends Model
{
    
	public $table = "employees";

    //protected $dates = ['birthdate'];
    public $fillable = [
	    "name_kh",
		"name_latin",
		"birthdate",
		"address",
        "email",
        "phone",
        "create_uid",
        "write_uid",
        "department_id",
        "user_id"
	];

    public function setBirthdateAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/Y', $value);
        $this->attributes['birthdate'] = $date->format('Y/m/d');
    }

    public function getBirthdateAttribute(){
        $date = Carbon::createFromFormat('Y-m-d h:i:s', $this->attributes['birthdate']);

        return $date->format('m/d/Y');
    }


    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
    }
	public function gender(){
		return $this->belongsTo('App\Models\Gender');
	}
    public function department(){
        return $this->belongsTo('App\Models\Department');
    }
	public function payslipClient(){
		return $this->belongsTo('App\Models\PayslipClient');
	}
    public function user(){
        return $this->hasOne('App\Models\Access\User','user_id');
    }
    public function roles()
    {
        return $this->belongsToMany(config('access.role'));
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name_kh" => "string",
		"name_latin" => "string",
		"address" => "string"
    ];

	public static $rules = [
	    "name_kh" => "Required",
		"name_latin" => "Required",
		"birthdate" => "Required"
	];

}
