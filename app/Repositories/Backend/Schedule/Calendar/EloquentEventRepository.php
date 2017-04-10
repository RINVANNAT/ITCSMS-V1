<?php

namespace App\Repositories\Backend\Schedule\Calendar;

use App\Http\Requests\Backend\Schedule\Calendar\CreateEventRequest;

/**
 * Class EloquentEventRepository
 * @package App\Repositories\Backend\Schedule\Calendar
 */
class EloquentEventRepository implements EventRepositoryContract
{

    /**
     * Create a new event.
     *
     * @param CreateEventRequest $request
     * @return mixed
     */
    public function createEvent(CreateEventRequest $request)
    {
        // TODO: Implement store() method.
    }
}
