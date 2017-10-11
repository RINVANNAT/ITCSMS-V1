<?php

namespace App\Http\Controllers\Backend\Schedule;

use App\Http\Controllers\Backend\Schedule\Traits\AjaxCloneTimetableController;
use App\Http\Controllers\Backend\Schedule\Traits\AjaxCRUDTimetableController;
use App\Http\Controllers\Backend\Schedule\Traits\ExportTimetableController;
use App\Http\Controllers\Backend\Schedule\Traits\PrintTimetableController;
use App\Http\Controllers\Backend\Schedule\Traits\ViewTimetableByTeacherController;
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
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
use App\Models\Schedule\Timetable\Week;
use App\Models\Semester;
use App\Repositories\Backend\Schedule\Timetable\TimetableRepositoryContract;
use App\Repositories\Backend\Schedule\Timetable\TimetableSlotRepositoryContract;
use Carbon\Carbon;
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
    use AjaxCRUDTimetableController, AjaxCloneTimetableController, PrintTimetableController, ExportTimetableController;
    use ViewTimetableByTeacherController;
    /**
     * @var TimetableRepositoryContract
     */
    protected $timetableRepository;

    /**
     * @var TimetableSlotRepositoryContract
     */
    protected $timetableSlotRepository;

    /**
     * TimetableController constructor.
     *
     * @param TimetableSlotRepositoryContract $timetableSlotRepository
     * @param TimetableRepositoryContract $timetableRepository
     */
    public function __construct
    (
        TimetableSlotRepositoryContract $timetableSlotRepository,
        TimetableRepositoryContract $timetableRepository
    )
    {
        $this->timetableSlotRepository = $timetableSlotRepository;
        $this->timetableRepository = $timetableRepository;
        $this->setRepository($this->timetableRepository, $this->timetableSlotRepository);
        $this->setTimetableSlotRepo($timetableSlotRepository);
        $this->setTimetableSlotRepository($timetableSlotRepository);
    }

    /**
     * Timetable home page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    /**
     * Get all timetables.
     *
     * @return mixed
     */
    public function get_timetables()
    {
        $academic_year_id = request('academicYear');
        $department_id = request('department');
        $degree_id = request('degree');
        $grade_id = request('grade');
        $option_id = request('option');
        $semester_id = request('semester');
        $group_id = request('group');

        $timetables = Timetable::join('weeks', 'weeks.id', '=', 'timetables.week_id')
            ->join('academicYears', 'academicYears.id', '=', 'timetables.academic_year_id')
            ->join('departments', 'departments.id', '=', 'timetables.department_id')
            ->join('degrees', 'degrees.id', '=', 'timetables.degree_id')
            ->join('grades', 'grades.id', '=', 'timetables.grade_id')
            ->leftJoin('departmentOptions', 'departmentOptions.id', '=', 'timetables.option_id')
            ->join('semesters', 'semesters.id', '=', 'timetables.semester_id')
            ->leftJoin('groups', 'groups.id', '=', 'timetables.group_id')
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
        if ($group_id !== 'Group' && $group_id != null) {
            $timetables->where('groups.id', $group_id);
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

    /**
     * Create timetable page.
     *
     * @param null $academic
     * @param null $department
     * @param null $degree
     * @param null $option
     * @param null $grade
     * @param null $semester
     * @param null $group
     * @param $week
     * @return mixed
     */
    public function create($academic = null, $department = null, $degree = null, $option = null, $grade = null, $semester = null, $group = null, $week=null)
    {
        $now = Carbon::now('Asia/Phnom_Penh');
        $employee = Employee::where('user_id', auth()->user()->id)->first();
        if ($employee instanceof Employee) {
            $createTimetablePermissionConfiguration = Configuration::where('key', 'timetable_' . $employee->department_id)->first();
        } else {
            $createTimetablePermissionConfiguration = null;
        }

        if(isset($academic)){
            $groups = DB::table('course_annuals')
                ->where([
                    ['academic_year_id', $academic],
                    ['department_id', $department],
                    ['degree_id', $degree],
                    ['grade_id', $grade]
                ])
                ->where(function ($query) use ($option){
                    if(!is_null($option) && $option != 0){
                        $query->where('department_option_id', $option);
                    }
                })
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
        }else{
            $groups = null;
        }

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
                    'groups' => $groups
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
                'groups' => $groups
            ]);
        }

        return abort(404);
    }

    /**
     * Show timetable's details page.
     *
     * @param Timetable $timetable
     * @param ShowTimetableRequest $showTimetableRequest
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Timetable $timetable, ShowTimetableRequest $showTimetableRequest)
    {
        $timetable_slots = TimetableSlot::where('timetable_id', $timetable->id)
            ->leftJoin('rooms', 'rooms.id', '=', 'timetable_slots.room_id')
            ->leftJoin('buildings', 'buildings.id', '=', 'rooms.building_id')
            ->select(
                'timetable_slots.id',
                'timetable_slots.course_name as title',
                'timetable_slots.course_name',
                'timetable_slots.teacher_name',
                'timetable_slots.type as type',
                'timetable_slots.start',
                'timetable_slots.end',
                'buildings.code as building',
                'rooms.name as room'
            )
            ->get();
        $timetableSlots = new Collection();

        if ($timetable->department_id < 12 && ($timetable instanceof Timetable)) {
            // get student annuals id
            $student_annual_ids = $this->timetableSlotRepo->find_student_annual_ids($timetable);
            $department_languages = array(12, 13); // (english, french)
            foreach ($department_languages as $department_language) {
                // get group language, [@return array(Collection $groups, Array $groups)]
                $groups = $this->timetableSlotRepo->get_group_student_annual_form_language($department_language, $student_annual_ids, $timetable);

                // get timetable language,
                $timetables = $this->timetableSlotRepo->get_timetables_form_language_by_student_annual($groups[0], $timetable, $department_language);

                // get timetable slots [@return array(timetableSlots, groupsRoom)]
                $timetableSlotsLang = $this->timetableSlotRepo->get_timetable_slot_language_dept($timetables, $groups[0]);

                // set timetable slots language to view.
                $this->timetableSlotRepo->set_timetable_slot_language($timetableSlots, $timetableSlotsLang[1], $timetableSlotsLang[0]);
            }
        }

        foreach ($timetable_slots as $timetable_slot) {
            if (($timetable_slot instanceof TimetableSlot) && is_object($timetable_slot)) {

                $newTimetableSlot = TimetableSlot::find($timetable_slot->id);
                $timetableSlot = new Collection($newTimetableSlot);
                $timetableSlot->put('building', $timetable_slot->building);
                $timetableSlot->put('room', $timetable_slot->room);
                $timetableSlots->push($timetableSlot);
            }
        }

        //dd($timetable);
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

    /**
     * @param CreateTimetableSlotRequest $request
     * @param CreateTimetableRequest $requestTimetable
     * @return int
     */
    public function store(CreateTimetableSlotRequest $request, CreateTimetableRequest $requestTimetable)
    {
        $findTimetable = $this->timetableRepository->find_timetable_is_existed($requestTimetable);
        $new_timetable_slot = new TimetableSlot();
        if ($findTimetable instanceof Timetable) {
            $new_timetable_slot = $this->timetableSlotRepository->create_timetable_slot($findTimetable, $request);
        } else {
            $newTimetable = $this->timetableRepository->create_timetable($requestTimetable);
            if ($newTimetable instanceof Timetable) {
                $new_timetable_slot = $this->timetableSlotRepository->create_timetable_slot($newTimetable, $request);
            }
        }
        if ($new_timetable_slot) {
            return Response::json([
                'status' => true,
                'timetable_slot' => \GuzzleHttp\json_decode($new_timetable_slot)
            ]);
        }
        return Response::json(['status' => false]);
    }

    /**
     * Delete timetable.
     *
     * @param DeleteTimetableRequest $request
     * @return mixed
     */
    public function delete(DeleteTimetableRequest $request)
    {
        if (Timetable::find($request->id) instanceof Timetable) {
            DB::table('timetables')->where('id', '=', $request->id)->delete();
            return Response::json(['status' => true], 200);
        }
    }
}
