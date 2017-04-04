<?php

namespace App\Repositories\Backend\Schedule;

use Illuminate\Support\Facades\DB;

/**
 * Class EloquentEventRepository
 * @package App\Repositories\Backend\Schedule
 */
class EloquentEventRepository implements EventRepositoryContract
{

    public function getAllObjectEvents()
    {
        $events = DB::table('event_year')
            ->join('events', 'events.id', '=', 'event_year.event_id')
            ->join('category_events', 'category_events.id', '=', 'events.category_event_id')
            ->join('years', 'years.id', '=', 'event_year.year_id')
            ->select('event_year.id', 'category_events.className', 'events.title', 'events.allDay', 'event_year.start', 'event_year.end', 'events.study')
            ->get();
        return $events;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getEventsByYear($id)
    {
        $events = DB::table('events')
            ->whereNotIn('id', function ($query) use ($id) {
                $query->select('event_year.event_id')
                    ->from('event_year')
                    ->where('event_year.year_id', $id);
            })->get();

        return $events;
    }
}
