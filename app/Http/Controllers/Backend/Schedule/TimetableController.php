<?php

namespace App\Http\Controllers\Backend\Schedule;

use App\Http\Controllers\Backend\Schedule\Traits\AjaxCloneTimetableController;
use App\Http\Controllers\Backend\Schedule\Traits\AjaxCRUDTimetableController;
use App\Http\Controllers\Backend\Schedule\Traits\PrintTimetableController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableSlotRequest;
use App\Http\Requests\Backend\Schedule\Timetable\DeleteTimetableRequest;
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
    use AjaxCRUDTimetableController, AjaxCloneTimetableController, PrintTimetableController;

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
        // dd(request()->all());
        $employee = Employee::where('user_id', auth()->user()->id)->first();

        $timetables = Timetable::join('weeks', 'weeks.id', '=', 'timetables.week_id')
            ->join('academicYears', 'academicYears.id', '=', 'timetables.academic_year_id')
            ->join('departments', 'departments.id', '=', 'timetables.department_id')
            ->join('degrees', 'degrees.id', '=', 'timetables.degree_id')
            ->join('grades', 'grades.id', '=', 'timetables.grade_id')
            ->leftJoin('departmentOptions', 'departmentOptions.id', '=', 'timetables.option_id')
            ->join('semesters', 'semesters.id', '=', 'timetables.semester_id')
            ->leftJoin('groups', 'groups.id', '=', 'timetables.group_id')
            ->orderBy('timetables.created_at', 'desc')
            ->select([
                /* 'academicYears.name_latin as academic_year',
                 'departments.code as department',
                 'degrees.name_en as degree',
                 'grades.code as grade',
                 'departmentOptions.name_en as option',
                 'semesters.name_en as semester',*/
                /*'groups.code as group',*/
                'weeks.name_en as weekly',
                'timetables.completed as status',
                'timetables.id as id'
            ]);

        return Datatables::of($timetables)
            ->addColumn('action', function ($timetable) {
                $export = ' <a href="#export" class="btn btn-xs btn-primary">'
                    . '<i class="fa fa-download" data-toggle="tooltip"'
                    . 'data-placement="top" title="Export"'
                    . 'data-original-title="Export">'
                    . '</i>'
                    . '</a> ';

                $print = ' <button id="print-timetable" rel="external"  href="' . route('timetables.print', $timetable->id) . '" class="btn btn-xs btn-success">'
                    . '<i class="fa fa-print" data-toggle="tooltip"'
                    . 'data-placement="top" title="Print"'
                    . 'data-original-title="Print">'
                    . '</i>'
                    . '</button> ';

                $view = '<a href="' . route('admin.schedule.timetables.show', $timetable->id) . '" class="btn btn-xs btn-info">'
                    . '<i class="fa fa-share-square-o" data-toggle="tooltip"'
                    . 'data-placement="top" title="View"'
                    . 'data-original-title="View">'
                    . '</i></a>';

                $delete = ' <a href="/admin/schedule/timetables/delete/' . $timetable->id . '" class="btn btn-xs btn-danger">'
                    . '<i class="fa fa-trash" data-toggle="tooltip"'
                    . 'data-placement="top" title="Delete"'
                    . 'data-original-title="Delete">'
                    . '</i>'
                    . '</a>';

                $result = '';
                if (access()->allow('export-timetable')) {
                    $result .= $export;
                }
                if (access()->allow('edit-timetable')) {
                    $result .= $print;
                }
                if (access()->allow('view-timetable')) {
                    $result .= $view;
                }
                if (access()->allow('delete-timetable')) {
                    $result .= $delete;
                }
                return $result;
            })
            ->editColumn('status', function ($timetable) {
                if ($timetable->status == false) {
                    $view = '<span class="btn btn-danger btn-xs">'
                        . '<i class="fa fa-times-circle"'
                        . 'data-toggle="tooltip"'
                        . 'data-placement="top" title="Uncompleted"'
                        . 'data-original-title="Uncompleted"></i>'
                        . '</span>';
                } else {
                    $view = '<span class="btn btn-info btn-xs">'
                        . '<i class="fa fa-times-circle"'
                        . 'data-toggle="tooltip"'
                        . 'data-placement="top" title="Completed"'
                        . 'data-original-title="Uncompleted"></i>'
                        . '</span>';
                }
                return $view;
            })
            ->make(true);
    }

    /**
     * Create timetable page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $now = Carbon::now('Asia/Phnom_Penh');
        $employee = Employee::where('user_id', auth()->user()->id)->first();
        if ($employee instanceof Employee) {
            $createTimetablePermissionConfiguration = Configuration::where('key', 'timetable_' . $employee->department_id)->first();
        } else {
            $createTimetablePermissionConfiguration = null;
        }

        if (isset($createTimetablePermissionConfiguration)) {
            if (access()->allow('create-timetable') && (strtotime($now) >= strtotime($createTimetablePermissionConfiguration->created_at) && strtotime($now) <= strtotime($createTimetablePermissionConfiguration->updated_at))) {
                return view('backend.schedule.timetables.create');
            }
        } else {
            return view('backend.schedule.timetables.create');
        }

        return abort(404);
    }

    /**
     * Show timetable's details page.
     *
     * @param Timetable $timetable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Timetable $timetable)
    {
        $now = Carbon::now('Asia/Phnom_Penh');
        $employee = Employee::where('user_id', auth()->user()->id)->first();
        if ($employee instanceof Employee) {
            $createTimetablePermissionConfiguration = Configuration::where('key', 'timetable_' . $employee->department_id)->first();
        } else {
            $createTimetablePermissionConfiguration = null;
        }
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
        foreach ($timetable_slots as $timetable_slot) {
            if (($timetable_slot instanceof TimetableSlot) && is_object($timetable_slot)) {

                $newTimetableSlot = TimetableSlot::find($timetable_slot->id);
                $timetableSlot = new Collection($newTimetableSlot);
                $timetableSlot->put('building', $timetable_slot->building);
                $timetableSlot->put('room', $timetable_slot->room);
                $timetableSlots->push($timetableSlot);
            }
        }
        return view('backend.schedule.timetables.show', compact('timetableSlots', 'timetable', 'now', 'createTimetablePermissionConfiguration'));
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
            return redirect()->back()->withFlashSuccess('Timetable is deleted successfully.');
        }
        return redirect()->back()->withFlashError('Something went wrong.');
    }
}
