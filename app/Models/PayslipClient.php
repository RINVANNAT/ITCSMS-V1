<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class PayslipClient extends Model
{
    
	public $table = "payslipClients";
    

	public $fillable = [
	    "type"
	];

	public function incomes(){
		return $this->hasMany('App\Models\Income');
	}
	public function outcomes(){
		return $this->hasMany('App\Models\Outcome');
	}
	public function customer(){
		return $this->hasOne('App\Models\Customer');
	}
	public function candidate(){
		return $this->hasOne('App\Models\Candidate');
	}
	public function student(){
		return $this->hasOne('App\Models\StudentAnnual');
	}
	public function employee(){
		return $this->hasOne('App\Models\Employee');
	}
	public function creator(){
		return $this->belongsTo('App\Models\Access\User','create_uid');
	}
	public function lastModifier(){
		return $this->belongsTo('App\Models\Access\User','write_uid');
	}

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "type" => "string"
    ];

	public static $rules = [
	    
	];

}
