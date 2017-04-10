<?php

namespace App\Repositories\Backend\Schedule\Calendar;

use App\Http\Requests\Backend\Schedule\Calendar\CreateEventRequest;

/**
 * Interface EventRepositoryContract
 * @package App\Repositories\Backend\Schedule\Calendar
 */
interface EventRepositoryContract
{
    /**
     * Create a new event.
     *
     * @param CreateEventRequest $request
     * @return mixed
     */
    public function createEvent(CreateEventRequest $request);
}
