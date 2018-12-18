<?php

namespace App\Http\Controllers\Backend\Schedule;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use App\Http\Controllers\Backend\Schedule\Traits\AjaxCalendarController;
use App\Http\Controllers\Backend\Traits\FilteringTrait;
>>>>>>> fedaae8fa343ed7fcb7f22e72491f4e7643b0b46
=======
>>>>>>> 02174d3efc980c369f28bf9cdc13d9f3fffaf30d
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Schedule\Calendar\CreateEventRequest;
use App\Models\Department;
use App\Models\Schedule\Calendar\Event\Event;
use App\Models\Schedule\Calendar\Repeat\Repeat;
use App\Models\Schedule\Calendar\Year\EventYear;
use App\Models\Schedule\Calendar\Year\Year;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

/**
 * Class CalendarController
 * @package App\Http\Controllers\Backend\Schedule
 */
class CalendarController extends Controller
{
<<<<<<< HEAD
<<<<<<< HEAD
=======
    use AjaxCalendarController;
    use FilteringTrait;

    /**
     * @var EloquentEventRepository
     */
    protected $eventRepository;

    /**
     * @var EloquentYearRepository
     */
    protected $yearRepository;

    /**
     * @var EloquentRepeatRepository
     */
    protected $repeatRepository;

    /**
     * CalendarController constructor.
     * @param EloquentEventRepository $eloquentEventRepository
     * @param EloquentYearRepository $eloquentYearRepository
     * @param EloquentRepeatRepository $eloquentRepeatRepository
     */
    public function __construct(
        EloquentEventRepository $eloquentEventRepository,
        EloquentYearRepository $eloquentYearRepository,
        EloquentRepeatRepository $eloquentRepeatRepository
    )
    {
        $this->eventRepository = $eloquentEventRepository;
        $this->yearRepository = $eloquentYearRepository;
        $this->repeatRepository = $eloquentRepeatRepository;
        $this->setEventRepository($eloquentEventRepository);
        $this->setRepeatRepository($eloquentRepeatRepository);
        $this->setYearRepository($eloquentYearRepository);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
>>>>>>> fedaae8fa343ed7fcb7f22e72491f4e7643b0b46
=======
>>>>>>> 02174d3efc980c369f28bf9cdc13d9f3fffaf30d
    public function index()
    {
        $departments = Department::all();
        return view('backend.schedule.calendars.index', compact('departments'));
    }

    public function store(CreateEventRequest $request)
    {
        if ($this->createEvent($request) == true) {
            return [
                'status' => true,
                'data' => Event::get()
            ];
        }
        return Response::json(['status' => false]);
    }

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 02174d3efc980c369f28bf9cdc13d9f3fffaf30d
    public function createEvent(CreateEventRequest $request)
    {
        if (!empty($request->dailyYear)) {
            $newRepeat = new Repeat();

            $newRepeat->start = new Carbon($request->start);
            $newRepeat->end = new Carbon($request->end);

            if ($newRepeat->save()) {
                $newEvent = new Event();

                $newEvent->title = $request->title;
                $newEvent->description = "Typing your description here.";
                $newEvent->repeat_id = $newRepeat->id;
                $newEvent->allDay = false;
                $newEvent->public = $request->public;
                if (empty($request->study)) {
                    $newEvent->study = false;
                }
                $newEvent->study = $request->study;

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

    public function getDepartments()
    {
        return Response::json([
            'status' => true,
            'data' => Department::all()
        ]);
    }

    /**
     * @return array
     */
    public function listEventsOnSideLeft()
    {
        $events = Event::latest();
        return [
            'status' => true,
            'events' => $events
        ];
    }

    public function dragEvent()
    {
        $year = Carbon::parse(request('start'))->year;
        $objectYear = $this->findByYear($year);

        if ($objectYear == false) {
            $newYear = new Year();
            $newYear->name = $year;
            if ($newYear->save()) {
                $year_id = $newYear->id;
            }
        } else {
            $year_id = $objectYear->id;
        }

        $objEvent = Event::find(request('event_id'));
        $objYear = Year::find($year_id);

        $findEventYear = DB::table('event_year')
            ->where([
                ['event_id', '=', $objEvent->id],
                ['year_id', '=', $objYear->id]
            ])->first();

        if (!is_object($findEventYear)) {
            if ($objEvent instanceof Event) {
                $objEvent->years()->save($objYear, [
                    'created_uid' => auth()->user()->id,
                    'updated_uid' => auth()->user()->id,
                    'start' => Carbon::parse(request('start'))->toDateTimeString(),
                    'end' => Carbon::parse(request('end'))->toDateTimeString(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
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
        return ['status' => false];
    }

    public function findByYear($name)
    {
        if (Year::where('name', $name)->first() instanceof Year) {
            return Year::where('name', $name)->first();
        }
        return false;
    }

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

    public function deleteEvent()
    {
        if (DB::table('event_year')->where([['id', '=', request('id')]])->delete()) {
            #TODO optimize code.
            DB::table('department_event')->where([['event_id', '=', request('id')]])->delete();
            return Response::json(['status' => true]);
        }
        return Response::json(['status' => false]);
    }

    public function renderEventsOnFullCalendar($departmentId)
    {
        $eventsPublic = DB::table('event_year')
            ->join('events', function ($eventsQuery) {
                $eventsQuery->on('events.id', '=', 'event_year.event_id')
                    ->where('events.public', '=', true);
            })
            ->select('event_year.id', 'events.title', 'events.description', 'event_year.start', 'event_year.end', 'events.allDay', 'events.public', 'events.created_uid');

        $eventsPrivate = DB::table('department_event')
            ->join('event_year', function ($eventYearQuery) use ($departmentId) {
                $eventYearQuery->on('department_event.event_id', '=', 'event_year.event_id')
                    ->where('department_event.department_id', '=', $departmentId);
            })
            ->join('events', 'events.id', '=', 'department_event.event_id')
            ->select('event_year.id', 'events.title', 'events.description', 'event_year.start', 'event_year.end', 'events.allDay', 'events.public', 'events.created_uid')
            ->union($eventsPublic)
            ->get();

        return $eventsPrivate;
    }

    public function findEventsByYear($year)
    {
        $objYear = Year::where('name', $year)->first();

        if ($objYear instanceof Year) {
            $events = $this->findEventsByYearAndAuthor($objYear->id, auth()->user()->id, auth()->user()->getDepartment());
            return Response::json(['status' => true, 'events' => $events]);
        }
        return Response::json(['status' => true, 'events' => Event::latest()->get()]);
    }

    public function findEventsByYearAndAuthor($yearId, $authorId, $departmentId)
    {
        return DB::table('events')
            ->whereNotIn('id', function ($query) use ($yearId, $departmentId) {
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
                if ($this->findEventYear($event->id, $objYear->id) instanceof EventYear) {
                    continue;
                } else {
                    /** @var Event $event */
                    /** @var Year $objYear */
                    $this->copiedObjectRepeatEvent($event, $objYear);
                }
            }
        }
    }

    public function findEventYear($event_id, $year_id)
    {
        return EventYear::where([
            'event_id' => $event_id,
            'year_id' => $year_id
        ])->first();
    }

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

    public function find($id)
    {
        if (Event::find($id) instanceof Event) {
            return Event::find($id);
        }
        return null;
    }

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
<<<<<<< HEAD
=======
    public function getClass() {
        return message_success($this->get_available_class(2018));
>>>>>>> fedaae8fa343ed7fcb7f22e72491f4e7643b0b46
=======
>>>>>>> 02174d3efc980c369f28bf9cdc13d9f3fffaf30d
    }
}
