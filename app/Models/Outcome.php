<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Outcome extends Model
{
    
	public $table = "outcomes";

	public $fillable = [
		"amount_dollar",
		"amount_dollar_kh",
		"amount_riel",
		"amount_riel_kh",
		"number",
		"is_printed",
		"pay_date",
		"create_uid",
		"write_uid",
		"payslip_client_id",
		"outcome_type_id",
		"account_id",
        "attachment_name"
	];

	public function attachments(){
		return $this->hasMany('App\Models\Attachment');
	}

	public function outcomeType(){
		return $this->belongsTo('App\Models\OutcomeType');
	}

	public function payslipClient(){
		return $this->belongsTo('App\Models\PayslipClient');
	}

	public function account(){
		return $this->belongsTo('App\Models\Account');
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

	];

	public static $rules = [
		"amount_dollar"=>'max:50',
		"amount_dollar_kh"=>'max:255',
		"amount_riel"=>'max:50',
		"amount_riel_kh"=>'max:255',
	];

}
