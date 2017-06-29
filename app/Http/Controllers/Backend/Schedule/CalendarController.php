<?php

namespace App\Http\Controllers\Backend\Schedule;

use App\Http\Controllers\Backend\Schedule\Traits\AjaxCalendarController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Schedule\Calendar\CreateEventRequest;
use App\Models\Department;
use App\Models\Schedule\Calendar\Event\Event;
use App\Repositories\Backend\Schedule\Calendar\EloquentEventRepository;
use App\Repositories\Backend\Schedule\Calendar\EloquentRepeatRepository;
use App\Repositories\Backend\Schedule\Calendar\EloquentYearRepository;
use Illuminate\Support\Facades\Response;

/**
 * Class CalendarController
 * @package App\Http\Controllers\Backend\Schedule
 */
class CalendarController extends Controller
{
    use AjaxCalendarController;

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
    public function index()
    {
        $departments = Department::all();
        return view('backend.schedule.calendars.index', compact('departments'));
    }

    /**
     * @param CreateEventRequest $request
     * @return mixed
     */
    public function store(CreateEventRequest $request)
    {
        if ($this->eventRepository->createEvent($request) == true) {
            return Response::json(['status' => true, 'data' => Event::all()]);
        }
        return Response::json(['status' => false]);
    }
}
