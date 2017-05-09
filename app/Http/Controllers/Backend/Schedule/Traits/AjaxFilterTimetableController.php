<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\MoveTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\ResizeTimetableSlotRequest;
use App\Models\DepartmentOption;
use App\Models\Room;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
use App\Models\Schedule\Timetable\Week;
use App\Repositories\Backend\Schedule\Timetable\EloquentTimetableRepository;
use App\Repositories\Backend\Schedule\Timetable\EloquentTimetableSlotRepository;
use Carbon\Carbon;
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
//    protected $timetableRepository;

    /**
     * @var EloquentTimetableSlotRepository
     */
//    protected $timetableSlotRepository;

    /**
     * AjaxFilterTimetableController constructor.
     * @param EloquentTimetableRepository $eloquentTimetableRepository
     * @param EloquentTimetableSlotRepository $eloquentTimetableSlotRepository
     */
    /*public function __construct
    (
        EloquentTimetableRepository $eloquentTimetableRepository,
        EloquentTimetableSlotRepository $eloquentTimetableSlotRepository
    )
    {
        $this->timetableRepository = $eloquentTimetableRepository;
        $this->timetableSlotRepository = $eloquentTimetableSlotRepository;
    }*/

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
            ->select(
                'course_sessions.id',
                'course_sessions.time_tp as tp',
                'course_sessions.time_td as td',
                'course_sessions.time_course as tc',
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
                    ->where('rooms.name', 'like', '%' . request('query') . '%')
                    ->orWhere('buildings.code', 'like', '%' . request('query') . '%')
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
        $timetable = $this->timetableRepository->find_timetable_is_existed($request);
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
            return \GuzzleHttp\json_decode($timetable_slots);
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
                return Response::json(['status' => true, 'timetable_slot' => $timetable_slot]);
            }
        }
        return Response::json(['status' => false]);
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
                $timetable_slot->durations = $this->timetableSlotRepository->durations(new Carbon($timetable_slot->start), new Carbon($request->end));
                $timetable_slot->end = new Carbon($request->end);
                $timetable_slot->update();
                return Response::json(['status' => true, 'timetable_slot' => $timetable_slot]);
            }
        }
        return Response::json(['status' => false]);
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
                    ->where('rooms.name', 'like', '%'.$query == null ? null : $query.'%')
                    ->whereNotNull('timetable_slots.room_id')
                    ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
                    ->select('rooms.id as id', 'rooms.name as name', 'buildings.code as code')
                    ->get();

                $rooms_tmp = DB::table('timetables')
                    ->join('timetable_slots', 'timetable_slots.timetable_id', '=', 'timetables.id')
                    ->where([
                        ['timetables.academic_year_id', $academic_year_id],
                        ['timetables.week_id', $week_id],
                        ['timetable_slots.start', $timetable_slot->start],
                        ['timetable_slots.end', $timetable_slot->end]
                    ])
                    ->whereNotNull('timetable_slots.room_id')
                    ->lists('timetable_slots.room_id');

                $rooms_remaining = DB::table('rooms')
                    ->whereNotIn('rooms.id', $rooms_tmp == [] ? [] : $rooms_tmp)
                    ->where('rooms.name', 'like', '%'.$query == null ? null : $query.'%')
                    ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
                    ->select('rooms.id as id', 'rooms.name as name', 'buildings.code as code')
                    ->get();

                if(count($rooms_remaining) > 0){
                    return Response::json([
                        'status' => true,
                        'roomUsed' => $rooms_used,
                        'roomRemain' => $rooms_remaining
                    ]);
                }else{
                    return Response::json([
                        'status' => false
                    ]);
                }
            }
        }
    }
}