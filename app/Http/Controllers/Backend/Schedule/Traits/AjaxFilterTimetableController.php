<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\MoveTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\ResizeTimetableSlotRequest;
use App\Models\DepartmentOption;
use App\Models\Room;
use App\Models\Schedule\Timetable\MergeTimetableSlot;
use App\Models\Schedule\Timetable\Slot;
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
        $academic_year_id = request('academicYear');
        $department_id = request('department');
        $degree_id = request('degree');
        $grade_id = request('grade');
        $semester_id = request('semester');
        $option_id = request('option') == null ? null : request('option');
        $group_id = request('group') == null ? null : request('group');

        $course_sessions = DB::table('course_annuals')
            ->where([
                ['course_annuals.academic_year_id', $academic_year_id],
                ['course_annuals.department_id', $department_id],
                ['course_annuals.degree_id', $degree_id],
                ['course_annuals.grade_id', $grade_id],
                ['course_annuals.semester_id', $semester_id],
                ['course_annuals.department_option_id', $option_id],
            ])
            ->join('slots', 'slots.course_annual_id', '=', 'course_annuals.id')
            ->leftJoin('employees', 'employees.id', '=', 'slots.lecturer_id')
            ->where('slots.group_id', '=', $group_id)
            ->where('slots.time_remaining', '>', 0)
            ->select(
                'slots.id as id',
                'slots.course_session_id as course_session_id',
                'slots.time_tp as tp',
                'slots.time_td as td',
                'slots.time_course as tc',
                'slots.time_remaining as remaining',
                'course_annuals.name_en as course_name',
                'employees.name_latin as teacher_name'
            )->get();

        // dd($course_sessions);
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
            ->orderBy('groups.code', 'desc')
            ->select('studentAnnuals.group_id as id', 'groups.code as name')
            ->distinct('studentAnnuals.group_id')
            ->get();

        // sort groups name.
        usort($groups, function ($a, $b) {
            if (is_numeric($a->name)) {
                return $a->name - $b->name;
            } else {
                return strcmp($a->name, $b->name);
            }
        });

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

                    $itemTimetableSlot = TimetableSlot::find($timetable_slot->id);
                    $timetableSlot = new Collection($itemTimetableSlot);

                    $data = $this->timetableSlotRepo->check_conflict_lecturer($itemTimetableSlot);
                    $timetableSlot->put('conflict_lecturer', $data);
                    $timetableSlot->put('building', $timetable_slot->building);
                    $timetableSlot->put('room', $timetable_slot->room);
                    $timetableSlots->push($timetableSlot);
                }
            }
            return json_decode($timetableSlots);
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

            // find timetable slot
            $timetable_slot = TimetableSlot::find($request->timetable_slot_id);

            // update timetable slot
            if ($timetable_slot instanceof TimetableSlot) {
                $start = new Carbon($request->start_date);
                $end = $start->addHours($timetable_slot->durations);
                $timetable_slot->start = new Carbon($request->start_date);
                $timetable_slot->end = $end;
                $timetable_slot->updated_at = Carbon::now();

                // update merge timetable slot
                if ($timetable_slot->update()) {
                    $this->timetableSlotRepo->update_merge_timetable_slot($timetable_slot);
                }
                return Response::json(['status' => true]);
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
            // find timetable slot
            $timetable_slot = TimetableSlot::find($request->timetable_slot_id);
            if ($timetable_slot instanceof TimetableSlot) {
                // get old durations.
                $old_durations = $timetable_slot->durations;
                // find new durations.
                $new_durations = $this->timetableSlotRepo->durations(new Carbon($timetable_slot->start), new Carbon($request->end));
                // set update new value.
                $timetable_slot->durations = $new_durations;
                $timetable_slot->end = new Carbon($request->end);
                $timetable_slot->updated_at = Carbon::now();
                // find course session.
                $slot = Slot::find($timetable_slot->slot_id);

                // calculate duration time.
                if ($new_durations > $old_durations) {
                    $interval = $new_durations - $old_durations;
                    $slot->time_remaining = $slot->time_remaining - $interval;
                } else {
                    $interval = $old_durations - $new_durations;
                    $slot->time_remaining = $slot->time_remaining + $interval;
                }

                // validate duration time.
                if (($slot->time_remaining <= $slot->time_used) && $slot->time_remaining >= 0) {
                    $slot->update();
                    // update timetable slot.
                    $timetable_slot->update();
                    // update merge timetable slot.
                    $this->timetableSlotRepo->update_merge_timetable_slot($timetable_slot);
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

        // check conflict room.
        if ($this->timetableSlotRepo->is_conflict_room($timetableSlot, Room::find($timetableSlot->room_id))[0]['status'] == true) {
            $conflicts['is_conflict_room'] = true;
            $with_timetable_slot = TimetableSlot::find($this->timetableSlotRepo->is_conflict_room($timetableSlot, Room::find($timetableSlot->room_id))[0]['conflict_with']->id);
            $info = $this->timetableSlotRepo->get_conflict_with($with_timetable_slot);
            $conflicts['room_info'] = $info;
        } else {
            $conflicts['is_conflict_room'] = false;
            $conflicts['room_info'] = null;
        }

        // check conflict lecturer.
        $lecturer = new Collection();

        $canMergeCollection = new Collection();
        $canNoMergeCollection = new Collection();
        $canMerge = $this->timetableSlotRepo->check_conflict_lecturer($timetableSlot)['canMerge'];
        $canNotMerge = $this->timetableSlotRepo->check_conflict_lecturer($timetableSlot)['canNotMerge'];

        // check each can merge item to get conflict details.
        if (count($canMerge) > 0) {
            foreach ($canMerge as $item) {
                $canMergeCollection->push($this->timetableSlotRepo->get_conflict_with(TimetableSlot::find($item->id)));
            }
            $lecturer->put('canMerge', $canMergeCollection);
        }
        // check each can not merge item to get conflict details.
        if (count($canNotMerge) > 0) {
            foreach ($canNotMerge as $item) {
                $canNoMergeCollection->push($this->timetableSlotRepo->get_conflict_with(TimetableSlot::find($item->id)));
            }
            $lecturer->put('canNotMerge', $canNoMergeCollection);
        }
        // merge those two to conflicts.
        $conflicts['lecturer'] = $lecturer;
        count($conflicts['lecturer']) > 0 ? $conflicts['lecturer_conflict'] = true : $conflicts['lecturer_conflict'] = false;

        // Return conflict info.
        return Response::json(['data' => $conflicts]);
    }

    /**
     * Merge timetable slot.
     *
     * @return mixed
     */
    public function merge_timetable_slot()
    {
        // find timetable slot we will solve conflict
        $timetableSlot = TimetableSlot::find(request('timetable_slot_id'));
        // check validate
        if ($timetableSlot instanceof TimetableSlot) {
            // find all timetable slots conflict with and can merge together.
            $canMerge = $this->timetableSlotRepo->check_conflict_lecturer($timetableSlot)['canMerge'];
            // loop for each item
            foreach ($canMerge as $item) {
                // update group merge id for each item.
                $this->timetableSlotRepo->update_timetable_slot_when_merge($item, $timetableSlot->group_merge_id);
                // remove it from merge timetable slot.
                MergeTimetableSlot::find($item->group_merge_id)->delete();
            }
        }
        return Response::json(['status' => false]);
    }

    /**
     * Export data from course session to slot and course annual classes to slot classes.
     *
     * @return mixed
     */
    public function export_course_session()
    {
        if ($this->timetableSlotRepo->export_course_sessions() == true) {
            return Response::json(['status' => true]);
        }
        return Response::json(['status' => false]);
    }
}