<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomeType extends Model
{
    use SoftDeletes;
	protected $dates = ['deleted_at'];
	public $table = "incomeTypes";


	public $fillable = [
	    "name",
		"description",
		"create_uid",
		"write_uid"
	];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name" => "string",
		"description" => "string"
    ];

	public static $rules = [
		'name' => 'required|max:100',
		'description' => 'max:255'
	];

}
