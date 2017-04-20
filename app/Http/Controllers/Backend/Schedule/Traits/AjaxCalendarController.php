<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Models\Department;
use App\Models\Schedule\Calendar\Event\Event;
use App\Models\Schedule\Calendar\Year\EventYear;
use App\Models\Schedule\Calendar\Year\Year;
use App\Repositories\Backend\Schedule\Calendar\EloquentEventRepository;
use App\Repositories\Backend\Schedule\Calendar\EloquentRepeatRepository;
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
     * @var EloquentRepeatRepository
     */
    protected $repeatRepository;

    /**
     * AjaxCalendarController constructor.
     * @param EloquentYearRepository $eloquentYearRepository
     * @param EloquentEventRepository $eloquentEventRepository
     * @param EloquentRepeatRepository $eloquentRepeatRepository
     */
    public function __construct(
        EloquentYearRepository $eloquentYearRepository,
        EloquentEventRepository $eloquentEventRepository,
        EloquentRepeatRepository $eloquentRepeatRepository
    )
    {
        $this->yearRepository = $eloquentYearRepository;
        $this->eventRepository = $eloquentEventRepository;
        $this->repeatRepository = $eloquentRepeatRepository;
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
                // Find the latest event_year record.
                $latestEventYear = DB::table('event_year')->latest()->first();
                return Response::json([
                    'status' => true,
                    'title' => request('title'),
                    'id' => $latestEventYear->id,
                    'public' => $objEvent->public,
                    'start' => $latestEventYear->start,
                    'end' => $latestEventYear->end
                ]);
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
        $objEvent = DB::table('event_year')->where('id', request('id'));

        if (is_object($objEvent)) {
            $objEvent->update([
                'start' => request('start'),
                'end' => request('end')
            ]);
            return Response::json(['status' => true]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Moving the event.
     *
     * @return mixed
     */
    public function moveEvent()
    {
        $objEvent = DB::table('event_year')->where('id', request('id'));

        if (is_object($objEvent)) {
            // Copy object to find times of days.
            $tmp = $objEvent->first();
            $startOld = new \DateTime($tmp->start);
            $endOld = new \DateTime($tmp->end);
            /** @var integer $interval */
            $interval = $startOld->diff($endOld);
            $startNewTmp = new Carbon(request('start'));
            $endNew = $startNewTmp->addDays($interval->days);
            $objEvent->update([
                'start' => new Carbon(request('start')),
                'end' => $endNew
            ]);
            return Response::json(['status' => true]);
        }
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
     * @param $departmentId
     * @return mixed
     */
    public function renderEventsOnFullCalendar($departmentId)
    {
        return DB::table('event_year')
            ->join('years', function ($yearQuery) {
                $yearQuery->on('event_year.year_id', '=', 'years.id');
            })
            ->join('events', function ($eventQuery) use ($departmentId) {
                $eventQuery->on('events.id', '=', 'event_year.event_id')
                    ->where('events.public', '=', true)
                    ->orWhere(function ($deptQuery) use ($departmentId) {
                        $eventIds = DB::table('department_event')
                            ->select('department_event.event_id')
                            ->where('department_event.department_id', $departmentId)
                            ->lists('event_id');
                        if ($eventIds) {
                            $deptQuery->whereIn('events.id', $eventIds);
                        } else {
                            $deptQuery = null;
                        }
                    });
            })
            ->select('event_year.id', 'events.title', 'events.description', 'event_year.start', 'event_year.end', 'events.allDay', 'events.public', 'events.created_uid')
            ->get();

    }

    /**
     * Find all events by year.
     *
     * @param $year
     * @return mixed
     */
    public function findEventsByYear($year)
    {
        $objYear = Year::where('name', $year)->first();

        if ($objYear instanceof Year) {
            $events = $this->eventRepository->findEventsByYearAndAuthor($objYear->id, auth()->user()->id);
            return Response::json(['status' => true, 'events' => $events]);
        }
        return Response::json(['status' => true, 'events' => Event::latest()->get()]);
    }

    /**
     * Store all repeat event to each year.
     *
     * @param $year
     * @return mixed
     */
    public function renderRepeatEvent($year)
    {
        $objYear = Year::where('name', $year)->first();

        if (!$objYear instanceof Year) {
            $objYear = new Year();

            $objYear->name = $year;
            $objYear->save();
        }

        $repeatEvents = Event::where([
            ['repeat_id', '!=', null]
        ])->get();

        if ($repeatEvents != null) {
            foreach ($repeatEvents as $event) {
                if ($this->eventRepository->findEventYear($event->id, $objYear->id) instanceof EventYear) {
                    continue;
                } else {
                    /** @var Event $event */
                    /** @var Year $objYear */
                    $this->repeatRepository->copiedObjectRepeatEvent($event, $objYear);
                }
            }
        }
    }
}