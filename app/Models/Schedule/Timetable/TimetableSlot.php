<?php

namespace App\Models\Schedule\Timetable;

use App\Models\Schedule\Timetable\Traits\Relationship\TimetableSlotRelationship;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TimetableSlot
 * @package App\Models\Schedule\Timetable
 */
class TimetableSlot extends Model
{
    use TimetableSlotRelationship;
}
