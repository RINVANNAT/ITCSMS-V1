<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class DepartmentOption extends Model
{
    
	public $table = "departmentOptions";
    

	public $fillable = [
	    "name_kh",
		"name_en",
		"name_fr",
		"department_id",
		"code",
        "create_uid",
        "write_uid"
	];

    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
    }

	public function department(){
		return $this->belongsTo('App\Models\Department');
	}

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name_kh" => "string",
		"name_en" => "string",
		"name_fr" => "string",
		"code" => "string"
    ];

	public static $rules = [
	    "name_kh" => "Required",
		"name_en" => "Required",
		"name_fr" => "Required",
		"code" => "Required"
	];

}
