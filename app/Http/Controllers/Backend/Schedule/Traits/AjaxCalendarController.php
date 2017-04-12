<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Department;
use App\Models\Schedule\Calendar\Event\Event;
use App\Models\Schedule\Calendar\Year\Year;
use App\Repositories\Backend\Schedule\Calendar\EloquentEventRepository;
use App\Repositories\Backend\Schedule\Calendar\EloquentYearRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

/**
 * Class AjaxCalendarController
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait AjaxCalendarController
{
    /**
     * @var EloquentYearRepository
     */
    protected $yearRepository;

    /**
     * @var EloquentEventRepository
     */
    protected $eventRepository;

    /**
     * AjaxCalendarController constructor.
     * @param EloquentYearRepository $eloquentYearRepository
     * @param EloquentEventRepository $eloquentEventRepository
     */
    public function __construct(
        EloquentYearRepository $eloquentYearRepository,
        EloquentEventRepository $eloquentEventRepository
    )
    {
        $this->yearRepository = $eloquentYearRepository;
        $this->eventRepository = $eloquentEventRepository;
    }

    /**
     * Get all departments.
     *
     * @return mixed
     */
    public function getDepartments()
    {
        return Response::json([
            'status' => true,
            'data' => Department::all()
        ]);
    }

    /**
     * List all events on side left.
     *
     * @return mixed
     */
    public function listEventsOnSideLeft()
    {
        return Response::json(['status' => true, 'events' => Event::latest()->get()]);
    }

    /**
     * Drag event on full calendar.
     *
     * @return mixed
     */
    public function dragEvent()
    {
        //dd(request()->all());
        $year = Carbon::parse(request('start'))->year;
        $objectYear = $this->yearRepository->findByYear($year);

        if ($objectYear == false) {
            $newYear = new Year();
            $newYear->name = $year;
            if ($newYear->save()) {
                $year_id = $newYear->id;
            }
        } else {
            $year_id = $objectYear->id;
        }

        $objEvent = $this->eventRepository->find(request('event_id'));
        $objYear = $this->yearRepository->find($year_id);

        $findEventYear = DB::table('event_year')
            ->where([
                ['event_id', '=', $objEvent->id],
                ['year_id', '=', $objYear->id]
            ])->first();

        if (!is_object($findEventYear)) {
            if ($objEvent instanceof Event) {
                $objEvent->years()->save($objYear,
                    [
                        'created_uid' => auth()->user()->id,
                        'updated_uid' => auth()->user()->id,
                        'start' => Carbon::parse(request('start'))->toDateTimeString(),
                        'end' => Carbon::parse(request('end'))->toDateTimeString(),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]
                );
                return Response::json(['status' => true, 'event' => $objEvent]);
            }
        }
        return Response::json(['status' => false]);
    }

    /**
     * Resizing the event.
     *
     * @return mixed
     */
    public function resizeEvent()
    {
        return Response::json(['status' => false]);
    }

    /**
     * Moving the event.
     *
     * @return mixed
     */
    public function moveEvent()
    {
        return Response::json(['status' => false]);
    }

    /**
     * Deleting the event.
     *
     * @return mixed
     */
    public function deleteEvent()
    {
        if (DB::table('event_year')->where([['id', '=', request('id')]])->delete()) {
            #TODO optimize code.
            DB::table('department_event')->where([['event_id', '=', request('id')]])->delete();
            return Response::json(['status' => true]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Rendering events on full calendar.
     *
     * @return mixed
     */
    public function renderEventsOnFullCalendar()
    {
        return DB::table('event_year')
            ->join('years', 'event_year.year_id', '=', 'years.id')
            ->join('events', 'event_year.event_id', '=', 'events.id')
            ->select('event_year.id', 'events.title', 'event_year.start', 'event_year.end', 'events.allDay')
            ->get();
    }
}