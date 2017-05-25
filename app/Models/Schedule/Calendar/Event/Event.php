<?php

namespace App\Models\Schedule\Calendar\Event;

use App\Models\Schedule\Calendar\Event\Traits\Relationship\EventRelationship;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use EventRelationship;
}
