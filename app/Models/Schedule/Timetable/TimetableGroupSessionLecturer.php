<?php

namespace App\Models\Schedule\Timetable;

use Illuminate\Database\Eloquent\Model;

class TimetableGroupSessionLecturer extends Model
{
    protected $fillable = [
        'timetable_group_session_id',
        'lecturer_id'
    ];
}
