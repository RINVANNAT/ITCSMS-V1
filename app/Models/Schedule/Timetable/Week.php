<?php

namespace App\Models\Schedule\Timetable;

use App\Models\Schedule\Timetable\Traits\Relationship\WeekRelationship;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Week
 * @package App\Models\Schedule\Timetable
 */
class Week extends Model
{
    use WeekRelationship;
}
