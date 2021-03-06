<?php namespace App\Models;

use App\Models\Schedule\Timetable\TimetableSlot;
use Illuminate\Database\Eloquent\Model as Model;

class Room extends Model
{

    public $table = "rooms";


    public $fillable = [
        "name",
        "create_ui",
        "building_id",
        "write_uid",
        "department_id",
        "room_type_id",
        "capacity"
    ];

    public function creator()
    {
        return $this->belongsTo('App\Models\Access\User', 'create_uid');
    }

    public function lastModifier()
    {
        return $this->belongsTo('App\Models\Access\User', 'write_uid');
    }

    public function department()
    {
        return $this->belongsTo('App\Models\Department');
    }

    public function building()
    {
        return $this->belongsTo('App\Models\Building');
    }

    public function roomType()
    {
        return $this->belongsTo('App\Models\RoomType');
    }

    public function timetableSlot()
    {
        return $this->belongsTo(TimetableSlot::class);
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
        "name" => "Required"
    ];

}
