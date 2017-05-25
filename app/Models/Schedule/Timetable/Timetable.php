<?php

namespace App\Models\Schedule\Timetable;

use App\Models\Schedule\Timetable\Traits\Relationship\TimetableRelationship;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Timetable
 * @package App\Models\Schedule\Timetable
 */
class Timetable extends Model
{
    use TimetableRelationship;
}
