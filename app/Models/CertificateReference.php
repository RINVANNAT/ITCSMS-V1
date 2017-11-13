<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class CertificateReference extends Model
{
    
	public $table = "certificate_references";
    

	protected $guarded = [
	    "id"
	];

    public function student(){
        return $this->belongsTo('App\Models\StudentAnnual','student_annual_id');
    }
    public function course(){
        return $this->belongsTo('App\Models\CourseAnnual','course_annual_id');
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
        "ref_number" => "string"
    ];

	public static $rules = [
	    "ref_number" => "Required"
	];

}
