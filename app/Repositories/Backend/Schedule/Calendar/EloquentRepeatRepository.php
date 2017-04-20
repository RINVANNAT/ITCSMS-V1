<?php

namespace App\Repositories\Backend\Schedule\Calendar;


use App\Models\Schedule\Calendar\Event\Event;
use App\Models\Schedule\Calendar\Repeat\Repeat;
use App\Models\Schedule\Calendar\Year\EventYear;
use App\Models\Schedule\Calendar\Year\Year;
use Carbon\Carbon;

/**
 * Class EloquentRepeatRepository
 * @package App\Repositories\Backend\Schedule\Calendar
 */
class EloquentRepeatRepository implements RepeatRepositoryContract
{

    /**
     * Find repeat object by id.
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return Repeat::find($id);
    }

    /**
     * Set new start and end to the object event.
     *
     * @param Event $event
     * @param Year $year
     * @return mixed
     */
    public function copiedObjectRepeatEvent(Event $event, Year $year)
    {
        $objRepeat = $this->find($event->repeat_id);

        $start = new Carbon($objRepeat->start);
        $end = new Carbon($objRepeat->end);
        $start->year($year->name);
        $end->year($year->name);

        $newEvent = new EventYear();

        $newEvent->event_id = $event->id;
        $newEvent->year_id = $year->id;
        $newEvent->start = $start;
        $newEvent->end = $end;
        $newEvent->created_uid = auth()->user()->id;
        $newEvent->updated_uid = auth()->user()->id;

        $newEvent->save();
    }
}