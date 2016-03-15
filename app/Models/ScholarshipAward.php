<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class ScholarshipAward extends Model
{
    
	public $table = "scholarshipAwards";


    public $fillable = [
        "scholarship_id",
        "award",
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

    public function promotion(){
        return $this->belongsTo('App\Models\Promotion');
    }

    public function academic_year(){
        return $this->belongsTo('App\Models\AcademicYear');
    }

}
