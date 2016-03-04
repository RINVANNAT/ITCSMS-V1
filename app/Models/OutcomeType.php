<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;


class OutcomeType extends Model
{
	public $table = "outcomeTypes";
    

	public $fillable = [
	    "code",
		"origin",
		"name",
		"description",
		"create_uid",
		"write_uid"
	];

	public function getCodeNameAttribute(){
		return $this->code.' | '.$this->name;
	}

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "code" => "string",
		"origin" => "string",
		"name" => "string",
		"description" => "string"
    ];

	public static $rules = [
		'code' => 'required|max:4',
		'origin' => 'max:10',
		'name' => 'required|max:100',
		'description' => 'max:255'
	];

}
