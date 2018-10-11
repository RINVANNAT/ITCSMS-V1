<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Http\Requests\Backend\Schedule\Timetable\AddRoomIntoTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\MoveTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\RemoveRoomFromTimetableSlot;
use App\Http\Requests\Backend\Schedule\Timetable\RemoveTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\ResizeTimetableSlotRequest;
use App\Models\Configuration;
use App\Models\Course;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Employee;
use App\Models\Grade;
use App\Models\Group;
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
 * Class AjaxCRUDTimetableController
 * @package App\Http\Controllers\Backend\Schedule\Traits
 */
trait AjaxCRUDTimetableController
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
     * AjaxCRUDTimetableController constructor.
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
        $dept_ids = [2, 3, 5, 6];

        $department_id = request('department_id');
        $options = DepartmentOption::where('department_id', $department_id)->get();

        if (in_array($department_id, $dept_ids)) {

            $additional_option = [
                'id' => '',
                'name_kh' => '',
                'name_en' => '',
                'name_fr' => '',
                'code' => '',
                'active' => true,
                'created_at' => Carbon::today(),
                'updated_at' => Carbon::today(),
                'department_id' => 10,
                'degree_id' => 1,
                'create_uid' => 10,
                'write_uid' => 10
            ];

            $options = collect($options)->push($additional_option);
        }

        if (isset($department_id)) {
            return Response::json(['status' => true, 'options' => $options]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Get grades by department.
     *
     * @return mixed
     */
    public function get_grades()
    {
        $department_id = request('department_id');

        if (isset($department_id)) {
            if ($department_id == 8) {
                return Response::json(['status' => true, 'grades' => Grade::where('id', '<=', 2)->orderBy('id')->get()]);
            } else if ($department_id == 12) {
                return Response::json(['status' => true, 'grades' => Grade::where('id', '>', 1)->orderBy('id')->get()]);
            } else if ($department_id == 13) {
                return Response::json(['status' => true, 'grades' => Grade::orderBy('id')->get()]);
            } else {
                return Response::json(['status' => true, 'grades' => Grade::orderBy('id')->get()]);
            }
        }
        return Response::json(['status' => false]);
    }

    /**
     * Get course sessions.
     *
     * @return mixed
     */
    public function get_course_programs()
    {
        $academic_year_id = request('academicYear');
        $department_id = request('department');
        $degree_id = request('degree');
        $grade_id = request('grade');
        $semester_id = request('semester');
        $option_id = (request('option') == null ? null : (request('option') == '' ? null : request('option')));
        $group_id = request('group') == null ? null : request('group');

        $course_program_ids = Course::where([
            ['department_id', $department_id],
            ['degree_id', $degree_id],
            ['grade_id', $grade_id],
            ['department_option_id', $option_id],
            ['semester_id', $semester_id],
        ])->pluck('id');

        $slots = Slot::join('courses', 'courses.id', '=', 'slots.course_program_id')
            ->leftJoin('employees', 'employees.id', '=', 'slots.lecturer_id')
            ->whereIn('course_program_id', $course_program_ids)
            ->where('group_id', $group_id)
            ->where('slots.academic_year_id', $academic_year_id)
            ->where('slots.time_remaining', '>', 0)
            ->select(
                'slots.id as id',
                'slots.course_program_id as course_program_id',
                'slots.time_tp as tp',
                'slots.time_td as td',
                'slots.time_course as tc',
                'slots.time_remaining as remaining',
                'courses.name_en as course_name',
                'slots.lecturer_id as lecturer_id',
                'employees.name_latin as teacher_name'
            )
            ->orderBy('courses.name_en', 'asc')
            ->get();
        return array('status' => true, 'data' => $slots, 'code' => 200);
    }

    /**
     * Get groups.
     *
     * @return mixed
     */
    public function get_groups()
    {
        $code = 200;
        $groups = Group::get()->toArray();
        $groups = $this->timetableSlotRepo->sort_groups($groups);
        if (count($groups) > 0) {
            return array('code' => $code, 'status' => true, 'groups' => $groups);
        }
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
                    ->join('roomTypes', 'roomTypes.id', '=', 'rooms.room_type_id')
                    ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
                    ->where(function ($query) {
                        if (!access()->hasRole('Administrator')) {
                            if (!access()->hasRole('Administrator')) {
                                $department_id = ((auth()->user())->employees)[0]->department_id;
                                $query->where('rooms.department_id', $department_id)
                                    ->orWhere('rooms.is_public_room', true);
                            }
                        }
                    })
                    ->where(DB::raw("CONCAT(buildings.code, '-', rooms.name)"), 'ilike', "%" . request('query') . "%")
                    ->select(
                        'rooms.id as id',
                        'rooms.name as name',
                        'buildings.code as code',
                        'rooms.nb_desk as desk',
                        'rooms.nb_chair as chair',
                        'roomTypes.name as room_type'
                    )
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
        $result = array(
            'code' => 200,
            'status' => true,
            'rooms' => []
        );

        try {
            $rooms = DB::table('rooms')
                ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
                ->join('roomTypes', 'roomTypes.id', '=', 'rooms.room_type_id')
                ->where(function ($query) {
                    if (!access()->hasRole('Administrator')) {
                        if (!access()->hasRole('Administrator')) {
                            $department_id = ((auth()->user())->employees)[0]->department_id;
                            $query->where('rooms.department_id', $department_id)
                                ->orWhere('rooms.is_public_room', true);
                        }
                    }
                })
                ->select(
                    'rooms.id as id',
                    'rooms.name as name',
                    'buildings.code as code',
                    'rooms.nb_desk as desk',
                    'rooms.nb_chair as chair',
                    'roomTypes.name as room_type'
                )
                ->get();
            $result['rooms'] = $rooms;

        } catch (\Exception $e) {
            $result['code'] = $e->getCode();
            $result['status'] = false;
        }

        return $result;
    }

    /**
     * Get timetable slots.
     *
     * @param CreateTimetableRequest $request
     * @return mixed
     */
    public function get_timetable_slots(CreateTimetableRequest $request)
    {
        $result = array(
            'code' => 200,
            'message' => 'Successfully',
            'timetable' => null,
            'timetableSlots' => [],
        );

        try {
            $timetableSlots = new Collection();
            $timetable = $this->timetableRepo->find_timetable_is_existed($request);
            if (($request->filter_language == "true") && !($request->department == 12 || $request->department == 13)) {
                if ($request->department < 12 && ($timetable instanceof Timetable)) {
                    $this->timetableSlotRepo->get_timetable_slot_language_dept($timetableSlots, $timetable);
                }
            }

            if ($timetable instanceof Timetable) {
                $this->timetableSlotRepo->get_timetable_slot_with_conflict_info($timetable, $timetableSlots, null);
            }

            if (!is_null($timetable)) {
                $result['timetable'] = $timetable;
                $result['timetableSlots'] = $timetableSlots;
            }

        } catch (\Exception $e) {
            $result['code'] = $e->getCode();
            $result['message'] = $e->getMessage();
            $request['status'] = false;
        }
        return $result;
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
                $timetable_slot->updated_at = Carbon::now();
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
        $result = array(
            'code' => 200,
            'message' => 'The operation was executed successfully',
            'data' => [],
            'status' => true,
            'timetable_slot' => []
        );

        try {
            $timetable_slot = TimetableSlot::find($request->timetable_slot_id);
            if ($timetable_slot instanceof TimetableSlot) {
                $old_durations = $timetable_slot->durations;
                $new_durations = $this->timetableSlotRepo->durations(new Carbon($timetable_slot->start), new Carbon($request->end));
                $timetable_slot->durations = $new_durations;
                $timetable_slot->end = new Carbon($request->end);
                $timetable_slot->updated_at = Carbon::now();

                $slot = Slot::find($timetable_slot->slot_id);

                if (($slot->time_remaining >= ($new_durations - $old_durations)) && $slot->time_remaining >= 0) {
                    if ($new_durations > $old_durations) {
                        $interval = $new_durations - $old_durations;
                        $slot->time_remaining = $slot->time_remaining - $interval;
                    } else {
                        $interval = $old_durations - $new_durations;
                        $slot->time_remaining = $slot->time_remaining + $interval;
                    }
                    if ($slot->update()) {
                        $timetable_slot->update();
                        $this->timetableSlotRepo->update_merge_timetable_slot($timetable_slot);
                    }
                    $result['timetable_slot'] = $timetable_slot;
                }
            }
        } catch (\Exception $e) {
            $result['message'] = $e->getMessage();
            $result['code'] = $e->getCode();
            $result['status'] = false;
        }

        return $result;
    }

    /**
     * Insert room into timetable slot.
     *
     * @param AddRoomIntoTimetableSlotRequest $request
     * @return mixed
     */
    public function insert_room_into_timetable_slot(AddRoomIntoTimetableSlotRequest $request)
    {
        $result = array(
            'code' => 200,
            'status' => true,
            'data' => []
        );

        try {
            $timetableSlot = TimetableSlot::find($request->timetable_slot_id);
            if ($timetableSlot instanceof TimetableSlot) {
                $timetableSlots = TimetableSlot::where('group_merge_id', $timetableSlot->group_merge_id)->get();
                if (count($timetableSlots) > 1) {
                    foreach ($timetableSlots as $item) {
                        $item->room_id = request('room_id');
                        $item->update();
                    }
                } else {
                    $timetableSlot->room_id = request('room_id');
                    $timetableSlot->update();

                }
            }
        } catch (\Exception $e) {
            $result['code'] = $e->getCode();
            $result['status'] = false;
        }
        return $result;
    }

    /**
     * Remove room.
     *
     * @param RemoveRoomFromTimetableSlot $removeRoomFromTimetableSlot
     * @return mixed
     */
    public function remove_room(RemoveRoomFromTimetableSlot $removeRoomFromTimetableSlot)
    {
        $result = array(
            'code' => 200,
            'status' => true,
            'data' => []
        );

        try {
            $timetable_slot_id = $removeRoomFromTimetableSlot->timetable_slot_id;
            if (isset($timetable_slot_id)) {
                $timetableSlot = TimetableSlot::find($timetable_slot_id);
                $timetableSlots = TimetableSlot::where('group_merge_id', $timetableSlot->group_merge_id)->get();
                if (count($timetableSlots) > 1) {
                    foreach ($timetableSlots as $timetableSlot) {
                        $timetableSlot->room_id = null;
                        $timetableSlot->update();
                    }
                } else {
                    $timetableSlot->room_id = null;
                    $timetableSlot->update();
                }
            }
        } catch (\Exception $e) {
            $result['code'] = $e->getCode();
            $result['status'] = false;
        }

        return $result;
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
                    ->join('roomTypes', 'roomTypes.id', '=', 'rooms.room_type_id')
                    ->where([
                        ['timetables.academic_year_id', $academic_year_id],
                        ['timetables.week_id', $week_id],
                        ['timetable_slots.start', $timetable_slot->start],
                        ['timetable_slots.end', $timetable_slot->end]
                    ])
                    ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
                    ->where(function ($query) {
                        if (!access()->hasRole('Administrator')) {
                            if (!access()->hasRole('Administrator')) {
                                $department_id = ((auth()->user())->employees)[0]->department_id;
                                $query->where('rooms.department_id', $department_id)
                                    ->orWhere('rooms.is_public_room', true);
                            }
                        }
                    })
                    ->where(DB::raw("CONCAT(buildings.code, '-', rooms.name)"), 'ilike', "%" . $query . "%")
                    ->whereNotNull('timetable_slots.room_id')
                    ->select(
                        'rooms.id as id',
                        'rooms.name as name',
                        'buildings.code as code',
                        'rooms.nb_desk as desk',
                        'rooms.nb_chair as chair',
                        'roomTypes.name as room_type'
                    )
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
                    ->where(function ($query) {
                        if (!access()->hasRole('Administrator')) {
                            if (!access()->hasRole('Administrator')) {
                                $department_id = ((auth()->user())->employees)[0]->department_id;
                                $query->where('rooms.department_id', $department_id)
                                    ->orWhere('rooms.is_public_room', true);
                            }
                        }
                    })
                    ->where(DB::raw("CONCAT(buildings.code, '-', rooms.name)"), 'ilike', "%" . $query . "%")
                    ->whereNotNull('timetable_slots.room_id')
                    ->lists('timetable_slots.room_id');

                $rooms_remaining = DB::table('rooms')
                    ->join('buildings', 'buildings.id', '=', 'rooms.building_id')
                    ->join('roomTypes', 'roomTypes.id', '=', 'rooms.room_type_id')
                    ->where(function ($query) {
                        if (!access()->hasRole('Administrator')) {
                            if (!access()->hasRole('Administrator')) {
                                $department_id = ((auth()->user())->employees)[0]->department_id;
                                $query->where('rooms.department_id', $department_id)
                                    ->orWhere('rooms.is_public_room', true);
                            }
                        }
                    })
                    ->whereNotIn('rooms.id', $rooms_tmp == [] ? [] : $rooms_tmp)
                    ->where(DB::raw("CONCAT(buildings.code, '-', rooms.name)"), 'ilike', "%" . $query . "%")
                    ->select(
                        'rooms.id as id',
                        'rooms.name as name',
                        'buildings.code as code',
                        'rooms.nb_desk as desk',
                        'rooms.nb_chair as chair',
                        'roomTypes.name as room_type'
                    )
                    ->get();

                return Response::json([
                    'status' => true,
                    'roomUsed' => $rooms_used,
                    'roomRemain' => $rooms_remaining
                ]);
            }
        }
    }

    /**
     * Get conflict information to show pop-up window.
     *
     * @return mixed
     */
    public function get_conflict_info()
    {
        $timetableSlot = TimetableSlot::find(request('timetable_slot_id'));
        // check conflict room.
        if ($this->timetableSlotRepo->is_conflict_room($timetableSlot)[0]['status'] == true) {
            $conflicts['is_conflict_room'] = true;
            $with_timetable_slot = TimetableSlot::find($this->timetableSlotRepo->is_conflict_room($timetableSlot)[0]['conflict_with']->id);
            $info = $this->timetableSlotRepo->get_conflict_with($with_timetable_slot);
            $conflicts['room_info'] = $info;
        } else {
            $conflicts['is_conflict_room'] = false;
            $conflicts['room_info'] = null;
        }

        // check conflict lecturer.
        $lecturer = new Collection();

        $canMerge = $this->timetableSlotRepo->check_conflict_lecturer($timetableSlot)['canMerge'];
        $canNotMerge = $this->timetableSlotRepo->check_conflict_lecturer($timetableSlot)['canNotMerge'];
        $arrayCanMerge = array();
        $arrayCanNotMerge = array();
        // check each can merge item to get conflict details.
        if (count($canMerge) > 0) {
            foreach ($canMerge as $item) {
                array_push($arrayCanMerge, $this->timetableSlotRepo->get_conflict_with(TimetableSlot::find($item->id)));
            }
        }

        // check each can not merge item to get conflict details.
        if (count($canNotMerge) > 0) {
            foreach ($canNotMerge as $item) {
                array_push($arrayCanNotMerge, $this->timetableSlotRepo->get_conflict_with(TimetableSlot::find($item->id)));
            }
        }

        // declare Can or Can't merge result item.
        $resultArrayCanNotMergeItem = array();
        $resultArrayCanMergeItem = array();

        // add arrayCanNotMerge into resultArrayCanNotMergeItem
        if (count($arrayCanNotMerge) > 0) {
            for ($i = 0; $i < count($arrayCanNotMerge); $i++) {
                array_push($resultArrayCanNotMergeItem, $arrayCanNotMerge[$i][0]);
            }
        }

        // add arrayCanMerge into resultArrayCanMergeItem
        if (count($arrayCanMerge) > 0) {
            for ($i = 0; $i < count($arrayCanMerge); $i++) {
                array_push($resultArrayCanMergeItem, $arrayCanMerge[$i][0]);
            }
        }

        // sort result can not merge item
        if (count($resultArrayCanNotMergeItem) > 1) {
            /*usort($resultArrayCanNotMergeItem, function ($a, $b) {
                if (is_numeric($a->group)) {
                    return $a->group - $b->group;
                } else {
                    return strcmp($a->group, $b->group);
                }
            });*/
        }

        // sort result can merge item
        if (count($resultArrayCanMergeItem) > 1) {
            /*usort($resultArrayCanMergeItem, function ($a, $b) {
                if (is_numeric($a->group)) {
                    return $a->group - $b->group;
                } else {
                    return strcmp($a->group, $b->group);
                }
            });*/
        }

        // put can or can't merge to lecturer collection.
        $lecturer->put('canNotMerge', $resultArrayCanNotMergeItem);
        $lecturer->put('canMerge', $resultArrayCanMergeItem);

        // merge those two to conflicts.
        $conflicts['lecturer'] = $lecturer;
        (count($resultArrayCanMergeItem) > 0 || count($resultArrayCanNotMergeItem) > 0) ? $conflicts['lecturer_conflict'] = true : $conflicts['lecturer_conflict'] = false;

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
            // find all timetable slots conflict with and can merge together
            $canMerge = $this->timetableSlotRepo->check_conflict_lecturer($timetableSlot)['canMerge'];
            foreach ($canMerge as $item) {
                // array_push($result, $this->timetableSlotRepo->update_timetable_slot_when_merge($item, $timetableSlot->group_merge_id));
                // update group merge id for each item
                DB::table('merge_timetable_slots')->where('id', '=', $item->group_merge_id)->delete();
                $this->timetableSlotRepo->update_timetable_slot_when_merge($item, $timetableSlot->group_merge_id);
            }
        }
        // add room
        $room_id = $this->timetableSlotRepo->find_room_existed_merge_timetable_slot($timetableSlot->group_merge_id);
        if (isset($room_id)) {
            $timetableSlots = TimetableSlot::where('group_merge_id', $timetableSlot->group_merge_id)->get();
            foreach ($timetableSlots as $timetableSlot) {
                $timetableSlot->room_id = $room_id;
                $timetableSlot->update();
            }
        }
        // return result
        return Response::json(['status' => false]);
    }

    /**
     * Export data from course session to slot and course annual classes to slot classes.
     *
     * @return mixed
     */
    public function export_course_program()
    {
        $data = request()->all();
        $group_id = is_null($data['group_id']) ? null : $data['group_id'];
        $course_programs = Course::where([
            ['department_id', $data['department_id']],
            ['degree_id', $data['degree_id']],
            ['department_option_id', isset($data['option_id']) ? ($data['option_id'] == '' ? null : $data['option_id']) : null],
            ['grade_id', $data['grade_id']],
            ['semester_id', $data['semester_id']]
        ])->get();

        $amountCourseProgramImported = 0;

        if (count($course_programs) > 0) {
            foreach ($course_programs as $course_program) {
                $slots = Slot::where([
                    ['course_program_id', $course_program->id],
                    ['academic_year_id', $data['academic_year_id']],
                    ['semester_id', $data['semester_id']],
                    ['group_id', $group_id],
                ])->get();
                if (count($slots) == 0) {
                    if ($course_program->time_tp > 0) {
                        DB::transaction(function () use ($data, $course_program, $group_id, &$amountCourseProgramImported) {
                            $newSlot = new Slot();
                            $newSlot->time_tp = $course_program->time_tp;
                            $newSlot->time_td = 0;
                            $newSlot->time_course = 0;
                            $newSlot->academic_year_id = $data['academic_year_id'];
                            $newSlot->course_program_id = $course_program->id;
                            $newSlot->lecturer_id = null;
                            $newSlot->semester_id = $data['semester_id'];
                            $newSlot->time_used = 0;
                            $newSlot->group_id = $group_id;
                            $newSlot->time_remaining = $course_program->time_tp;
                            $newSlot->created_uid = auth()->user()->id;
                            $newSlot->write_uid = auth()->user()->id;
                            $newSlot->save();
                            $amountCourseProgramImported++;
                        });
                    }
                    if ($course_program->time_td > 0) {
                        DB::transaction(function () use ($data, $course_program, $group_id, &$amountCourseProgramImported) {
                            $newSlot = new Slot();
                            $newSlot->time_tp = 0;
                            $newSlot->time_td = $course_program->time_td;
                            $newSlot->time_course = 0;
                            $newSlot->lecturer_id = null;
                            $newSlot->academic_year_id = $data['academic_year_id'];
                            $newSlot->course_program_id = $course_program->id;
                            $newSlot->semester_id = $data['semester_id'];
                            $newSlot->group_id = $group_id;
                            $newSlot->time_used = 0;
                            $newSlot->time_remaining = $course_program->time_td;
                            $newSlot->created_uid = auth()->user()->id;
                            $newSlot->write_uid = auth()->user()->id;
                            $newSlot->save();
                            $amountCourseProgramImported++;
                        });
                    }
                    if ($course_program->time_course > 0) {
                        DB::transaction(function () use ($data, $course_program, $group_id, &$amountCourseProgramImported) {
                            $newSlot = new Slot();
                            $newSlot->time_tp = 0;
                            $newSlot->time_td = 0;
                            $newSlot->time_course = $course_program->time_course;
                            $newSlot->lecturer_id = null;
                            $newSlot->academic_year_id = $data['academic_year_id'];
                            $newSlot->course_program_id = $course_program->id;
                            $newSlot->semester_id = $data['semester_id'];
                            $newSlot->group_id = $group_id;
                            $newSlot->time_used = 0;
                            $newSlot->time_remaining = $course_program->time_course;
                            $newSlot->created_uid = auth()->user()->id;
                            $newSlot->write_uid = auth()->user()->id;
                            $newSlot->save();
                            $amountCourseProgramImported++;
                        });
                    }
                }
            }
            return message_success($amountCourseProgramImported);
        } else {
            return message_error('There are 0 courses are program found.');
        }
    }

    /**
     * Remove timetable slot.
     *
     * @param RemoveTimetableSlotRequest $removeTimetableSlotRequest
     * @return mixed
     */
    public function remove_timetable_slot(RemoveTimetableSlotRequest $removeTimetableSlotRequest)
    {
        $timetable_slot_id = $removeTimetableSlotRequest->timetable_slot_id;

        if (isset($timetable_slot_id)) {
            $timetableSlot = TimetableSlot::find($timetable_slot_id);
            // take duration and slot_id field
            $slot = Slot::find($timetableSlot->slot_id);
            // update time_remaining field
            $slot->time_remaining = $slot->time_remaining + $timetableSlot->durations;
            $slot->updated_at = Carbon::now();
            $slot->write_uid = auth()->user()->id;
            if ($slot->update() && $timetableSlot->delete()) {
                return Response::json(['status' => true]);
            }
        }
        return Response::json(['status' => false]);
    }

    /**
     * Assign department can create tiemtable.
     *
     * @return mixed
     */
    public function assign_turn_create_timetable()
    {
        $result = false;
        /** key format: timetable_department_id. Example: key: timetable_1 */

        if (count(request('departments')) > 0) {
            foreach (request('departments') as $item) {
                if (Configuration::where('key', 'timetable_' . $item)->first() instanceof Configuration) {
                    return Response::json(['status' => $result, 'message' => 'The key: timetable_' . $item . ' value already existed']);
                }
            }

            foreach (request('departments') as $item) {
                $newAssignCreateTimetable = new Configuration();
                $newAssignCreateTimetable->key = 'timetable_' . $item;
                $newAssignCreateTimetable->value = $item;
                $newAssignCreateTimetable->created_at = new Carbon(request('start'));
                $newAssignCreateTimetable->updated_at = new Carbon(request('end'));
                $newAssignCreateTimetable->description = 'true';
                $newAssignCreateTimetable->create_uid = auth()->user()->id;
                $newAssignCreateTimetable->write_uid = auth()->user()->id;
                if ($newAssignCreateTimetable->save()) {
                    $result = true;
                } else {
                    $result = false;
                    break;
                }
            }

            $this->timetableSlotRepo->set_permission_create_timetable();

            if ($result) {
                return Response::json(['status' => $result, 'message' => 'All those department are assigned.']);
            }
        } else {
            return Response::json(['status' => $result, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Get timetable assignment.
     *
     * @return mixed
     */
    public function get_timetable_assignment()
    {
        $timetable_assignments = Configuration::where('key', 'like', 'timetable_%')->select('id', 'value', 'key', 'description', 'created_at', 'updated_at')->get();

        $departments = new Collection();
        if (count($timetable_assignments) > 0) {
            foreach ($timetable_assignments as $assignment) {
                $department = new Collection(Department::where('id', $assignment->value)->select('code')->first());
                $department->put('start', (new Carbon($assignment->created_at))->toDateString());
                $department->put('end', (new Carbon($assignment->updated_at))->toDateString());
                $department->put('description', $assignment->description);
                $department->put('key_id', $assignment->id);
                $departments->push($department);
            }
            return Response::json(['status' => true, 'departments' => $departments]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Delete timetable assignment.
     *
     * @return mixed
     * @throws \Exception
     */
    public function assign_delete()
    {
        $id = request('id');

        if (isset($id)) {
            $configuration = Configuration::find($id);
            if ($configuration instanceof Configuration) {
                $configuration->delete();
                return Response::json(['status' => true]);
            }
        }
        return Response::json(['status' => false]);
    }

    /**
     * Update Timetable Assignment.
     *
     * @return mixed
     */
    public function assign_update()
    {
        $now = Carbon::now('Asia/Phnom_Penh');
        $configuration = Configuration::find(request('configuration_id'));
        if ($configuration instanceof Configuration) {
            $configuration->created_at = new Carbon(request('start'));
            $configuration->updated_at = new Carbon(request('end'));

            if ((strtotime($now) >= strtotime(new Carbon(request('start')))) && (strtotime($now) <= strtotime(new Carbon(request('end'))))) {
                $configuration->description = 'true';
                $configuration->timestamps = false;

            } else if (strtotime($now) > strtotime(new Carbon(request('end')))) {
                $configuration->description = 'finished';
                $configuration->timestamps = false;
            } else {
                $configuration->description = 'false';
                $configuration->timestamps = false;
            }
            $configuration->update();
            return Response::json(['status' => true]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Public Timetable.
     *
     * @return mixed
     */
    public function publish()
    {
        // find timetable.
        $timetable = Timetable::where([
            ['academic_year_id', request('academicYear')],
            ['department_id', request('department')],
            ['degree_id', request('degree')],
            ['grade_id', request('grade')],
            ['option_id', request('option') == null ? null : request('option')],
            ['group_id', request('group') == null ? null : request('group')],
            ['semester_id', request('semester')],
            ['week_id', request('weekly')]
        ])->first();
        if ($timetable instanceof Timetable) {
            $timetable->completed = true;
            $timetable->update();
            return Response::json([200]);
        }
    }

    /**
     * Get configuration back to frontend.
     *
     * @return mixed
     */
    public function update_assign_timetable()
    {
        $configuration = Configuration::find(request('id'));
        return Response::json(['status' => true, 'start' => (new Carbon($configuration->created_at))->toDateString(), 'end' => (new Carbon($configuration->updated_at))->toDateString()]);
    }

    /**
     * Search groups.
     *
     * @return array
     */
    public function search_course_program()
    {
        $academic_year_id = request('academic');
        $department_id = request('department');
        $degree_id = request('degree');
        $grade_id = request('grade');
        $semester_id = request('semester');
        $option_id = (request('option') == null ? null : (request('option') == '' ? null : request('option')));
        $group_id = request('group') == null ? null : request('group');

        $course_program_ids = Course::where([
            ['department_id', $department_id],
            ['degree_id', $degree_id],
            ['grade_id', $grade_id],
            ['department_option_id', $option_id],
            ['semester_id', $semester_id],
        ])->pluck('id');


        $slots = Slot::join('courses', 'courses.id', '=', 'slots.course_program_id')
            ->leftJoin('employees', 'employees.id', '=', 'slots.lecturer_id')
            ->whereIn('course_program_id', $course_program_ids)
            ->where('group_id', $group_id)
            ->where('slots.academic_year_id', $academic_year_id)
            ->where('slots.time_remaining', '>', 0)
            ->where(function ($query) {
                $query->whereRaw('LOWER(courses.name_en) LIKE ?', array('%' . strtolower(request('query')) . '%'))
                    ->orWhereRaw('LOWER(courses.name_kh) LIKE ?', array('%' . strtolower(request('query')) . '%'))
                    ->orWhereRaw('LOWER(employees.name_latin) LIKE ?', array('%' . strtolower(request('query')) . '%'))
                    ->orWhereRaw('LOWER(employees.name_kh) LIKE ?', array('%' . strtolower(request('query')) . '%'));
            })
            ->select(
                'slots.id as id',
                'slots.course_program_id as course_program_id',
                'slots.time_tp as tp',
                'slots.time_td as td',
                'slots.time_course as tc',
                'slots.time_remaining as remaining',
                'courses.name_en as course_name',
                'slots.lecturer_id as lecturer_id',
                'employees.name_latin as teacher_name'
            )->get();
        return array('status' => true, 'course_sessions' => $slots, 'code' => 200);
    }

    /**
     * Get Employees.
     *
     * @return array
     */
    public function get_employees()
    {
        $query = request('query');
        $employees = Employee::join('genders', 'employees.gender_id', '=', 'genders.id')
            ->join('departments', 'departments.id', '=', 'employees.department_id')
            ->where(function ($sql) use ($query) {
                if (isset($query) && !is_null($query) && $query != '') {
                    $sql->orWhere('employees.name_kh', 'ilike', '%' . $query . '%')
                        ->orWhere('employees.name_latin', 'ilike', '%' . $query . '%')
                        ->orWhere('departments.code', 'ilike', '%' . $query . '%');
                }
            })
            ->select([
                'employees.id as employee_id',
                'employees.name_kh as employee_name_kh',
                'employees.name_latin as employee_name_latin',
                'employees.id_card as id_card',
                'genders.code as gender_code',
                'departments.code as department_code'
            ])
            ->orderBy('employee_name_kh', 'asc')
            ->get();
        return array('status' => true, 'code' => 200, 'data' => $employees);
    }

    public function assign_lecturer_to_course_program()
    {
        $result = [
            'code' => 200,
            'data' => [],
            'message' => "The operation was executed successfully"
        ];

        $slot_id = request('slot_id');
        $lecturer_id = request('lecturer_id');
        if (isset($slot_id) && !is_null($slot_id)) {
            try {
                DB::transaction(function () use ($slot_id, $lecturer_id) {
                    $slot = Slot::find($slot_id);
                    $slot->lecturer_id = $lecturer_id;
                    $slot->write_uid = auth()->user()->id;
                    $slot->updated_at = Carbon::now();
                    $slot->update();
                });
            } catch (\Exception $e) {
                $result['code'] = $e->getCode();
                $result['message'] = $e->getMessage();
            }
        }

        return $result;
    }

    public function assign_lecturer_to_timetable_slot()
    {
        $result = [
            'code' => 200,
            'data' => [],
            'message' => "The operation was executed successfully"
        ];

        $timetable_slot_id = request('timetable_slot_id');
        $lecturer_id = request('lecturer_id');
        if (isset($timetable_slot_id) && !is_null($timetable_slot_id)) {
            try {
                DB::transaction(function () use ($timetable_slot_id, $lecturer_id) {
                    $timetable_slot = TimetableSlot::find($timetable_slot_id);
                    $timetable_slot->lecturer_id = $lecturer_id;
                    $timetable_slot->updated_at = Carbon::now();
                    $timetable_slot->update();
                });
            } catch (\Exception $e) {
                $result['code'] = $e->getCode();
                $result['message'] = $e->getMessage();
            }
        }

        return $result;
    }
}