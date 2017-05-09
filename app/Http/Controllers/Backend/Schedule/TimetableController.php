<?php

namespace App\Http\Controllers\Backend\Schedule;

use App\Http\Controllers\Backend\Schedule\Traits\AjaxCloneTimetableController;
use App\Http\Controllers\Backend\Schedule\Traits\AjaxFilterTimetableController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Schedule\Timetable\CreateTimetableRequest;
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
                'timetables.completed as status'
            ])
            ->get();
        return Datatables::of($timetables)
            ->addColumn('action', function ($timetables) {
                $view = '<a href="' . route('admin.schedule.timetables.create') . '" class="btn btn-xs btn-primary">'
                    . '<i class="fa fa-share-square-o" data-toggle="tooltip"'
                    . 'data-placement="top" title="View"'
                    . 'data-original-title="View">'
                    . '</i></a>';

                $delete = ' <a href="' . route('admin.schedule.timetables.create') . '" class="btn btn-xs btn-danger">'
                    . '<i class="fa fa-trash" data-toggle="tooltip"'
                    . 'data-placement="top" title="Delete"'
                    . 'data-original-title="Delete">'

                    . '</i>'
                    . '</a>';

                if (access()->allow('delete-timetable') || access()->allow('view-timetable')) {
                    return $view . $delete;
                } else if (access()->allow('view-timetable')) {
                    return $view;
                }
            })
            ->editColumn('status', function ($timetables) {
                $view = '';
                if ($timetables->completed == false) {
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        return view('backend.schedule.timetables.show');
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
            return Response::json(['status' => true, 'timetable_slot' => \GuzzleHttp\json_decode($new_timetable_slot)]);
        }
        return Response::json(['status' => false]);
    }
}
