<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Customer extends Model
{
    
	public $table = "customers";
    

	public $fillable = [
	    "name",
		"address",
		"phone",
		"email",
		"company",
		"identity_number"
	];

	public function payslipClient(){
		return $this->belongsTo('App\Models\PayslipClient');
	}
	public function creator(){
		return $this->belongsTo('App\User','create_uid');
	}
	public function lastModifier(){
		return $this->belongsTo('App\User','write_uid');
	}


    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name" => "string",
		"address" => "string",
		"phone" => "string",
		"email" => "string",
		"company" => "string",
		"identity_number" => "string"
    ];

	public static $rules = [
	    
	];

}
