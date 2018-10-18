<?php

namespace App\Models\Schedule\Timetable;

use Illuminate\Database\Eloquent\Model;

class TimetableGroupSession extends Model
{
    protected $fillable = [
        'timetable_slot_id',
        'timetable_group_id',
        'total_hours',
        'total_hours_remain'
    ];

    public function lecturers ()
    {
        return $this->hasMany(TimetableGroupSlotLecturer::class);
    }
}
