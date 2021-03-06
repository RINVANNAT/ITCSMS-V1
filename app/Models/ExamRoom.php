<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Support\Facades\DB;

class ExamRoom extends Model
{
    
	public $table = "examRooms";
    

	public $fillable = [
	    "nb_chair_exam",
		"roomcode",
        "created_at",
        "updated_at",
        "room_id",
        "exam_id",
        "create_uid",
        "write_uid"
	];

    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
    }
    public function exam(){
        return $this->belongsTo('App\Models\Exam');
    }
    public function building(){
        return $this->belongsTo('App\Models\Building');
    }
    public function department(){
        return $this->belongsTo('App\Models\Department');
    }
    public function room_type(){
        return $this->belongsTo('App\Models\RoomType');
    }

    public function candidates(){
        return $this->hasMany('App\Models\Candidate','room_id');
    }

    public function getCount_candidatesAttribute()
    {
        return $this->hasMany('App\Models\Candidate')->where('room_id',$this->id)->count();
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name" => "string"
    ];

	public static $rules = [
	    
	];

}
