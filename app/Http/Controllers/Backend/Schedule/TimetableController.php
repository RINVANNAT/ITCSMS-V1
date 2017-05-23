<?php

namespace App\Http\Controllers\Backend\Schedule;

use App\Http\Controllers\Backend\Schedule\Traits\AjaxCloneTimetableController;
use App\Http\Controllers\Backend\Schedule\Traits\AjaxFilterTimetableController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
use App\Http\Requests\Backend\Schedule\Timetable\DeleteTimetableRequest;
use App\Models\AcademicYear;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Grade;
use App\Models\Schedule\Timetable\Timetable;
use App\Models\Schedule\Timetable\TimetableSlot;
use App\Models\Schedule\Timetable\Week;
use App\Models\Semester;
use App\Repositories\Backend\Schedule\Timetable\TimetableRepositoryContract;
use App\Repositories\Backend\Schedule\Timetable\TimetableSlotRepositoryContract;
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
    use AjaxFilterTimetableController, AjaxCloneTimetableController;

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
        return view('backend.schedule.timetables.index')->with([
            'academicYears' => AcademicYear::latest()->get(),
            'departments' => Department::where('parent_id', 11)->get(),
            'degrees' => Degree::all(),
            'grades' => Grade::all(),
            'options' => DepartmentOption::all(),
            'semesters' => Semester::all(),
            'weeks' => Week::all()
        ]);
    }

    /**
     * Get all timetables.
     *
     * @return mixed
     */
    public function get_timetables()
    {
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
                'academicYears.name_latin as academic_year',
                'departments.code as department',
                'degrees.name_en as degree',
                'grades.code as grade',
                'departmentOptions.name_en as option',
                'semesters.name_en as semester',
                'groups.code as group',
                'weeks.name_en as weekly',
                'timetables.completed as status',
                'timetables.id as id'
            ])
            ->get();
        return Datatables::of($timetables)
            ->addColumn('action', function ($timetable) {
                $print = ' <a href="#print" class="btn btn-xs btn-info">'
                    . '<i class="fa fa-print" data-toggle="tooltip"'
                    . 'data-placement="top" title="Print"'
                    . 'data-original-title="Print">'
                    . '</i>'
                    . '</a> ';

                $view = '<a href="' . route('admin.schedule.timetables.show', $timetable->id) . '" class="btn btn-xs btn-primary">'
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

                if (access()->allow('delete-timetable') || access()->allow('view-timetable')) {
                    return $print . $view . $delete;
                } else if (access()->allow('view-timetable')) {
                    return $view;
                }
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
        return view('backend.schedule.timetables.create');
    }

    /**
     * Show timetable's details page.
     *
     * @param Timetable $timetable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Timetable $timetable)
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
        foreach ($timetable_slots as $timetable_slot) {
            if (($timetable_slot instanceof TimetableSlot) && is_object($timetable_slot)) {

                $newTimetableSlot = TimetableSlot::find($timetable_slot->id);
                $timetableSlot = new Collection($newTimetableSlot);
                $timetableSlot->put('building', $timetable_slot->building);
                $timetableSlot->put('room', $timetable_slot->room);
                $timetableSlots->push($timetableSlot);
            }
        }
        return view('backend.schedule.timetables.show', compact('timetableSlots', 'timetable'));
    }

    /**
     * @param CreateTimetableRequest $request
     * @return int
     */
    public function store(CreateTimetableRequest $request)
    {
        $findTimetable = $this->timetableRepository->find_timetable_is_existed($request);
        $new_timetable_slot = new TimetableSlot();
        if ($findTimetable instanceof Timetable) {
            $new_timetable_slot = $this->timetableSlotRepository->create_timetable_slot($findTimetable, $request);
        } else {
            $newTimetable = $this->timetableRepository->create_timetable($request);
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
