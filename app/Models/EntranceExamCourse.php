<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class EntranceExamCourse extends Model
{
    
	public $table = "entranceExamCourses";
    

	public $fillable = [
	    "name_kh",
		"name_en",
        "name_fr",
        "description",
        "total_question",
        "create_uid",
        "write_uid",
        "exam_id"
	];

    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }

    public function lastModifier(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
    }

    public function exam(){
        return $this->belongsTo('App\Models\Exam','exam_id');
    }

}
