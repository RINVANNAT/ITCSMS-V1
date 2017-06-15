<?php

namespace App\Http\Controllers\Backend\Schedule\Traits;

use App\Http\Requests\Backend\Schedule\Timetable\AddRoomIntoTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\MoveTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\RemoveRoomFromTimetableSlot;
use App\Http\Requests\Backend\Schedule\Timetable\RemoveTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\ResizeTimetableSlotRequest;
use App\Models\Configuration;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Grade;
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
        $department_id = request('department_id');

        if (isset($department_id)) {
            return Response::json(['status' => true, 'options' => DepartmentOption::where('department_id', $department_id)->get()]);
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
                return Response::json(['status' => true, 'grades' => Grade::where('id', '<=', 2)->get()]);
            } else if ($department_id == 12) {
                return Response::json(['status' => true, 'grades' => Grade::where('id', '>', 1)->get()]);
            } else if ($department_id == 13) {
                return Response::json(['status' => true, 'grades' => Grade::all()]);
            } else {
                return Response::json(['status' => true, 'grades' => Grade::where('id', '>', 2)->get()]);
            }
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

        // get group by search on slot tables.
        $groups = DB::table('course_annuals')
            ->where([
                ['academic_year_id', $academic_year_id],
                ['department_id', $department_id],
                ['degree_id', $degree_id],
                ['grade_id', $grade_id],
                ['department_option_id', $option_id]
            ])
            ->join('slots', 'slots.course_annual_id', '=', 'course_annuals.id')
            ->join('groups', 'groups.id', '=', 'slots.group_id')
            ->distinct('groups.code')
            ->select('groups.code as name', 'groups.id as id')
            ->get();

        usort($groups, function ($a, $b) {
            if (is_numeric($a->name)) {
                return $a->name - $b->name;
            } else {
                return strcmp($a->name, $b->name);
            }
        });

        if (count($groups) > 0) {
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
        $timetable_languages = new Collection();
        $timetableSlots = new Collection();
        // get student annuals.
        if ($request->department < 12) {
            $student_annual_ids = DB::table('group_student_annuals')
                ->leftJoin('studentAnnuals', 'studentAnnuals.id', '=', 'group_student_annuals.student_annual_id')
                ->where([
                    ['studentAnnuals.academic_year_id', $request->academicYear],
                    ['studentAnnuals.department_id', $request->department],
                    ['studentAnnuals.degree_id', $request->degree],
                    ['studentAnnuals.grade_id', $request->grade],
                    ['studentAnnuals.department_option_id', $request->option == null ? null : $request->option],
                    ['group_student_annuals.group_id', $request->group == null ? null : $request->group]
                ])
                ->orderBy('studentAnnuals.id')
                ->distinct('studentAnnuals.id')
                ->lists('studentAnnuals.id');

            $groupsStudentEnglishSection = DB::table('group_student_annuals')
                ->whereIn('student_annual_id', $student_annual_ids)
                ->orWhere('department_id', 12)
                ->orderBy('group_id')
                ->distinct('group_id')
                ->lists('group_id');

            $groupsStudentFrenchSection = DB::table('group_student_annuals')
                ->whereIn('student_annual_id', $student_annual_ids)
                ->orWhere('department_id', 13)
                ->orderBy('group_id')
                ->distinct('group_id')
                ->lists('group_id');

            // take studentAnnuals to find group in language section.
            $group_languages = DB::table('group_student_annuals')
                ->whereIn('student_annual_id', $student_annual_ids)
                ->orWhere([
                    ['department_id', 12],
                    ['department_id', 13]
                ])
                ->orderBy('group_id')
                ->distinct('group_id')
                ->lists('group_id');

            // get timetable from language section
            $timetableForEnglishs = new Collection();
            $timetableForFrenchs = new Collection();


            foreach ($group_languages as $group_language) {
                $getTimetableLanguage = Timetable::where([
                    ['academic_year_id', $request->academicYear],
                    ['department_id', 12], // English section
                    ['degree_id', $request->degree],
                    ['option_id', $request->option == null ? null : $request->option],
                    ['grade_id', $request->grade],
                    ['semester_id', $request->semester],
                    ['week_id', $request->weekly],
                    ['group_id', $group_language]
                ])
                    ->orWhere([
                        ['academic_year_id', $request->academicYear],
                        ['department_id', 13], // French section
                        ['degree_id', $request->degree],
                        ['option_id', $request->option == null ? null : $request->option],
                        ['grade_id', $request->grade],
                        ['semester_id', $request->semester],
                        ['week_id', $request->weekly],
                        ['group_id', $group_language]
                    ])
                    ->first();

                if ($getTimetableLanguage instanceof Timetable) {
                    $timetable_languages->push($getTimetableLanguage);
                }
            }

        }

        if (count($timetable_languages) > 0) {
            foreach ($timetable_languages as $timetable_language) {
                if (($timetable_language instanceof Timetable) && (count($timetable_language->timetableSlots) > 0)) {
                    $this->timetableSlotRepo->get_timetable_slot_with_conflict_info($timetable_language, $timetableSlots);
                }
            }
        }

        // $group_student_annual_classes = DB::table('group_student_annual')
        $timetable = $this->timetableRepo->find_timetable_is_existed($request);
        if ($timetable instanceof Timetable) {
            $this->timetableSlotRepo->get_timetable_slot_with_conflict_info($timetable, $timetableSlots);
        }
        return Response::json(['status' => true, 'timetable' => $timetable == null ? null : $timetable, 'timetableSlots' => $timetableSlots]);
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
     * @param AddRoomIntoTimetableSlotRequest $request
     * @return mixed
     */
    public function insert_room_into_timetable_slot(AddRoomIntoTimetableSlotRequest $request)
    {
        // find timetable slot by request
        $timetableSlot = TimetableSlot::find($request->timetable_slot_id);
        // check validate
        if ($timetableSlot instanceof TimetableSlot) {
            // find another timetables with the same group.
            $timetableSlots = TimetableSlot::where('group_merge_id', $timetableSlot->group_merge_id)->get();
            if (count($timetableSlots) > 1) {
                foreach ($timetableSlots as $item) {
                    // a new room
                    $item->room_id = request('room_id');
                    $item->update();
                }
            } else {
                // set room only itself
                $timetableSlot->room_id = request('room_id');
                $timetableSlot->update();

            }
            return Response::json(['status' => true]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Remove room.
     *
     * @param RemoveRoomFromTimetableSlot $removeRoomFromTimetableSlot
     * @return mixed
     */
    public function remove_room(RemoveRoomFromTimetableSlot $removeRoomFromTimetableSlot)
    {
        $timetable_slot_id = $removeRoomFromTimetableSlot->timetable_slot_id;
        if (isset($timetable_slot_id)) {
            $timetableSlot = TimetableSlot::find($timetable_slot_id);
            // find all timetable slots in the same group_merge_id
            $timetableSlots = TimetableSlot::where('group_merge_id', $timetableSlot->group_merge_id)->get();
            if (count($timetableSlots) > 1) {
                // remove room which timetable has the same group_merge_id
                foreach ($timetableSlots as $timetableSlot) {
                    $timetableSlot->room_id = null;
                    $timetableSlot->update();
                }
            } else {
                $timetableSlot->room_id = null;
                $timetableSlot->update();
            }
            return Response::json(['status' => true, 'timetable_slot' => $timetableSlot]);
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
            usort($resultArrayCanNotMergeItem, function ($a, $b) {
                if (is_numeric($a->group)) {
                    return $a->group - $b->group;
                } else {
                    return strcmp($a->group, $b->group);
                }
            });
        }

        // sort result can merge item
        if (count($resultArrayCanMergeItem) > 1) {
            usort($resultArrayCanMergeItem, function ($a, $b) {
                if (is_numeric($a->group)) {
                    return $a->group - $b->group;
                } else {
                    return strcmp($a->group, $b->group);
                }
            });
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
    public function export_course_session()
    {
        if ($this->timetableSlotRepo->export_course_sessions() == true) {
            return Response::json(['status' => true]);
        }
        return Response::json(['status' => false]);
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
        $configuration = Configuration::find(request('configuration_id'));
        if ($configuration instanceof Configuration) {
            $configuration->created_at = new Carbon(request('start'));
            $configuration->updated_at = new Carbon(request('end'));
            $configuration->update();
            $this->timetableSlotRepo->set_permission_create_timetable();
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
}