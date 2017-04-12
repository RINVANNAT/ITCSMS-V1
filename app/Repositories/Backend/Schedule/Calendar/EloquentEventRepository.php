<?php

namespace App\Repositories\Backend\Schedule\Calendar;

use App\Http\Requests\Backend\Schedule\Calendar\CreateEventRequest;
use App\Models\Schedule\Calendar\Event\Event;
use App\Models\Schedule\Calendar\Repeat\Repeat;

/**
 * Class EloquentEventRepository
 * @package App\Repositories\Backend\Schedule\Calendar
 */
class EloquentEventRepository implements EventRepositoryContract
{
    /**
     * @param CreateEventRequest $request
     * @return mixed
     */
    public function createEvent(CreateEventRequest $request)
    {
        // Initialize repeat_id variable.
        $repeat_id = null;
        // Check the event is fix or not.
        if ($request->fix == 'true') {
            // Create repeat instance
            $newRepeat = new Repeat();
            $newRepeat->start = $request->start;
            $newRepeat->end = $request->end;
            if ($newRepeat->save()) {
                $repeat_id = $newRepeat->id;
            }
        }
        // Create new instance event.
        $newEvent = new Event();
        $newEvent->title = $request->title;
        // @TODO Remove description field.
        $newEvent->description = "You can write description here.";
        $newEvent->created_uid = auth()->user()->id;
        $newEvent->updated_uid = auth()->user()->id;
        $newEvent->public = $request->public;

        if ($request->study == 'true') {
            $newEvent->study = true;
        } else {
            $newEvent->study = false;
        }

        if ($repeat_id != null) {
            $newEvent->repeat_id = $repeat_id;
        }

        if ($newEvent->save()) {
            if ($request->public == 'false') {
                $newEvent->departments()->attach($request->departments);
            }
            return true;
        } else {
            return false;
        }
    }
}
