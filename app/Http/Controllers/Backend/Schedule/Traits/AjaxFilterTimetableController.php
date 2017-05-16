<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\MoveTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\ResizeTimetableSlotRequest;
use App\Models\CourseSession;
use App\Models\DepartmentOption;
use App\Models\Room;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
use App\Models\Schedule\Timetable\Week;
use App\Repositories\Backend\Schedule\Timetable\EloquentTimetableRepository;
use App\Repositories\Backend\Schedule\Timetable\EloquentTimetableSlotRepository;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

/**
 * Class AjaxFilterTimetableController
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait AjaxFilterTimetableController
{
    /**
     * @var EloquentTimetableRepository
     */
    public $timetableRepo;

    /**
     * @var EloquentTimetableSlotRepository
     */
    public $timetableSlotRepo;

    /**
     * AjaxFilterTimetableController constructor.
     *
     * @param EloquentTimetableRepository $timetableRepository
     * @param EloquentTimetableSlotRepository $timetableSlotRepository
     */
    public function __construct(
        EloquentTimetableRepository $timetableRepository,
        EloquentTimetableSlotRepository $timetableSlotRepository
    )
    {
        $this->timetableRepo = $timetableRepository;
        $this->timetableSlotRepo = $timetableSlotRepository;
    }

    /**
     * @param EloquentTimetableRepository $timetableRepository
     * @param EloquentTimetableSlotRepository $timetableSlotRepository
     */
    public function setRepository(
        EloquentTimetableRepository $timetableRepository,
        EloquentTimetableSlotRepository $timetableSlotRepository
    )
    {
        $this->timetableRepo = $timetableRepository;
        $this->timetableSlotRepo = $timetableSlotRepository;
    }

    /**
     * Filter timetable.
     *
     * @return mixed
     */
    public function filter()
    {
        return Response::json(['status' => true, 'data' => request()->all()]);
    }

    /**
     * Filter courses sessions.
     *
     * @return mixed
     */
    public function filterCoursesSessions()
    {
        return Response::json(['status' => true, 'data' => request()->all()]);
    }

    /**
     * Get weeks by semester.
     *
     * @return array
     * @internal param Request $request
     */
    public function get_weeks()
    {
        $semester_id = request('semester_id');

        if (isset($semester_id)) {
            return Response::json(['status' => true, 'weeks' => Week::where('semester_id', $semester_id)->get()]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Get options by department.
     *
     * @return mixed
     */
    public function get_options()
    {
        $department_id = request('department_id');

        if (isset($department_id)) {
            return Response::json(['status' => true, 'options' => DepartmentOption::where('department_id', $department_id)->get()]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Get course sessions.
     *
     * @return mixed
     */
    public function get_course_sessions()
    {
        $this->timetableSlotRepo->set_value_into_time_remaining_course_session();
        $academic_year_id = request('academicYear');
        $department_id = request('department');
        $degree_id = request('degree');
        $grade_id = request('grade');
        $semester_id = request('semester');
        $option_id = request('option') == null ? null : request('option');
        $group_id = request('group') == null ? null : request('group');


        /*dd(request('group'));*/
        $course_sessions = DB::table('course_annuals')
            ->where([
                ['course_annuals.academic_year_id', $academic_year_id],
                ['course_annuals.department_id', $department_id],
                ['course_annuals.degree_id', $degree_id],
                ['course_annuals.grade_id', $grade_id],
                ['course_annuals.semester_id', $semester_id],
                ['course_annuals.department_option_id', $option_id],
            ])
            ->join('course_sessions', 'course_sessions.course_annual_id', '=', 'course_annuals.id')
            ->leftJoin('employees', 'employees.id', '=', 'course_sessions.lecturer_id')
            ->where(function ($query) use ($group_id) {
                $groups = DB::table('course_annual_classes')->where('course_annual_classes.group_id', $group_id)
                    ->lists('course_annual_classes.course_session_id');
                $query->whereIn('course_sessions.id', $groups == null ? [] : $groups);
            })
            ->where('course_sessions.time_remaining', '>', 0)
            ->select(
                'course_sessions.id',
                'course_sessions.time_tp as tp',
                'course_sessions.time_td as td',
                'course_sessions.time_course as tc',
                'course_sessions.time_remaining as remaining',
                'course_annuals.name_en as course_name',
                'employees.name_latin as teacher_name'
            )
            ->get();

        if (count($course_sessions) > 0) {
            return Response::json([
                'status' => true,
                'course_sessions' => $course_sessions
            ]);
        } else {
            return Response::json([
                'status' => false
            ]);
        }
    }

    /**
     * Get groups.
     *
     * @return mixed
     */
    public function get_groups()
    {
        $academic_year_id = request('academicYear');
        $department_id = request('department');
        $degree_id = request('degree');
        $grade_id = request('grade');
        $option_id = request('option') == null ? null : request('option');

        $groups = DB::table('studentAnnuals')
            ->where([
                ['academic_year_id', $academic_year_id],
                ['department_id', $department_id],
                ['degree_id', $degree_id],
                ['grade_id', $grade_id],
                ['department_option_id', $option_id]
            ])
            ->join('groups', 'groups.id', '=', 'studentAnnuals.group_id')
            ->orderBy('groups.code', 'asc')
            ->select('studentAnnuals.group_id as id', 'groups.code as name')
            ->distinct('studentAnnuals.group_id')
            ->get();

        if (count($groups) > 1) {
            return Response::json(['status' => true, 'groups' => $groups]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Search rooms.
     *
     * @return array|string
     */
    public function search_rooms()
    {
        if (array_key_exists('query', request()->all())) {
            if (request('query') != ' ') {
                $rooms = DB::table('rooms')
                    ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
                    ->where(DB::raw("CONCAT(buildings.code, '-', rooms.name)"), 'LIKE', "%" . request('query') . "%")
                    ->select('rooms.id as id', 'rooms.name as name', 'buildings.code as code')
                    ->get();

                if (count($rooms) > 0) {
                    return Response::json([
                        'status' => true,
                        'rooms' => $rooms
                    ]);
                } else {
                    return Response::json(['status' => false]);
                }
            }
        }
        $this->get_rooms();
    }

    /**
     * Get all rooms.
     *
     * @return mixed
     */
    public function get_rooms()
    {
        $rooms = DB::table('rooms')
            ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->select('rooms.id as id', 'rooms.name as name', 'buildings.code as code')
            ->get();

        return Response::json([
            'status' => true,
            'rooms' => $rooms
        ]);
    }

    /**
     * Get timetable slots.
     *
     * @param CreateTimetableRequest $request
     * @return mixed
     */
    public function get_timetable_slots(CreateTimetableRequest $request)
    {
        $timetable = $this->timetableRepo->find_timetable_is_existed($request);
        if ($timetable instanceof Timetable) {
            $timetable_slots = TimetableSlot::where('timetable_id', $timetable->id)
                ->leftJoin('rooms', 'rooms.id', '=', 'timetable_slots.room_id')
                ->leftJoin('buildings', 'buildings.id', '=', 'rooms.building_id')
                ->select(
                    'timetable_slots.id',
                    'timetable_slots.course_name as title',
                    'timetable_slots.course_name',
                    'timetable_slots.teacher_name',
                    'timetable_slots.type as course_type',
                    'timetable_slots.start',
                    'timetable_slots.end',
                    'buildings.code as building',
                    'rooms.name as room'
                )
                ->get();

            $timetableSlots = new Collection();
            foreach ($timetable_slots as $timetable_slot) {
                if (($timetable_slot instanceof TimetableSlot) && is_object($timetable_slot)) {

                    $newTimetableSlot = TimetableSlot::find($timetable_slot->id);
                    $timetableSlot = new Collection($newTimetableSlot);
                    if ($this->timetableSlotRepo->is_conflict_lecturer($newTimetableSlot)[0]['status'] == true) {
                        $timetableSlot->put('is_conflict_lecturer', true);
                    } else {
                        $timetableSlot->put('is_conflict_lecturer', false);
                    }
                    /*if($this->timetableSlotRepo->is_conflict_course($newTimetableSlot) == true){
                        $timetableSlot->put('is_conflict_course', true);
                    }
                    else{
                        $timetableSlot->put('is_conflict_course', false);
                    }*/
                    if ($this->timetableSlotRepo->is_conflict_room($newTimetableSlot, Room::find($newTimetableSlot->room_id))[0]['status'] == true) {
                        $timetableSlot->put('is_conflict_room', true);
                    } else {
                        $timetableSlot->put('is_conflict_room', false);
                    }
                    $timetableSlot->put('building', $timetable_slot->building);
                    $timetableSlot->put('room', $timetable_slot->room);
                    $timetableSlots->push($timetableSlot);
                }
            }

            return \GuzzleHttp\json_decode($timetableSlots);
        }
    }

    /**
     * Move timetable slot.
     *
     * @param MoveTimetableSlotRequest $request
     * @return mixed
     */
    public function move_timetable_slot(MoveTimetableSlotRequest $request)
    {
        if (isset($request->timetable_slot_id)) {
            $timetable_slot = TimetableSlot::find($request->timetable_slot_id);
            if ($timetable_slot instanceof TimetableSlot) {
                $start = new Carbon($request->start_date);
                $end = $start->addHours($timetable_slot->durations);
                $timetable_slot->start = new Carbon($request->start_date);
                $timetable_slot->end = $end;
                $timetable_slot->update();
                $collectionTimetableSlot = new Collection($timetable_slot);

                if ($this->timetableSlotRepo->is_conflict_lecturer($timetable_slot)[0]['status'] == true) {
                    $collectionTimetableSlot->put('is_conflict_lecturer', true);
                } else {
                    $collectionTimetableSlot->put('is_conflict_lecturer', false);
                }
                if ($timetable_slot->room_id != null) {
                    if ($this->timetableSlotRepo->is_conflict_room($timetable_slot, Room::find($timetable_slot->room_id))[0]['status'] == true) {
                        $collectionTimetableSlot->put('is_conflict_room', true);
                    } else {
                        $collectionTimetableSlot->put('is_conflict_room', false);
                    }
                }
                return Response::json(['status' => true, 'timetable_slot' => $collectionTimetableSlot]);
            }
        }
    }

    /**
     * Resize timetable slot.
     *
     * @param ResizeTimetableSlotRequest $request
     * @return mixed
     */
    public function resize_timetable_slot(ResizeTimetableSlotRequest $request)
    {
        if (isset($request->timetable_slot_id)) {
            $timetable_slot = TimetableSlot::find($request->timetable_slot_id);
            if ($timetable_slot instanceof TimetableSlot) {
                $old_durations = $timetable_slot->durations;
                $new_durations = $this->timetableSlotRepo->durations(new Carbon($timetable_slot->start), new Carbon($request->end));
                $timetable_slot->durations = $new_durations;
                $timetable_slot->end = new Carbon($request->end);

                $course_session = CourseSession::find($timetable_slot->course_session_id);

                if ($new_durations > $old_durations) {
                    $interval = $new_durations - $old_durations;
                    $course_session->time_remaining = $course_session->time_remaining - $interval;
                } else {
                    $interval = $old_durations - $new_durations;
                    $course_session->time_remaining = $course_session->time_remaining + $interval;
                }

                if (($course_session->time_remaining <= $course_session->time_used) && $course_session->time_remaining >= 0) {
                    $course_session->update();
                    $timetable_slot->update();
                    return Response::json(['status' => true, 'timetable_slot' => $timetable_slot]);
                } else {
                    return Response::json(['status' => false, 'message' => 'Time is limited.']);
                }
            }
        }
        return Response::json(['status' => false, 'message' => 'The timetable slot did not create yet.']);
    }

    /**
     * Insert room into timetable slot.
     *
     * @return mixed
     */
    public function insert_room_into_timetable_slot()
    {
        $timetable_slot_id = request('timetable_slot_id');
        $room_id = request('room_id');
        if (isset($timetable_slot_id) && isset($room_id)) {
            $timetable_slot = TimetableSlot::find($timetable_slot_id);
            $timetable_slot->room_id = $room_id;
            $timetable_slot->update();
            return Response::json(['status' => true]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Remove room.
     *
     * @return mixed
     */
    public function remove_room()
    {
        $timetable_slot_id = request('timetable_slot_id');
        if (isset($timetable_slot_id)) {
            $timetable_slot = TimetableSlot::find($timetable_slot_id);
            $timetable_slot->room_id = null;
            $timetable_slot->update();
            return Response::json(['status' => true, 'timetable_slot' => $timetable_slot]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Get all suggest room.
     *
     * @return mixed
     */
    public function get_suggest_room()
    {
        $academic_year_id = request('academic_year_id');
        $week_id = request('week_id');
        $timetable_slot_id = request('timetable_slot_id');
        $query = request('room_number');

        if (isset($timetable_slot_id)) {
            $timetable_slot = TimetableSlot::find($timetable_slot_id);

            if (isset($academic_year_id) && isset($week_id)) {

                $rooms_used = DB::table('timetables')
                    ->join('timetable_slots', 'timetable_slots.timetable_id', '=', 'timetables.id')
                    ->join('rooms', 'rooms.id', '=', 'timetable_slots.room_id')
                    ->where([
                        ['timetables.academic_year_id', $academic_year_id],
                        ['timetables.week_id', $week_id],
                        ['timetable_slots.start', $timetable_slot->start],
                        ['timetable_slots.end', $timetable_slot->end]
                    ])
                    ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
                    ->where(DB::raw("CONCAT(buildings.code, '-', rooms.name)"), 'LIKE', "%" . $query . "%")
                    ->whereNotNull('timetable_slots.room_id')
                    ->select('rooms.id as id', 'rooms.name as name', 'buildings.code as code')
                    ->distinct('name', 'code')
                    ->get();

                $rooms_tmp = DB::table('timetables')
                    ->join('timetable_slots', 'timetable_slots.timetable_id', '=', 'timetables.id')
                    ->join('rooms', 'rooms.id', '=', 'timetable_slots.room_id')
                    ->where([
                        ['timetables.academic_year_id', $academic_year_id],
                        ['timetables.week_id', $week_id],
                        ['timetable_slots.start', $timetable_slot->start],
                        ['timetable_slots.end', $timetable_slot->end]
                    ])
                    ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
                    ->where(DB::raw("CONCAT(buildings.code, '-', rooms.name)"), 'LIKE', "%" . $query . "%")
                    ->whereNotNull('timetable_slots.room_id')
                    ->lists('timetable_slots.room_id');

                $rooms_remaining = DB::table('rooms')
                    ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
                    ->whereNotIn('rooms.id', $rooms_tmp == [] ? [] : $rooms_tmp)
                    ->where(DB::raw("CONCAT(buildings.code, '-', rooms.name)"), 'LIKE', "%" . $query . "%")
                    ->select('rooms.id as id', 'rooms.name as name', 'buildings.code as code')
                    ->get();

                if (count($rooms_remaining) > 0) {
                    return Response::json([
                        'status' => true,
                        'roomUsed' => $rooms_used,
                        'roomRemain' => $rooms_remaining
                    ]);
                } else {
                    return Response::json([
                        'status' => false
                    ]);
                }
            }
        }
    }

    /**
     * Get conflict information.
     *
     * @return mixed
     */
    public function get_conflict_info()
    {
        $timetableSlot = TimetableSlot::find(request('timetable_slot_id'));
        $conflicts = array();
        if ($this->timetableSlotRepo->is_conflict_room($timetableSlot, Room::find($timetableSlot->room_id))[0]['status'] == true) {
            $conflicts['is_conflict_room'] = true;
            $with_timetable_slot = TimetableSlot::find($this->timetableSlotRepo->is_conflict_room($timetableSlot, Room::find($timetableSlot->room_id))[0]['conflict_with']->id);
            $info = $this->timetableSlotRepo->get_conflict_with($with_timetable_slot);
            $conflicts['room_info'] = $info;
            $timetableSlot->is_conflict = true;
            $timetableSlot->update();
        }

        if ($this->timetableSlotRepo->is_conflict_lecturer($timetableSlot)[0]['status'] == true) {
            $conflicts['is_conflict_lecturer'] = true;
            $with_timetable_slot = TimetableSlot::find($this->timetableSlotRepo->is_conflict_lecturer($timetableSlot)[0]['conflict_with']->id);
            $info = $this->timetableSlotRepo->get_conflict_with($with_timetable_slot);
            $conflicts['lecturer_info'] = $info;
            $conflicts['merge'] = $this->timetableSlotRepo->is_conflict_lecturer($timetableSlot)[0]['merge'];
            $timetableSlot->is_conflict = true;
            $timetableSlot->update();
        }

        if (count($conflicts) > 0) {
            return Response::json(['status' => true, 'data' => $conflicts]);
        } else {
            $timetableSlot->is_conflict = false;
            $timetableSlot->update();
            return Response::json(['status' => false]);
        }
    }
}