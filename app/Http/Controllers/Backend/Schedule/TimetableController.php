<?php

namespace App\Http\Controllers\Backend\Schedule;

use App\Http\Controllers\Backend\Schedule\Traits\AjaxCloneTimetableController;
use App\Http\Controllers\Backend\Schedule\Traits\AjaxFilterTimetableController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Models\Schedule\Timetable\Timetable;
use App\Repositories\Backend\Schedule\Timetable\EloquentTimetableRepository;
use App\Repositories\Backend\Schedule\Timetable\EloquentTimetableSlotRepository;

/**
 * Class TimetableController
 * @package App\Http\Controllers\Backend\Schedule
 */
class TimetableController extends Controller
{
    use AjaxFilterTimetableController, AjaxCloneTimetableController;

    /**
     * @var EloquentTimetableRepository
     */
    protected $timetableRepository;
    /**
     * @var EloquentTimetableSlotRepository
     */
    protected $timetableSlotRepository;

    /**
     * TimetableController constructor.
     * @param EloquentTimetableSlotRepository $timetableSlotRepository
     * @param EloquentTimetableRepository $timetableRepository
     */
    public function __construct
    (
        EloquentTimetableSlotRepository $timetableSlotRepository,
        EloquentTimetableRepository $timetableRepository
    )
    {
        $this->timetableSlotRepository = $timetableSlotRepository;
        $this->timetableRepository = $timetableRepository;
    }

    /**
     * Timetable home page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.schedule.timetables.index');
    }

    /**
     * Create timetable page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('backend.schedule.timetables.create');
    }

    /**
     * Show timetable's details page.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        return view('backend.schedule.timetables.show');
    }

    /**
     * @param CreateTimetableRequest $request
     * @return int
     */
    public function store(CreateTimetableRequest $request)
    {
        $findTimetable = $this->timetableRepository->find_timetable_is_existed($request);
        if ($findTimetable instanceof Timetable) {
            $this->timetableSlotRepository->create_timetable_slot($findTimetable, $request);
        } else {
            $newTimetable = $this->timetableRepository->create_timetable($request);

            if ($newTimetable instanceof Timetable) {
                $this->timetableSlotRepository->create_timetable_slot($newTimetable, $request);
            }
        }
    }
}
