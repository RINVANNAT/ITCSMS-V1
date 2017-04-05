<?php

namespace App\Http\Controllers\Backend\Schedule;

use App\Models\Department;
use App\Models\Schedule\Calendar\CategoryEvent;
use App\Models\Schedule\Calendar\Event;
use App\Models\Schedule\Calendar\Year;
use App\Repositories\Backend\Department\EloquentDepartmentRepository;
use App\Repositories\Backend\Schedule\EloquentEventRepository;
use App\Repositories\Backend\Schedule\EloquentYearRepository;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
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
     * CalendarController constructor.
     * @param EloquentEventRepository $eventRepository
     * @param EloquentYearRepository $yearRepository
     */
    public function __construct(EloquentEventRepository $eventRepository, EloquentYearRepository $yearRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->yearRepository = $yearRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categoryEvents = CategoryEvent::all();
        $events = Event::latest()->get();
        $departments = Department::all();
        return view('backend.schedule.calendars.index', compact('categoryEvents', 'events', 'departments'));
    }

    /**
     * @param $year
     * @return mixed
     */
    public function getEventsByYear($year)
    {
        $findYear = $this->yearRepository->findByYear($year);
        if ($findYear instanceof Year) {
            $events = $this->eventRepository->getEventsByYear($findYear->id);
            return Response::json(['status' => true, 'events' => $events]);
        }

        return Response::json(['status' => true, 'events' => Event::all()]);
    }

    public function dragEvent()
    {
        if ($this->yearRepository->findByYear(Carbon::parse(request('start'))->year) == null) {
            $newYear = new Year();
            $newYear->year = Carbon::parse(request('start'))->year;

            $newYear->save();
        }

        $event_id = request('event_id');
        $year = Carbon::parse(request('start'))->year;
        $objectYear = $this->yearRepository->findByYear($year);
        $event = DB::table('event_year')
            ->where([
                ['event_id', '=', $event_id],
                ['year_id', '=', $objectYear->id]
            ])->first();

        if (!empty($event)) {
            return Response::json(['status' => false]);
        }


        // Check year, the year is already existed or not yet.


        // Find year match with year_id request.
        $year = $this->yearRepository->findByYear(Carbon::parse(request('start'))->year);

        // Find event match with event_id request.
        $event = Event::find(request('event_id'));

        // Attach associated model.
        $year->events()->save($event, ['start' => Carbon::parse(request('start')), 'end' => Carbon::parse(request('end'))]);

        // Get fresh event
        $event = DB::table('event_year')->where([
            ['event_id', '=', $event->id],
            ['year_id', '=', $year->id]
        ])->first();

        return Response::json(['status' => true, 'event' => $event, 'title' => request('title')]);
    }

    /**
     * @return mixed
     */
    public function getEvents()
    {
        $events = $this->eventRepository->getAllObjectEvents();
        return Response::json($events);
    }

    /**
     * @return mixed
     */
    public function deleteEvent()
    {
        $status = DB::table('event_year')
            ->where([
                ['id', '=', request('id')]
            ])->delete();

        return Response::json(['status' => $status, 'id' => request('id')]);
    }

    /**
     * @return mixed
     */
    public function resizeEvent()
    {
        $status = DB::table('event_year')
            ->where([
                ['id', '=', request('id')]
            ])
            ->update([
                'start' => request('start'),
                'end' => request('end')
            ]);

        return Response::json(['status' => $status]);
    }

    /**
     * @return mixed
     */
    public function moveEvent()
    {

        $event = DB::table('event_year')
            ->where('id', request('id'))
            ->update(['start' => request('start'), 'end' => request('start')]);

        if (empty($event)) {
            return Response::json(['status' => false]);
        }

        return Response::json(['status' => true]);
    }

    /***
     * @return mixed
     */
    public function store()
    {
        dd(request()->all());

        $newEvent = new Event();

        $newEvent->title = $request->title;
        $newEvent->category_event_id = $request->category_id;


        if (isset($request->study)) {
            $newEvent->study = $request->study;
        }

        $status = false;

        if ($newEvent->save()) {
            $status = true;
        }

        return Response::json(['status' => $status]);
    }
}
