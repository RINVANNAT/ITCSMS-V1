<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class PrintedTranscript extends Model
{
    
	public $table = "printed_transcripts";
    

	public $fillable = [
	    "type",
        "academic_year_id",
        "student_annual_id",
		"create_uid",
		"write_uid",
        "created_at",
        "updated_at"
	];

	public function creator(){
		return $this->belongsTo('App\Models\Access\User','create_uid');
	}
	public function lastModifier(){
		return $this->belongsTo('App\Models\Access\User','write_uid');
	}
    public function student(){
        return $this->belongsTo('App\Models\StudentAnnual','student_annual_id');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */

	public static $rules = [
	    "type" => "Required",
        "student_annual_id" => "Required"
	];

}
