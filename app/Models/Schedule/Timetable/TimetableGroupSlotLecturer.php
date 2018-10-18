<?php

namespace App\Models\Schedule\Timetable;

use Illuminate\Database\Eloquent\Model;

class TimetableGroupSlotLecturer extends Model
{
    protected $fillable = [
        'timetable_group_slot_id',
        'lecturer_id'
    ];
}
