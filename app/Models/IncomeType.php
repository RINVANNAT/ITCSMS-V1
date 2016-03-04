<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class IncomeType extends Model
{

	public $table = "incomeTypes";


	public $fillable = [
	    "name",
		"description",
		"create_uid",
		"write_uid"
	];


}
