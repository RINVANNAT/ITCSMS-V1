<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Employee extends Model
{
    
	public $table = "employees";
    

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

    public function creator(){
        return $this->belongsTo('App\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\User','write_uid');
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
        return $this->hasOne('App\User','user_id');
    }
	public function groups(){
		return $this->belongsToMany('App\Models\Group')->withTimestamps();
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
