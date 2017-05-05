<?php

namespace App\Repositories\Backend\Schedule\Calendar;

use App\Models\Schedule\Calendar\Event\Event;
use App\Models\Schedule\Calendar\Year\Year;

/**
 * Interface RepeatRepositoryContract
 * @package App\Repositories\Backend\Schedule\Calendar
 */
interface RepeatRepositoryContract
{
    /**
     * Find repeat object by id.
     *
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Set new start and end to the object event.
     *
     * @param Event $event
     * @param Year $year
     * @return mixed
     */
    public function copiedObjectRepeatEvent(Event $event, Year $year);
}