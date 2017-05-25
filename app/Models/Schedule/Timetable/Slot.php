<?php

namespace App\Models\Schedule\Timetable;

use App\Models\Schedule\Timetable\Traits\Relationship\SlotRelationship;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Slot
 * @package App\Models\Schedule\Timetable
 */
class Slot extends Model
{
    use SlotRelationship;
}
