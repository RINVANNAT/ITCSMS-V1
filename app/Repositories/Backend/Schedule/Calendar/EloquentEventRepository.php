<?php

namespace App\Repositories\Backend\Schedule\Calendar;

use App\Http\Requests\Backend\Schedule\Calendar\CreateEventRequest;
use App\Models\Schedule\Calendar\Event\Event;
use App\Models\Schedule\Calendar\Repeat\Repeat;
use App\Models\Schedule\Calendar\Year\Year;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        //d(auth()->user()->id);
        if (!empty($request->dailyYear)) {
            //dd($request->all());

            $newRepeat = new Repeat();

            $newRepeat->start = new Carbon($request->start);
            $newRepeat->end = new Carbon($request->end);

            if ($newRepeat->save()) {
                $newEvent = new Event();

                $newEvent->title = $request->title;
                $newEvent->description = "Typing your description here.";
                $newEvent->repeat_id = $newRepeat->id;
                $newEvent->allDay = false;
                if (empty($request->study)) {
                    $request->study = false;
                }

                if (empty($request->public)) {
                    $request->public = false;
                }

                $newEvent->created_uid = auth()->user()->id;
                $newEvent->updated_uid = auth()->user()->id;

                if ($newEvent->save()) {
                    if ($newEvent->public == 'false') {
                        $newEvent->departments()->attach($request->departments);
                    }

                    $year = Carbon::parse(request('start'))->year;
                    $objYear = Year::where('name', $year)->first();

                    if ($objYear instanceof Year) {
                        $newEvent->years()->save($objYear, [
                            'event_id' => $newEvent->id,
                            'year_id' => $objYear->id,
                            'start' => new Carbon($request->start),
                            'end' => new Carbon($request->end),
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'created_uid' => auth()->user()->id,
                            'updated_uid' => auth()->user()->id
                        ]);
                    }
                    return true;
                }
            }
            return false;
        } else {
            // initialize repeat_id variable.
            $repeat_id = null;
            // check the event is fix or not.
            if ($request->fix == 'true') {
                // Create repeat instance
                $newRepeat = new Repeat();
                $newRepeat->start = $request->start;
                $newRepeat->end = $request->end;
                if ($newRepeat->save()) {
                    $repeat_id = $newRepeat->id;
                }
            }
            // create new instance event.
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

    /**
     * Find event by Id.
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        if (Event::find($id) instanceof Event) {
            return Event::find($id);
        }
        return null;
    }

    /**
     * Check the event is already existed or not yet.
     *
     * @param $event_id
     * @param $year_id
     * @return bool
     */
    public function objectEventExisted($event_id, $year_id)
    {
        $query = DB::table('event_year')->where([
            ['event_id', '=', $event_id],
            ['year_id', '=', $year_id]
        ])->first();

        if (is_object($query)) {
            return (bool)true;
        }
        return (bool)false;
    }

    /**
     * Find all events by year and author.
     *
     * @param $yearId
     * @param $authorId
     * @return mixed
     */
    public function findEventsByYearAndAuthor($yearId, $authorId)
    {
        return DB::table('events')
            ->whereNotIn('id', function ($query) use ($yearId) {
                $query->select('event_year.event_id')
                    ->from('event_year')
                    ->where('event_year.year_id', $yearId);
            })
            ->where([
                ['created_uid', '=', $authorId],
                ['repeat_id', '=', null]
            ])
            ->get();
    }
}
