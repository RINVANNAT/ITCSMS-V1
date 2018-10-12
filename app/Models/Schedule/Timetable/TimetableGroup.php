<?php

namespace App\Models\Schedule\Timetable;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TimetableGroup
 * @package App\Models\Schedule\Timetable
 */
class TimetableGroup extends Model
{
    protected $table='timetable_groups';

    protected $fillable = [
        'code',
        'description'
    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
    }




    public function timetableSlots ()
    {
        return $this->hasMany(TimetableSlot::class);
    }
}