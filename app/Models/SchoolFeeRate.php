<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class SchoolFeeRate extends Model
{
    
	public $table = "schoolFeeRates";


    public $fillable = [
        "scholarship_id",
        "to_pay",
        "to_pay_currency",
        "degree_id",
        "promotion_id",
        "academic_year_id",
        "create_uid"
    ];

    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
    }
    public function degree(){
        return $this->belongsTo('App\Models\Degree');
    }
    public function departments(){
        return $this->belongsToMany('App\Models\Department');
    }
    public function grades(){
        return $this->belongsToMany('App\Models\Grade');
    }

    public function promotion(){
        return $this->belongsTo('App\Models\Promotion');
    }

    public function academic_year(){
        return $this->belongsTo('App\Models\AcademicYear');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "male_fee" => "integer",
		"female_fee" => "integer",
		"sport_fee" => "integer"
    ];

	public static $rules = [
	    "to_pay" => "Required",
		"to_pay_currency" => "Required",
		"promotion_id" => "Required",
        "degree_id" => "Required"
	];

}
