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
        'description',
        'parent_id'
    ];

    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub
    }

    public function timetableSlots ()
    {
        return $this->belongsToMany(TimetableSlot::class, 'timetable_group_sessions', 'timetable_group_id', 'timetable_slot_id');
    }

    public function parent () {
        return $this->belongsTo(TimetableGroup::class);
    }
}
