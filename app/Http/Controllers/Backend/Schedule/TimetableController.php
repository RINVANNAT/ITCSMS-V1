<?php

namespace App\Http\Controllers\Backend\Schedule;

use App\Http\Controllers\Backend\Schedule\Traits\CloneTimetableTrait;
use App\Http\Controllers\Backend\Schedule\Traits\TimetableSessionTrait;
use App\Http\Controllers\Backend\Schedule\Traits\TimetableSlotTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Schedule\Timetable\AddRoomIntoTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\DeleteTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\MoveTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\RemoveTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\ResizeTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\ShowTimetableRequest;
use App\Models\AcademicYear;
use App\Models\Configuration;
use App\Models\Course;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Employee;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Room;
use App\Models\Schedule\Timetable\MergeTimetableSlot;
use App\Models\Schedule\Timetable\Slot;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableGroup;
use App\Models\Schedule\Timetable\TimetableGroupSession;
use App\Models\Schedule\Timetable\TimetableGroupSlot;
use App\Models\Schedule\Timetable\TimetableSlot;
use App\Models\Schedule\Timetable\Week;
use App\Models\Semester;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

/**
 * Class TimetableController
 * @package App\Http\Controllers\Backend\Schedule
 */
class TimetableController extends Controller
{
    use CloneTimetableTrait,
        TimetableSlotTrait,
        TimetableSessionTrait;

    public function index()
    {
        $now = Carbon::now('Asia/Phnom_Penh');
        $employee = Employee::where('user_id', auth()->user()->id)->first();

        if ($employee instanceof Employee) {
            $createTimetablePermissionConfiguration = Configuration::where('key', 'timetable_' . $employee->department_id)->first();
        } else {
            $createTimetablePermissionConfiguration = null;
        }

        return view('backend.schedule.timetables.index')->with([
            'academicYears' => AcademicYear::latest()->get(),
            'departments' => Department::where('parent_id', 11)->get(),
            'degrees' => Degree::all(),
            'grades' => Grade::all(),
            'options' => DepartmentOption::all(),
            'semesters' => Semester::all(),
            'weeks' => Week::all(),
            'createTimetablePermissionConfiguration' => $createTimetablePermissionConfiguration,
            'now' => $now
        ]);
    }

    public function get_timetables()
    {
        $academic_year_id = request('academicYear');
        $department_id = request('department');
        $degree_id = request('degree');
        $grade_id = request('grade');
        $option_id = request('option');
        $semester_id = request('semester');

        $timetables = Timetable::join('weeks', 'weeks.id', '=', 'timetables.week_id')
            ->join('academicYears', 'academicYears.id', '=', 'timetables.academic_year_id')
            ->join('departments', 'departments.id', '=', 'timetables.department_id')
            ->join('degrees', 'degrees.id', '=', 'timetables.degree_id')
            ->join('grades', 'grades.id', '=', 'timetables.grade_id')
            ->leftJoin('departmentOptions', 'departmentOptions.id', '=', 'timetables.option_id')
            ->join('semesters', 'semesters.id', '=', 'timetables.semester_id')
            ->where([
                ['academicYears.id', $academic_year_id],
                ['departments.id', $department_id],
                ['degrees.id', $degree_id],
                ['grades.id', $grade_id],
                ['semesters.id', $semester_id],
            ]);

        if ($option_id !== 'Option' && $option_id != null) {
            $timetables->where('departmentOptions.id', $option_id);
        }

        if (request('week') != null) {
            $timetables->where('weeks.name_en', request('week'));
        }
        $timetables->orderBy('weeks.id', 'asc')
            ->select([
                'weeks.name_en as week',
                'timetables.completed as status',
                'timetables.id as id'
            ]);

        return Datatables::of($timetables)
            ->addColumn('action', function ($timetable) {
                $export = ' <button id="export-timetable"  href="' . route('timetables.export', $timetable->id) . '" class="btn btn-xs btn-primary">'
                    . '<i class="fa fa-download" data-toggle="tooltip"'
                    . 'data-placement="top" title="Export"'
                    . 'data-original-title="Export">'
                    . '</i>'
                    . '</button> ';
                $print = ' <button id="print-timetable" href="' . route('timetables.print', $timetable->id) . '" class="btn btn-xs btn-success">'
                    . '<i class="fa fa-print" data-toggle="tooltip"'
                    . 'data-placement="top" title="Print"'
                    . 'data-original-title="Print">'
                    . '</i>'
                    . '</button> ';

                $view = '<a href="' . route('admin.schedule.timetables.show', $timetable->id) . '" class="btn btn-xs btn-info">'
                    . '<i class="fa fa-eye" data-toggle="tooltip"'
                    . 'data-placement="top" title="View"'
                    . 'data-original-title="View">'
                    . '</i></a>';

                $delete = ' <button id="' . $timetable->id . '" class="btn btn-xs btn-danger btn_delete_timetable">'
                    . '<i class="fa fa-trash" data-toggle="tooltip"'
                    . 'data-placement="top" title="Delete"'
                    . 'data-original-title="Delete">'
                    . '</i>'
                    . '</button> ';

                $result = '';
                if (access()->allow('export-timetable')) {
                    $result .= $export;
                }
                if (access()->allow('print-timetable')) {
                    $result .= $print;
                }
                if (access()->allow('delete-timetable')) {
                    $result .= $delete;
                }
                if (access()->allow('view-timetable')) {
                    $result .= $view;
                }
                return $result;
            })
            ->editColumn('status', function ($timetable) {
                if ($timetable->status == false) {
                    $view = '<span class="btn btn-danger btn-xs">'
                        . '<i class="fa fa-times-circle"'
                        . 'data-toggle="tooltip"'
                        . 'data-placement="top" title="Unpublished"'
                        . 'data-original-title="Unpublished"></i>'
                        . '</span>';
                } else {
                    $view = '<span class="btn btn-success btn-xs">'
                        . '<i class="fa fa-check"'
                        . 'data-toggle="tooltip"'
                        . 'data-placement="top" title="Published"'
                        . 'data-original-title="Published"></i>'
                        . '</span>';
                }
                return $view;
            })
            ->make(true);
    }

    public function create($academic = null, $department = null, $degree = null, $option = null, $grade = null, $semester = null, $group = null, $week = null)
    {
        $now = Carbon::now('Asia/Phnom_Penh');
        $employee = Employee::where('user_id', auth()->user()->id)->first();
        $degrees = Degree::all();
        $groups = TimetableGroup::select('code', 'id')->get();
        if ($employee instanceof Employee) {
            $createTimetablePermissionConfiguration = Configuration::where('key', 'timetable_' . $employee->department_id)->first();
        } else {
            $createTimetablePermissionConfiguration = null;
        }

        if (isset($option)) {
            $dept_ids = [2, 3, 5, 6];

            $department_id = request('department_id');
            $options_ = DepartmentOption::where('department_id', $department)->get();

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

                $options_ = collect($options_)->push($additional_option);
            }
        } else {
            $options_ = null;
        }

        $weeks = Week::where('semester_id', $semester)->get();

        $timetableGroups = TimetableGroup::with('parent')->get();

        if (isset($createTimetablePermissionConfiguration)) {
            if (access()->allow('create-timetable') && (strtotime($now) >= strtotime($createTimetablePermissionConfiguration->created_at) && strtotime($now) <= strtotime($createTimetablePermissionConfiguration->updated_at))) {
                return view('backend.schedule.timetables.create')->with([
                    'academic_year_id' => $academic,
                    'department_id' => $department,
                    'degree_id' => $degree,
                    'option_id' => $option,
                    'grade_id' => $grade,
                    'semester_id' => $semester,
                    'group_id' => $group,
                    'week_id' => $week,
                    'groups' => $groups,
                    'options_' => $options_,
                    'weeks_' => $weeks,
                    'degrees' => $degrees,
                    'timetable_groups' => $timetableGroups
                ]);
            }
        } else {
            return view('backend.schedule.timetables.create')->with([
                'academic_year_id' => $academic,
                'department_id' => $department,
                'degree_id' => $degree,
                'option_id' => $option,
                'grade_id' => $grade,
                'semester_id' => $semester,
                'group_id' => $group,
                'week_id' => $week,
                'groups' => $groups,
                'options_' => $options_,
                'weeks_' => $weeks,
                'degrees' => $degrees,
                'timetable_groups' => $timetableGroups
            ]);
        }

        return abort(404);
    }

    public function show(Timetable $timetable, ShowTimetableRequest $showTimetableRequest)
    {
        $timetable_slots = TimetableSlot::where('timetable_id', $timetable->id)
            ->leftJoin('rooms', 'rooms.id', '=', 'timetable_slots.room_id')
            ->leftJoin('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->select(
                'timetable_slots.id',
                'timetable_slots.course_name as title',
                'timetable_slots.course_name',
                'timetable_slots.type as type',
                'timetable_slots.start',
                'timetable_slots.end',
                'buildings.code as building',
                'rooms.name as room'
            )
            ->get();
        $timetableSlots = new Collection();

        foreach ($timetable_slots as $timetable_slot) {
            if (($timetable_slot instanceof TimetableSlot) && is_object($timetable_slot)) {

                $newTimetableSlot = TimetableSlot::with('employee')->find($timetable_slot->id);
                $timetableSlot = new Collection($newTimetableSlot);
                $timetableSlot->put('building', $timetable_slot->building);
                $timetableSlot->put('room', $timetable_slot->room);
                $timetableSlots->push($timetableSlot);
            }
        }

        $options = [
            $timetable->academic_year_id,
            $timetable->department_id,
            $timetable->degree_id,
            isset($timetable->option_id) ? $timetable->option_id : 0,
            $timetable->grade_id,
            $timetable->semester_id,
            isset($timetable->group_id) ? $timetable->group_id : 0,
            $timetable->week_id
        ];

        return view('backend.schedule.timetables.show', compact('timetableSlots', 'timetable', 'now', 'createTimetablePermissionConfiguration', 'options'));
    }

    public function store(CreateTimetableSlotRequest $request, CreateTimetableRequest $requestTimetable)
    {
        $timetable = $this->firstOrCreateTimetable($requestTimetable);
        $timetableSlots = [];
        if ($timetable instanceof Timetable) {
            $timetableSlots = $this->createTimetableSlot($timetable, $request);
        } else {
            $newTimetable = $this->createNewTimetable($requestTimetable);
            if ($newTimetable instanceof Timetable) {
                $timetableSlots = $this->createTimetableSlot($newTimetable, $request);
            }
        }
        return [
            'status' => true,
            'timetable_slot' => json_decode($timetableSlots)
        ];
    }

    private function firstOrCreateTimetable(CreateTimetableRequest $request)
    {
        $this->validate($request, [
            'academicYear' => 'required',
            'department' => 'required',
            'degree' => 'required',
            'grade' => 'required',
            'semester' => 'required',
            'weekly' => 'required',
        ]);
        $timetable = Timetable::where([
            'academic_year_id' => $request->academicYear,
            'department_id' => $request->department,
            'degree_id' => $request->degree,
            'grade_id' => $request->grade,
            'option_id' => $request->option == null ? null : $request->option,
            'semester_id' => $request->semester,
            'week_id' => $request->weekly,
        ])->first();

        if ($timetable instanceof Timetable) {
            return $timetable;
        }
        return false;
    }

    private function createTimetableSlot(Timetable $timetable, CreateTimetableSlotRequest $request)
    {
        DB::beginTransaction();
        try {
            $newMergeTimetableSlot = $this->createMergeTimetableSlot($request);
            if ($newMergeTimetableSlot instanceof MergeTimetableSlot) {
                $slot = Slot::with('groups')->find($request->slot_id);
                if ($slot instanceof Slot) {
                    $start = new Carbon($request->start);
                    $end = new Carbon($request->end == null ? $request->start : $request->end);
                    $duration = $this->durations($start, $end);
                    $groups = $slot->groups->pluck('id');

                    $timetableGroupSlots = TimetableGroupSlot::where('slot_id', $slot->id)
                        ->whereIn('timetable_group_id', $groups)
                        ->where('total_hours_remain', '>', 0)
                        ->where('total_hours_remain', '>=', $duration)
                        ->get();

                    $newTimetableSlot = (new TimetableSlot())->create([
                        'timetable_id' => $timetable->id,
                        'course_program_id' => $request->course_program_id,
                        'room_id' => null,
                        'slot_id' => $slot->id,
                        'group_merge_id' => $newMergeTimetableSlot->id,
                        'course_name' => $request->course_name,
                        'lecturer_id' => null,
                        'type' => $request->course_type,
                        'start' => $start,
                        'end' => $end,
                        'durations' => $duration
                    ]);

                    if (count($timetableGroupSlots) > 0) {
                        // and then create timetable group
                        foreach ($timetableGroupSlots as $timetableGroupSlot) {
                            $timetableGroupSlot->total_hours_remain -= $duration;
                            $timetableGroupSlot->update();

                            // add timetable slot with group on timetable_group_session
                            (new TimetableGroupSession())->create([
                                'timetable_slot_id' => $newTimetableSlot->id,
                                'timetable_group_id' => $timetableGroupSlot->timetable_group_id,
                                'room_id' => $timetableGroupSlot->room_id
                            ]);
                        }
                    }
                    DB::commit();
                    return $newTimetableSlot;
                }
            }
        } catch (\Exception $exception) {
            DB::rollback();
            return message_error($exception->getMessage());
        }
    }

    private function createMergeTimetableSlot(CreateTimetableSlotRequest $request)
    {
        try {
            if (isset($request->start) || isset($request->end)) {
                $newMergeTimetableSlot = new MergeTimetableSlot();
                $newMergeTimetableSlot->start = (new Carbon($request->start))->setTimezone('Asia/Phnom_Penh');
                $newMergeTimetableSlot->end = (new Carbon($request->end == null ? $request->start : $request->end))->setTimezone('Asia/Phnom_Penh');
                if ($newMergeTimetableSlot->save()) {
                    return $newMergeTimetableSlot;
                }
            }
            return false;
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    private function createNewTimetable(CreateTimetableRequest $request)
    {
        try {
            $newTimetable = new Timetable();
            $newTimetable->academic_year_id = $request->academicYear;
            $newTimetable->department_id = $request->department;
            $newTimetable->degree_id = $request->degree;
            $newTimetable->grade_id = $request->grade;
            $newTimetable->option_id = $request->option == null ? null : $request->option;
            $newTimetable->semester_id = $request->semester;
            $newTimetable->week_id = $request->weekly;

            if ($newTimetable->save()) {
                return $newTimetable;
            }
            return false;
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function delete(DeleteTimetableRequest $request)
    {
        $timetable = Timetable::find($request->id);
        $timetableSlots = $timetable->timetableSlots;
        if (count($timetableSlots) > 0) {
            foreach ($timetableSlots as $timetableSlot) {
                $slot = Slot::find($timetableSlot->slot_id);
                if ($slot instanceof Slot) {
                    $slot->time_remaining = ($timetableSlot->durations + $slot->time_remaining);
                    $slot->updated_at = Carbon::now();
                    $slot->update();
                }
            }
        }
        if ($timetable instanceof Timetable) {
            DB::table('timetables')->where('id', '=', $request->id)->delete();
            return Response::json(['status' => true], 200);
        }
    }

    public function reset(Request $request)
    {
        DB::beginTransaction();
        try {
            $weekIds = array_unique($request->selected_week);
            $timetables = Timetable::where([
                'timetables.academic_year_id' => $request->academic_year_id,
                'timetables.department_id' => $request->department_id,
                'timetables.option_id' => $request->department_option_id,
                'timetables.degree_id' => $request->degree_id,
                'timetables.grade_id' => $request->grade_id,
                'timetables.semester_id' => $request->semester_id
            ])
                ->whereIn('week_id', $weekIds)
                ->get();
            if (count($timetables) > 0) {
                foreach ($timetables as $timetable) {
                    if ($timetable instanceof Timetable) {
                        $timetableSlots = $timetable->timetableSlots;
                        if (count($timetableSlots) > 0) {
                            foreach ($timetableSlots as $timetableSlot) {
                                if ($timetableSlot instanceof TimetableSlot) {
                                    $groupIds = TimetableGroupSession::where([
                                        'timetable_slot_id' => $timetableSlot->id
                                    ])->pluck('timetable_group_id');

                                    foreach ($groupIds as $groupId) {
                                        $timetableGroupSlot = TimetableGroupSlot::where([
                                            'slot_id' => $timetableSlot->slot_id,
                                            'timetable_group_id' => $groupId
                                        ])->first();
                                        if ($timetableGroupSlot instanceof TimetableGroupSlot) {
                                            $timetableGroupSlot->total_hours_remain += $timetableSlot->durations;
                                            $timetableGroupSlot->update();
                                            TimetableGroupSession::where([
                                                'timetable_slot_id' => $timetableSlot->id,
                                                'timetable_group_id' => $groupId
                                            ])->delete();
                                        }
                                    }
                                    $timetableSlot->delete();
                                }
                            }
                        }
                    }
                }
            }
            DB::commit();
            return message_success($timetables);
        } catch (\Exception $exception) {
            DB::rollback();
            return message_error($exception->getMessage());
        }
    }

    public function storeTimetableGroup(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return message_error($validator->errors());
        }

        $newGroup = TimetableGroup::firstOrCreate([
            'code' => $request->name,
            'parent_id' => $request->parent_id == '' ? null : $request->parent_id
        ]);

        return message_success(TimetableGroup::where('id', $newGroup->id)->with('parent')->first());
    }

    public function searchTimetableGroup()
    {
        return message_success(TimetableGroup::where('code', 'ilike', "%" . request('query') . "%")->get());
    }

    public function assignGroup(Request $request)
    {
        $this->validate($request, [
            'slot_id' => 'required',
            'group_id' => 'required'
        ]);

        try {
            $slot = Slot::find($request->slot_id);
            if ($slot instanceof Slot) {
                $timetableGroupSlot = TimetableGroupSlot::where([
                    'slot_id' => $request->slot_id,
                    'timetable_group_id' => $request->group_id
                ])->first();

                if (is_null($timetableGroupSlot)) {
                    $timetableGroupSlot = TimetableGroupSlot::create([
                        'slot_id' => $request->slot_id,
                        'timetable_group_id' => $request->group_id,
                        'total_hours' => $slot->total_hours,
                        'total_hours_remain' => $slot->total_hours
                    ]);
                }
                return message_success($timetableGroupSlot);
            }
            return message_error('The slot could not found!');
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function removeGroup(Request $request)
    {
        $this->validate($request, [
            'slot_id' => 'required',
            'timetable_group_id' => 'required'
        ]);
        DB::beginTransaction();
        try {
            $timetableGroupSlot = TimetableGroupSlot::where([
                'slot_id' => $request->slot_id,
                'timetable_group_id' => $request->timetable_group_id
            ])->first();

            if ($timetableGroupSlot instanceof TimetableGroupSlot) {

                $timetableSlotIds = TimetableSlot::where([
                    'slot_id' => $request->slot_id
                ])->pluck('id');

                TimetableGroupSession::whereIn('timetable_slot_id', $timetableSlotIds)
                    ->where('timetable_group_id', $request->timetable_group_id)
                    ->delete();
                $timetableGroupSlot->delete();
                DB::commit();
                return message_success([]);
            }
            return message_error('Could not delete group!');
        } catch (\Exception $exception) {
            DB::rollback();
            return message_error($exception->getMessage());
        }
    }

    public function assignGroupToTimetableSlot(Request $request)
    {
        DB::beginTransaction();
        $this->validate($request, [
            'timetable_slot_id' => 'required',
            'timetable_group_id' => 'required'
        ]);

        try {
            $timetableSlot = TimetableSlot::find($request->timetable_slot_id);

            if ($timetableSlot instanceof TimetableSlot) {
                $slot = Slot::find($timetableSlot->slot_id);

                $timetableGroupSession = TimetableGroupSession::where([
                    'timetable_slot_id' => $request->timetable_slot_id,
                    'timetable_group_id' => $request->timetable_group_id
                ])->first();

                if (is_null($timetableGroupSession)) {
                    $timetableGroupSession = TimetableGroupSession::create([
                        'timetable_slot_id' => $request->timetable_slot_id,
                        'timetable_group_id' => $request->timetable_group_id
                    ]);
                }

                $timetableGroupSlot = TimetableGroupSlot::where([
                    'slot_id' => $slot->id,
                    'timetable_group_id' => $request->timetable_group_id
                ])->first();

                if (is_null($timetableGroupSlot)) {
                    TimetableGroupSlot::create([
                        'slot_id' => $slot->id,
                        'timetable_group_id' => $request->timetable_group_id,
                        'total_hours' => $slot->total_hours,
                        'total_hours_remain' => ($slot->total_hours - $timetableSlot->durations)  // eliminate hours by default session durations
                    ]);
                }
                DB::commit();
                return message_success($timetableGroupSession);
            }
            return message_error('The timetable slot could not found!');
        } catch (\Exception $exception) {
            DB::rollback();
            return message_error($exception->getMessage());
        }
    }

    public function removeGroupFromTimetableSlot(Request $request)
    {
        $this->validate($request, [
            'timetable_slot_id' => 'required',
            'timetable_group_id' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $timetableSlot = TimetableSlot::find($request->timetable_slot_id);
            $durations = $timetableSlot->durations;
            if ($timetableSlot instanceof TimetableSlot) {
                $slot = Slot::find($timetableSlot->slot_id);
                if ($slot instanceof Slot) {
                    $timetableGroupSession = TimetableGroupSession::where([
                        'timetable_slot_id' => $request->timetable_slot_id,
                        'timetable_group_id' => $request->timetable_group_id
                    ])->first();

                    if ($timetableGroupSession instanceof TimetableGroupSession) {
                        $timetableGroupSession->delete();
                    }

                    // update timetable group slot
                    $timetableGroupSlot = TimetableGroupSlot::where([
                        'slot_id' => $slot->id,
                        'timetable_group_id' => $request->timetable_group_id
                    ])->first();

                    if ($timetableGroupSlot instanceof TimetableGroupSlot) {
                        $timetableGroupSlot->total_hours_remain += $durations;
                        $timetableGroupSlot->update();
                    }
                    DB::commit();
                    return message_success([]);
                }
            }
            return message_error('Could not delete group!');
        } catch (\Exception $exception) {
            DB::rollback();
            return message_error($exception->getMessage());
        }
    }

    public function getTimetableGroup()
    {
        return message_success(TimetableGroup::with('parent')->get());
    }

    public function getRooms()
    {
        try {
            $rooms = Room::join('buildings', 'buildings.id', '=', 'rooms.building_id')
                ->select([
                    DB::raw("CONCAT(buildings.code, '-', rooms.name) as code"),
                    'rooms.id as id'
                ])->get();
            return message_success($rooms);
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function get_conflict_info()
    {
        return message_success([]);
    }

    public function export_course_program(Request $request)
    {
        $this->validate($request, [
            'department_id' => 'required',
            'degree_id' => 'required',
            'grade_id' => 'required',
            'semester_id' => 'required'
        ]);
        $data = $request->all();
        try {
            $course_programs = Course::where([
                ['department_id', $data['department_id']],
                ['degree_id', $data['degree_id']],
                ['department_option_id', isset($data['option_id']) ? ($data['option_id'] == '' ? null : $data['option_id']) : null],
                ['grade_id', $data['grade_id']],
                ['semester_id', $data['semester_id']],
                ['active', true]
            ])->get();

            $amountCourseProgramImported = 0;

            if (count($course_programs) > 0) {
                foreach ($course_programs as $course_program) {
                    $slots = Slot::where([
                        ['course_program_id', $course_program->id],
                        ['academic_year_id', $data['academic_year_id']],
                        ['semester_id', $data['semester_id']]
                    ])->get();
                    if (count($slots) == 0) {
                        if ($course_program->time_tp > 0) {
                            DB::transaction(function () use ($data, $course_program, &$amountCourseProgramImported) {
                                $newSlot = new Slot();
                                $newSlot->time_tp = $course_program->time_tp;
                                $newSlot->time_td = 0;
                                $newSlot->time_course = 0;
                                $newSlot->academic_year_id = $data['academic_year_id'];
                                $newSlot->course_program_id = $course_program->id;
                                $newSlot->semester_id = $data['semester_id'];
                                $newSlot->created_uid = auth()->user()->id;
                                $newSlot->write_uid = auth()->user()->id;
                                $newSlot->save();
                                $amountCourseProgramImported++;
                            });
                        }
                        if ($course_program->time_td > 0) {
                            DB::transaction(function () use ($data, $course_program, &$amountCourseProgramImported) {
                                $newSlot = new Slot();
                                $newSlot->time_tp = 0;
                                $newSlot->time_td = $course_program->time_td;
                                $newSlot->time_course = 0;
                                $newSlot->academic_year_id = $data['academic_year_id'];
                                $newSlot->course_program_id = $course_program->id;
                                $newSlot->semester_id = $data['semester_id'];
                                $newSlot->created_uid = auth()->user()->id;
                                $newSlot->write_uid = auth()->user()->id;
                                $newSlot->save();
                                $amountCourseProgramImported++;
                            });
                        }
                        if ($course_program->time_course > 0) {
                            DB::transaction(function () use ($data, $course_program, &$amountCourseProgramImported) {
                                $newSlot = new Slot();
                                $newSlot->time_tp = 0;
                                $newSlot->time_td = 0;
                                $newSlot->time_course = $course_program->time_course;
                                $newSlot->academic_year_id = $data['academic_year_id'];
                                $newSlot->course_program_id = $course_program->id;
                                $newSlot->semester_id = $data['semester_id'];
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
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function get_timetable_slots(CreateTimetableRequest $request)
    {
        try {
            $result = array(
                'code' => 200,
                'message' => 'Successfully',
                'timetable' => null,
                'timetableSlots' => [],
            );

            $timetable = Timetable::where([
                ['academic_year_id', $request->academicYear],
                ['department_id', $request->department],
                ['degree_id', $request->degree],
                ['grade_id', $request->grade],
                ['option_id', $request->option == null ? null : $request->option],
                ['semester_id', $request->semester],
                ['week_id', $request->weekly]
            ])->first();

            if ($timetable instanceof Timetable) {
                $timetableSlots = TimetableSlot::with('groups', 'employee', 'room')
                    ->where('timetable_id', $timetable->id)
                    ->get();

                $testTimetableSlots = $timetable->timetableSlots;


                $found = [];

                foreach ($testTimetableSlots as $testTimetableSlot) {
                    $otherTimetableSlots = TimetableSlot::join('timetables', 'timetables.id', '=', 'timetable_slots.timetable_id')
                        ->whereNotIn('timetables.id', [$timetable->id])
                        ->where([
                            'timetables.academic_year_id' => $request->academicYear,
                            'timetables.semester_id' => $request->semester,
                            'timetables.week_id' => $request->weekly
                        ])
                        ->where(function ($query) use ($testTimetableSlot) {
                            $query->where('start', '<', $testTimetableSlot->start)
                                ->where('end', '>', $testTimetableSlot->start);
                        })
                        ->orWhere(function ($query) use ($testTimetableSlot) {
                            $query->where('start', '<', $testTimetableSlot->end)
                                ->where('end', '>', $testTimetableSlot->end);
                        })
                        ->get();
                    if (count($otherTimetableSlots) > 0) {
                        array_push($found, [$testTimetableSlot->id, $otherTimetableSlots]);
                    }
                }

                if ($timetable instanceof Timetable) {
                    $result['timetable'] = $timetable;
                    $result['timetableSlots'] = $timetableSlots;
                    $result['otherTimetableSlots'] = $otherTimetableSlots;
                    $result['testTimetableSlots'] = $testTimetableSlots;
                    $result['found'] = $found;
                }
            } else {
                $result['timetable'] = null;
                $result['timetableSlots'] = [];
            }
            return $result;
        } catch (\Exception $e) {
            return message_error($e->getMessage());
        }
    }

    public function get_weeks(Request $request)
    {
        $this->validate($request, [
            'semester_id' => 'required'
        ]);
        try {
            $weeks = Week::where('semester_id', $request->semester_id)->get();
            return ['status' => true, 'weeks' => $weeks];
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function get_options()
    {
        try {
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
                return ['status' => true, 'options' => $options];
            }
        } catch (\Exception $exception) {
            return ['status' => false];
        }
    }

    public function get_grades(Request $request)
    {
        $this->validate($request, [
            'department_id'
        ]);
        try {
            $result = [
                'status' => true,
                'grades' => [],
            ];
            $departmentId = $request->department_id;
            if ($departmentId == 8) {
                $result['grades'] = Grade::where('id', '<=', 2)->orderBy('id')->get();
            } else if ($departmentId == 12) {
                $result['grades'] = Grade::where('id', '>', 1)->orderBy('id')->get();
            } else {
                $result['grades'] = Grade::orderBy('id')->get();
            }
            return $result;
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function get_groups()
    {
        $tmpGroups = Group::get()->toArray();
        $groups = sort_groups($tmpGroups);
        return [
            'code' => 200, 'status' => true, 'groups' => $groups
        ];
    }

    public function get_course_programs(Request $request)
    {
        $this->validate($request, [
            'academicYear' => 'required',
            'department' => 'required',
            'degree' => 'required',
            'grade' => 'required',
            'semester' => 'required',
        ]);
        try {
            $academic_year_id = $request->academicYear;
            $department_id = $request->department;
            $degree_id = $request->degree;
            $grade_id = $request->grade;
            $semester_id = $request->semester;
            $option_id = (isset($request->option) && $request->option != '' && $request->option != null) ? $request->option : null;

            $course_program_ids = Course::where([
                'department_id' => $department_id,
                'degree_id' => $degree_id,
                'grade_id' => $grade_id,
                'department_option_id' => $option_id,
                'semester_id' => $semester_id,
            ])->pluck('id');

            $slots = Slot::join('courses', 'courses.id', '=', 'slots.course_program_id')
                ->whereIn('course_program_id', $course_program_ids)
                ->where('slots.academic_year_id', $academic_year_id)
                ->with('groups')
                ->select(
                    'slots.id as id',
                    'slots.course_program_id as course_program_id',
                    'slots.time_tp as tp',
                    'slots.time_td as td',
                    'slots.time_course as tc',
                    'courses.name_en as course_name'
                )
                ->orderBy('courses.name_en', 'asc')
                ->get();
            return array('status' => true, 'data' => $slots, 'code' => 200);
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

    public function assign_lecturer_to_timetable_slot(Request $request)
    {
        DB::beginTransaction();
        $this->validate($request, [
            'timetable_slot_id' => 'required',
            'lecturer_id' => 'required'
        ]);
        try {
            $timetableSlot = TimetableSlot::find($request->timetable_slot_id);
            if ($timetableSlot instanceof TimetableSlot) {
                $employee = Employee::find($request->lecturer_id);
                if ($employee instanceof Employee) {
                    $timetableGroupSessionIds = TimetableGroupSession::where('timetable_slot_id', $timetableSlot->id)->pluck('id');
                    if (count($timetableGroupSessionIds) > 0) {
                        foreach ($timetableGroupSessionIds as $timetableGroupSessionId) {
                            TimetableGroupSessionLecturer::firstOrCreate([
                                'timetable_group_session_id' => $timetableGroupSessionId,
                                'lecturer_id' => $employee->id
                            ]);
                        }
                        DB::commit();
                        return message_success([]);
                    }
                    return message_error('There are no one lecturers set');
                }
                return message_error('Could not found lecturer');
            }
            return message_error('Could not found timetable slot.');
        } catch (\Exception $exception) {
            DB::rollback();
            return message_error($exception->getMessage());
        }
    }

    public function search_course_program()
    {
        $academic_year_id = request('academic');
        $department_id = request('department');
        $degree_id = request('degree');
        $grade_id = request('grade');
        $semester_id = request('semester');
        $option_id = (request('option') == null ? null : (request('option') == '' ? null : request('option')));

        $course_program_ids = Course::where([
            ['department_id', $department_id],
            ['degree_id', $degree_id],
            ['grade_id', $grade_id],
            ['department_option_id', $option_id],
            ['semester_id', $semester_id],
        ])->pluck('id');

        $slots = Slot::join('courses', 'courses.id', '=', 'slots.course_program_id')
            ->whereIn('course_program_id', $course_program_ids)
            ->where('slots.academic_year_id', $academic_year_id)
            ->where(function ($query) {
                $query->whereRaw('LOWER(courses.name_en) LIKE ?', array('%' . strtolower(request('query')) . '%'))
                    ->orWhereRaw('LOWER(courses.name_kh) LIKE ?', array('%' . strtolower(request('query')) . '%'));
            })
            ->with('groups')
            ->select(
                'slots.id as id',
                'slots.course_program_id as course_program_id',
                'slots.time_tp as tp',
                'slots.time_td as td',
                'slots.time_course as tc',
                'courses.name_en as course_name'
            )
            ->orderBy('courses.name_en', 'asc')
            ->get();
        return array('status' => true, 'course_sessions' => $slots, 'code' => 200);
    }

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

    public function publish(Request $request)
    {
        try {
            $this->validate($request, [
                'academicYear' => 'required',
                'department' => 'required',
                'degree' => 'required',
                'grade' => 'required',
                'semester' => 'required',
                'weekly' => 'required'
            ]);

            $timetable = Timetable::where([
                'academic_year_id' => $request->academicYear,
                'department_id' => $request->department,
                'degree_id' => $request->degree,
                'grade_id' => $request->grade,
                'option_id' => $request->option == null ? null : $request->option,
                'group_id' => $request->group == null ? null : $request->group,
                'semester_id' => $request->semester,
                'week_id' => $request->weekly,
            ])->first();

            if ($timetable instanceof Timetable) {
                $timetable->update([
                    'completed' => true
                ]);
                return message_success([]);
            }
        } catch (\Exception $exception) {
            return message_error($exception->getMessage());
        }
    }

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

    public function resize_timetable_slot(ResizeTimetableSlotRequest $request)
    {
        DB::beginTransaction();
        try {
            try {
                $timetable_slot = TimetableSlot::find($request->timetable_slot_id);

                if ($timetable_slot instanceof TimetableSlot) {
                    $groups = $timetable_slot->groups->pluck('id');

                    $old_durations = $timetable_slot->durations;
                    $new_durations = $this->timetableSlotRepo->durations(new Carbon($timetable_slot->start), new Carbon($request->end));
                    $interval = $new_durations - $old_durations;

                    $timetable_slot->durations = $new_durations;
                    $timetable_slot->end = new Carbon($request->end);

                    $timetableGroupSlots = TimetableGroupSlot::where('slot_id', $timetable_slot->slot_id)
                        ->whereIn('timetable_group_id', $groups)
                        ->get();

                    $groupUnableResize = [];
                    foreach ($timetableGroupSlots as $timetableGroupSlot) {
                        if (!(($timetableGroupSlot->total_hours_remain > 0) && ($timetableGroupSlot->total_hours_remain >= $interval))) {
                            array_push($groupUnableResize, $timetableGroupSlot->timetable_group_id);
                        }
                    }

                    if (count($groupUnableResize) > 0) {
                        return message_error(Group::whereIn('id', $groupUnableResize)->get());
                    } else {
                        foreach ($timetableGroupSlots as $timetableGroupSlot) {
                            $timetableGroupSlot->total_hours_remain = (float)$timetableGroupSlot->total_hours_remain - (float)$interval;
                            $timetableGroupSlot->update();
                        }
                        $timetable_slot->update();
                        DB::commit();
                        return message_success($timetable_slot);
                    }
                }
            } catch (\Exception $e) {
                return message_error($e->getMessage());
            }
        } catch (\Exception $e) {
            DB::rollback();
            return message_error($e->getMessage());
        }
    }

    public function insert_room_into_timetable_slot(AddRoomIntoTimetableSlotRequest $request)
    {
        DB::beginTransaction();
        try {
            $timetableSlot = TimetableSlot::find($request->timetable_slot_id);
            $room_id = $request->room_id;
            if ($timetableSlot instanceof TimetableSlot) {
                $timetableGroupSessions = TimetableGroupSession::where('timetable_slot_id', $timetableSlot->id)->get();
                if (count($timetableGroupSessions) > 0) {
                    foreach ($timetableGroupSessions as $timetableGroupSession) {
                        $timetableGroupSession->room_id = $room_id;
                        $timetableGroupSession->update();
                    }
                    DB::commit();
                    return message_success([]);
                }
                return message_error('There are 0 records updated');
            }
            return message_error('Could not found timetable slot.');
        } catch (\Exception $exception) {
            DB::rollBack();
            return message_error($exception->getMessage());
        }
    }

    public function remove_timetable_slot(RemoveTimetableSlotRequest $removeTimetableSlotRequest)
    {
        DB::beginTransaction();
        try {
            $timetable_slot_id = $removeTimetableSlotRequest->timetable_slot_id;
            $timetableSlot = TimetableSlot::find($timetable_slot_id);
            if ($timetableSlot instanceof TimetableSlot) {
                $groups = $timetableSlot->groups->pluck('id');
                $timetableGroupSlots = TimetableGroupSlot::where('slot_id', $timetableSlot->slot_id)
                    ->whereIn('timetable_group_id', $groups)
                    ->get();

                foreach ($timetableGroupSlots as $groupSlot) {
                    $groupSlot->total_hours_remain += $timetableSlot->durations;
                    $groupSlot->update();
                }
                TimetableGroupSession::where('timetable_slot_id', $timetableSlot->id)->delete();
                $timetableSlot->delete();
                DB::commit();
                return message_success([]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return message_error($e->getMessage());
        }
    }

    public function get_timetable_assignment()
    {
        $timetable_assignments = Configuration::where('key', 'like', 'timetable_%')
            ->select('id', 'value', 'key', 'description', 'created_at', 'updated_at')
            ->get();

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

    public function update_assign_timetable()
    {
        $configuration = Configuration::find(request('id'));
        return Response::json(['status' => true, 'start' => (new Carbon($configuration->created_at))->toDateString(), 'end' => (new Carbon($configuration->updated_at))->toDateString()]);
    }

    private function hasHalfHour(Timetable $timetable)
    {
        $timetableSlots = $timetable->timetableSlots;
        foreach ($timetableSlots as $timetableSlot) {
            $start = new Carbon($timetableSlot->start);
            $end = new Carbon($timetableSlot->end);
            if (($end->minute - $start->minute) == 30 || ($start->minute - $end->minute) == 30) {
                return true;
            }
        }
        return false;
    }
}
