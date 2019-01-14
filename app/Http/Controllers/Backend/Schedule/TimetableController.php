<?php

namespace App\Http\Controllers\Backend\Schedule;

use App\Http\Controllers\Backend\Schedule\Traits\CloneTimetableTrait;
use App\Http\Controllers\Backend\Schedule\Traits\ExportCourseToSlotTrait;
use App\Http\Controllers\Backend\Schedule\Traits\OptionTimetableTrait;
use App\Http\Controllers\Backend\Schedule\Traits\TimetableAssignmentTrait;
use App\Http\Controllers\Backend\Schedule\Traits\TimetableSlotTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\DeleteTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\ShowTimetableRequest;
use App\Models\AcademicYear;
use App\Models\Configuration;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Employee;
use App\Models\Grade;
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
use Yajra\Datatables\Datatables;

/**
 * Class TimetableController
 * @package App\Http\Controllers\Backend\Schedule
 */
class TimetableController extends Controller
{
    use CloneTimetableTrait,
        ExportCourseToSlotTrait,
        TimetableAssignmentTrait,
        OptionTimetableTrait,
        TimetableSlotTrait;

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
}
