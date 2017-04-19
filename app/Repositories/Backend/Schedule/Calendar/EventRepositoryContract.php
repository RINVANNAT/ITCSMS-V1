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

    /**
     * Find event by Id.
     *
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Check event that match with the year.
     *
     * @param $event_id
     * @param $year_id
     * @return bool
     */
    public function objectEventExisted($event_id, $year_id);

    /**
     * Find all events by year.
     *
     * @param $yearId
     * @return mixed
     */
    public function findEventsByYear($yearId);
}
