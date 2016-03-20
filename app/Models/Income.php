<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class Income extends Model
{
    
	public $table = "incomes";
    

	public $fillable = [
	    "amount_dollar",
		"amount_riel",
		"amount_kh",
		"number",
		"is_printed",
		"pay_date",
		"create_uid",
		"write_uid",
		"payslip_client_id",
		"income_type_id",
		"account_id"
	];

	public function incomeType(){
		return $this->belongsTo('App\Models\IncomeType');
	}

	public function payslipClient(){
		return $this->belongsTo('App\Models\PayslipClient');
	}

	public function account(){
		return $this->belongsTo('App\Models\Account');
	}

	public function creator(){
		return $this->belongsTo('App\Models\Access\User\User','create_uid');
	}
	public function lastModifier(){
		return $this->belongsTo('App\Models\Access\User\User','write_uid');
	}

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
