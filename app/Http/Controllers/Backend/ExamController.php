<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\Backend\Candidate\GenerateRoomExamRequest;
use App\Http\Requests\Backend\EntranceExamCourse\CreateEntranceExamCourseRequest;
use App\Http\Requests\Backend\Exam\CalculateExamScoreRequest;
use App\Http\Requests\Backend\Exam\CreateExamRequest;
use App\Http\Requests\Backend\Exam\DeleteExamRequest;
use App\Http\Requests\Backend\Exam\DownloadExaminationDocumentsRequest;
use App\Http\Requests\Backend\Exam\EditExamRequest;
use App\Http\Requests\Backend\Exam\GenerateExamScoreDUTRequest;
use App\Http\Requests\Backend\Exam\ModifyExamRoomRequest;
use App\Http\Requests\Backend\Exam\NewCorrectionExamScoreRequest;
use App\Http\Requests\Backend\Exam\StoreExamRequest;
use App\Http\Requests\Backend\Exam\UpdateExamRequest;
use App\Http\Requests\Backend\Exam\ViewExamRequest;
use App\Http\Requests\Backend\Exam\ViewSecretCodeRequest;
use App\Models\AcademicYear;
use App\Models\Building;
use App\Models\Candidate;
use App\Models\CandidateDepartment;
use App\Models\Department;
use App\Models\EntranceExamCourse;
use App\Models\Exam;
use App\Models\ExamRoom;
use App\Models\ExamType;
use App\Models\Origin;
use App\Models\Room;
use App\Models\SecretRoomScore;
use App\Models\Student;
use App\Repositories\Backend\Exam\ExamRepositoryContract;
use App\Repositories\Backend\TempEmployeeExam\TempEmployeeExamRepositoryContract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

//use App\Http\Requests\Backend\Exam\StoreEntranceExamScoreRequest;


class ExamController extends Controller
{
    /**
     * @var ExamRepositoryContract
     */
    protected $exams;
    protected $employeeExams;

    /**
     * @param TempEmployeeExamRepositoryContract $empolyeeExams
     * @param ExamRepositoryContract $examRepo
     */
    public function __construct(
        TempEmployeeExamRepositoryContract $empolyeeExams,
        ExamRepositoryContract $examRepo

    )
    {
        $this->employeeExams = $empolyeeExams;
        $this->exams = $examRepo;
    }

    /**
     * Display a listing of the exams base on its type (engineer or DUT or final semester).
     * @param ViewExamRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function index(ViewExamRequest $request, $id)
    {
        $type = $id;
        return view('backend.exam.index', compact('type'));
    }


    /**
     * Show the form for creating a new resource.
     * @param CreateExamRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateExamRequest $request, $id)
    {
        $last_academic_year_id = AcademicYear::orderBy('id', 'desc')->first()->id;
        $academicYear = AcademicYear::where('id', $last_academic_year_id)->orderBy('id')->lists('name_kh', 'id');
        $examType = ExamType::where('id', $id)->lists('name_kh', 'id')->toArray();
        $type = $id;
        return view('backend.exam.create', compact('examType', 'type', 'academicYear'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreExamRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExamRequest $request)
    {

        $id = $this->exams->create($request->all());
        return redirect()->route('admin.exam.show', [$request->get('type_id'), $id])->withFlashSuccess(trans('alerts.backend.generals.created'));

    }

    /**
     * Display the specified resource.
     *
     * @param ViewExamRequest $request
     * @param int $type_id
     * @param  int $exam_id
     * @return \Illuminate\Http\Response
     */

    public function show(ViewExamRequest $request, $type_id, $exam_id)
    {
        $exam = $this->exams->findOrThrowException($exam_id);
        $type = $exam->type->id;
        $academicYear = AcademicYear::where('id', $exam->academicYear->id)->lists('name_kh', 'id');
        $examType = ExamType::where('id', $type)->lists('name_kh', 'id')->toArray();
        $usable_room_exam = Room::where('is_exam_room', true)->count();
        $exam_rooms = $exam->rooms()->with(['building', 'candidates'])->orderBy('building_id')->orderBy('name')->get();
        $buildings = Building::lists('name', 'id');

        // For candidate filtering
        $exams = Exam::where('type_id', $type)->lists('name', 'id');
        $origins = Origin::lists('name_kh', 'id');
        $rooms = ExamRoom::where('exam_id', $exam_id)
            ->join('buildings', "examRooms.building_id", '=', 'buildings.id')
            ->select(
                DB::raw('CONCAT(buildings.code,"examRooms".name) as room_name'), 'examRooms.id'
            )
            ->orderBy('room_name', 'ASC')
            ->lists('room_name', 'id');

        $roles = $this->employeeExams->getRoles();

        foreach ($roles as $role) {
        }
        return view('backend.exam.show', compact('rooms', 'exam', 'type', 'academicYear', 'examType', 'roles', 'usable_room_exam', 'exam_rooms', 'buildings', 'exams', 'origins'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditExamRequest $request
     * @param  int $type_id
     * @param int $exam_id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditExamRequest $request, $type_id, $exam_id)
    {

        $exam = $this->exams->findOrThrowException($exam_id);

        $type = $type_id;
        $academicYear = AcademicYear::where('id', $exam->academicYear->id)->lists('name_kh', 'id');
        $examType = ExamType::where('id', $type)->lists('name_kh', 'id')->toArray();

        return view('backend.exam.edit', compact('exam', 'type', 'academicYear', 'examType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateExamRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExamRequest $request, $id)
    {
        $this->exams->update($id, $request->all());
        return redirect()->route('admin.exam.index', $request['type_id'])->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteExamRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteExamRequest $request, $id)
    {
        $this->exams->destroy($id);
    }

    public function data(ViewExamRequest $request, $id)
    {
        $exams = DB::table('exams')
            ->where('type_id', $id)
            ->where('active', true)
            ->select([
                'id', 'type_id', 'name', 'date_start', 'date_end',
                'success_registration_start', 'success_registration_stop',
                'reserve_registration_start', 'reserve_registration_stop',
                'description'
            ]);

        $datatables = app('datatables')->of($exams);


        return $datatables
            ->addColumn('date_start_end', function ($exam) {
                return Carbon::createFromFormat('Y-m-d h:i:s', $exam->date_start)->toFormattedDateString() . " - " . Carbon::createFromFormat('Y-m-d h:i:s', $exam->date_end)->toFormattedDateString();
            })
            ->addColumn('success_registration_date_start_end', function ($exam) {
                if ($exam->success_registration_start != null) {
                    return Carbon::createFromFormat('Y-m-d', $exam->success_registration_start)->toFormattedDateString() . " - " . Carbon::createFromFormat('Y-m-d', $exam->success_registration_stop)->toFormattedDateString();
                } else {
                    return "-";
                }
            })
            ->addColumn('reserve_registration_date_start_end', function ($exam) {
                if ($exam->reserve_registration_start != null) {
                    return Carbon::createFromFormat('Y-m-d', $exam->reserve_registration_start)->toFormattedDateString() . " - " . Carbon::createFromFormat('Y-m-d', $exam->reserve_registration_stop)->toFormattedDateString();
                } else {
                    return "-";
                }
            })
            ->addColumn('action', function ($exam) {
                $action = "";
                if (Auth::user()->allow('edit-exams')) {
                    $action = $action . '<a href="' . route('admin.exam.edit', [$exam->type_id, $exam->id]) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . trans('buttons.general.crud.edit') . '"></i> </a>';
                }
                if (Auth::user()->allow('delete-exams')) {
                    $action = $action . ' <button class="btn btn-xs btn-danger btn-delete" data-remote="' . route('admin.exams.destroy', $exam->id) . '"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
                }

                $action = $action . ' <a href="' . route('admin.exam.show', [$exam->type_id, $exam->id]) . '" class="btn btn-xs btn-info"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . trans('buttons.general.view') . '"></i> </a>';
                return $action;
            })
            ->make(true);
    }

    public function get_courses($id)
    {
        $exam = $this->exams->findOrThrowException($id);
        $course = $exam->courses();

        $datatables = app('datatables')->of($course);
        return $datatables
            ->editColumn('name', '{!! $name !!}')
            ->editColumn('date_start', '{!! $date_start !!}')
            ->editColumn('date_end', '{!! $date_end !!}')
            ->editColumn('description', '{!! $description !!}')
            ->addColumn('action', function ($exam) {
                return '<a href="' . route('admin.exams.edit', $exam->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . trans('buttons.general.crud.edit') . '"></i> </a>' .
                    ' <button class="btn btn-xs btn-danger btn-delete" data-remote="' . route('admin.exams.destroy', $exam->id) . '"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>' .
                    ' <a href="' . route('admin.exams.show', $exam->id) . '" class="btn btn-xs btn-info"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . trans('buttons.general.view') . '"></i> </a>';
            })
            ->make(true);
    }

    /*public function get_entranceExamCourses($id){
        $exam = $this->exams->findOrThrowException($id);
        $entranceExamCourse = $exam->entranceExamCourses();


        $datatables =  app('datatables')->of($entranceExamCourse);

        return $datatables
            ->addColumn('action', function ($item) {
                return '<a href="'.route('admin.exams.edit',$item->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                    ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.exam.delete_entranceExamCourses', $item->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);

    }

    public function delete_entranceExamCourses(DeleteEntranceExamCourseRequest $request, $course_id)
    {
        $course = EntranceExamCourse::find($course_id);

        //Don't delete the role is there are users associated
        //if ($course->candidates()->count() > 0) {
        //    throw new GeneralException(trans('exceptions.backend.exams.has_candidate'));
       // }

        if ($course->delete()) {
            if($request->ajax()){
                return Response::json(array("success"=>true));
            } else {
                return redirect()->route('admin.exams.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
            }
        }
    }*/

//    public function get_buildings($id){
//        $type = $_GET['type'];
//        $data = array();
//        $all_ids = $this->get_all_room_ids();
//        $ids = $this->get_selected_room_ids($id);
//
//        if($type == "available"){
//            $ids = $this->get_available_room_ids($all_ids,$ids);
//        }
//
//        $buildings = DB::table('rooms')
//            ->select('buildings.name','buildings.id')
//            ->whereIN('rooms.id',$ids)
//            ->join('buildings','rooms.building_id','=','buildings.id')
//            ->groupBy('buildings.id')
//            ->get();
//
//        foreach ($buildings as $building){
//            $element = array(
//                "id"=>'building_'.$building->id,
//                "text" => $building->name,
//                "children"=>true,
//                "type"=>"building"
//            );
//            array_push($data,$element);
//        }
//
//        return Response::json($data);
//    }

    public function add_room(ModifyExamRoomRequest $request, $id)
    {

        $exam = $this->exams->findOrThrowException($id);

        if (isset($_POST['room_id']) && $_POST['room_id'] != null) { //This one is for edit
            $exam_room = ExamRoom::find($_POST['room_id']);

            $exam_room->write_uid = auth()->id();
            $exam_room->updated_at = Carbon::now();

        } else { // This is for add
            $exam_room = new ExamRoom();

            $exam_room->exam_id = $id;
            $exam_room->create_uid = auth()->id();
            $exam_room->created_at = Carbon::now();
        }

        $exam_room->name = $_POST['name'];
        $exam_room->building_id = $_POST['building_id'];
        $exam_room->nb_chair_exam = $_POST['nb_chair_exam'];
        $exam_room->description = $_POST['description'];

        $exam_room->save();

        $exam_rooms = $exam->rooms()->with(['building', 'candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list', compact('exam_rooms'));
    }

    public function merge_rooms(ModifyExamRoomRequest $request, $id)
    {

        $exam = $this->exams->findOrThrowException($id);
        $rooms = $_POST['rooms'];

        if (is_numeric($rooms[0])) {
            $room_0 = ExamRoom::find($rooms[0]);
        } else {
            $room_0 = ExamRoom::find($rooms[1]); // prevent get header too
        }

        foreach ($rooms as $key => $room) {
            if (is_numeric($room)) {
                ExamRoom::destroy($room);
            }
        }

        $exam_room = new ExamRoom();
        $exam_room->name = $_POST['name'];
        $exam_room->building_id = $_POST['building_id'];
        $exam_room->nb_chair_exam = $_POST['nb_chair_exam'];
        $exam_room->description = $_POST['description'];
        $exam_room->exam_id = $room_0->exam_id;
        $exam_room->create_uid = auth()->id();
        $exam_room->room_type_id = $room_0->room_type_id;
        $exam_room->created_at = Carbon::now();

        $exam_room->save();

        $exam_rooms = $exam->rooms()->with(['building', 'candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list', compact('exam_rooms'));
    }

    public function split_room(ModifyExamRoomRequest $request, $id)
    {


        $exam = $this->exams->findOrThrowException($id);
        ExamRoom::destroy($_POST['split_room']);

        for ($i = 0; $i < count($_POST['name']); $i++) {
            $exam_room = new ExamRoom();
            $exam_room->name = $_POST['name'][$i];
            $exam_room->building_id = $_POST['building_id'][$i];
            $exam_room->nb_chair_exam = $_POST['nb_chair_exam'][$i];
            $exam_room->description = $_POST['description'][$i];
            $exam_room->exam_id = $id;
            $exam_room->create_uid = auth()->id();
            $exam_room->created_at = Carbon::now();

            $exam_room->save();
        }

        $exam_rooms = $exam->rooms()->with(['building', 'candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list', compact('exam_rooms'));
    }

    public function generate_rooms(ModifyExamRoomRequest $request, $id)
    { // In Room section

        $exam = $this->exams->findOrThrowException($id);
        $exam_rooms = $exam->rooms()->get();


        foreach ($exam_rooms as $room) {
            //$room->delete(); // delete all realted room first because this is the first genration
            ExamRoom::destroy($room->id);
        }

        $rooms = DB::table('rooms')
            ->select('id', 'nb_chair_exam', 'name', 'room_type_id', 'building_id', 'department_id')
            ->where('is_exam_room', true)
            ->get();

        foreach ($rooms as $room) {
            $exam_room = new ExamRoom();
            if ($room->nb_chair_exam > $_POST['exam_chair'] + 5 || $room->nb_chair_exam < $_POST['exam_chair'] - 5) {
                $exam_room->nb_chair_exam = $room->nb_chair_exam;
            } else {
                $exam_room->nb_chair_exam = $_POST['exam_chair'];
            }

            $exam_room->created_at = Carbon::now();
            $exam_room->create_uid = auth()->id();
            $exam_room->exam_id = $id;
            $exam_room->name = $room->name;
            $exam_room->department_id = $room->department_id;
            $exam_room->building_id = $room->building_id;
            $exam_room->room_type_id = $room->room_type_id;

            $exam_room->save();
        }

        $exam_rooms = $exam->rooms()->with(['building', 'candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list', compact('exam_rooms'));
    }

    public function refresh_room($id)
    {
        $exam = $this->exams->findOrThrowException($id);

        $exam_rooms = $exam->rooms()->with(['building', 'candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list', compact('exam_rooms'));
    }

    public function sort_room_capacity($id)
    {
        $exam = $this->exams->findOrThrowException($id);

        $exam_rooms = $exam->rooms()->with(['building', 'candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list', compact('exam_rooms'));
    }

    public function delete_rooms(ModifyExamRoomRequest $request, $id)
    {
        $exam = $this->exams->findOrThrowException($id);

        $room_ids = $_POST['exam_room'];

        foreach ($room_ids as $room_id) {
            if (is_numeric($room_id)) {
                ExamRoom::destroy($room_id);
            }
        }

        $exam_rooms = $exam->rooms()->with(['building', 'candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list', compact('exam_rooms'));

    }

    public function edit_seats(ModifyExamRoomRequest $request, $id)
    {
        $exam = $this->exams->findOrThrowException($id);

        $room_ids = $_POST['rooms'];

        foreach ($room_ids as $room_id) {
            if (is_numeric($room_id)) {
                DB::table("examRooms")->where('id', $room_id)->update(['nb_chair_exam' => $_POST['nb_chair_exam']]);
            }
        }

        $exam_rooms = $exam->rooms()->with(['building', 'candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list', compact('exam_rooms'));

    }

    public function count_seat_exam($id)
    {
        $exam = $this->exams->findOrThrowException($id);
        $rooms = $exam->rooms()->get();

        $seat_exam = 0;

        foreach ($rooms as $room) {
            $seat_exam = $seat_exam + $room->nb_chair_exam;
        }

        return Response::json(array("seat_exam" => $seat_exam));
    }

    public function count_assigned_seat($id)
    {
        $rooms = DB::table('candidates')
            ->where('exam_id', $id)
            ->select(DB::raw('count(*) as room_count, room_id'))
            ->groupBy('room_id')
            ->get();

        $result = [];
        foreach ($rooms as $room) {
            if (isset($result[$room->room_count])) {
                $result[$room->room_count] = $result[$room->room_count] + 1;
            } else {
                $result[$room->room_count] = 1;
            }
        }
        return Response::json($result);
    }

    public function view_room_secret_code(ViewSecretCodeRequest $request, $exam_id)
    {
        $exam = $this->exams->findOrThrowException($exam_id);

        $rooms = $exam->rooms()->with('building')->orderBy('building_id')->orderBy('name')->get()->toArray();

        foreach ($rooms as &$room) {
            if ($room['roomcode'] == "") {
                $room['roomcode'] = "";
            } else {
                $room['roomcode'] = Crypt::decrypt($room['roomcode']);
            }
        }

        return view('backend.exam.includes.popup_room_secret_code', compact('rooms', 'exam_id'));
    }

    public function save_room_secret_code(ViewSecretCodeRequest $request, $exam_id)
    {

        $rooms = json_decode($_POST['room_ids']);

        $rooms = collect($rooms);
        $rooms_key_by_secret_code = $rooms->keyBy('secret_code');
        if (count($rooms) > count($rooms_key_by_secret_code)) {
            // Some duplicate code exist
            return Response::json(array('success' => false, 'message' => "Duplicate secret code!"));
        }
        // Everything is alright.
        foreach ($rooms as $room) {
            DB::table('examRooms')
                ->where('exam_id', $exam_id)
                ->where('id', $room->room_id)
                ->update(['roomcode' => Crypt::encrypt($room->secret_code)]);
        }

        return Response::json(array('success' => true, 'message' => "Secret code is saved successfully"));
    }

    public function export_room_secret_code(ViewSecretCodeRequest $request, $exam_id)
    {
        $exam = $this->exams->findOrThrowException($exam_id);

        $rooms = $exam->rooms()->with('building')->orderBy('building_id')->orderBy('name')->get()->toArray();

        foreach ($rooms as &$room) {
            if ($room['roomcode'] == "") {
                $room['roomcode'] = "";
            } else {
                $room['roomcode'] = Crypt::decrypt($room['roomcode']);
            }
        }

        $alpha = array();
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        Excel::create('Room secret code', function ($excel) use ($rooms, $alpha) {

            // Set the title
            $excel->setTitle('Room secret code');

            // Chain the setters
            $excel->setCreator('Director of development & planning')
                ->setCompany('Institute of Technology of Cambodia');

            $excel->sheet('Secret codes', function ($sheet) use ($rooms, $alpha) {

                $header = array('#', "Room Name", "Room Code");
                $sheet->setOrientation('portrait');
                // Set top, right, bottom, left
                $sheet->setPageMargin(array(
                    0.25, 0.30, 0.25, 0.30
                ));

                // Set all margins
                $sheet->setPageMargin(0.25);

                $sheet->appendRow(array(
                    'Room Secret Code'
                ));

                $sheet->rows(
                    array($header)
                );

                $index = 1;
                foreach ($rooms as $item) {

                    $row = array($index, $item['building']['code'] . $item['name'], $item['roomcode']);

                    $sheet->appendRow(
                        $row
                    );
                    $index++;
                }

                $sheet->mergeCells('A1:C1');
                $range = 'A1:C' . (count($rooms) + 1);
                $sheet->cells($range, function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('middle');
                });
                $sheet->setBorder('A2:C' . (2 + count($rooms)), 'thin');

            });

        })->export('xls');
    }

    public function export_attendance_list(DownloadExaminationDocumentsRequest $request, $exam_id)
    {

        $exam = $this->exams->findOrThrowException($exam_id);
        $courses = $exam->entranceExamCourses()->get();
        $rooms = $exam->rooms()->with('building')->orderBy('building_id')->orderBy('name')->get();
        $candidateData = [];

        if ($courses) {
            foreach ($courses as $course) {
                $arrayTmpCands = [];
                if ($rooms) {
                    foreach ($rooms as $room) {
                        $candidates = $room->candidates()->with('gender')->orderBy('register_id')->get();
                        foreach ($candidates as $candidate) {
                            $element = array(
                                'លេខបង្កាន់ដៃ' => str_pad($candidate->register_id, 4, '0', STR_PAD_LEFT),
                                'បន្ទប់' => $room->building->code . "-" . $room->name,
                                'ឈ្មោះ ខ្មែរ' => $candidate->name_kh,
                                'ឈ្មោះ ឡាតាំង' => $candidate->name_latin,
                                'ភេទ' => $candidate->gender->code,
                                'ថ្ងៃខែរឆ្នាំកំនើត' => $candidate->dob->formatLocalized("%d/%b/%Y"),
                                'ហត្ថលេខា' => ''
                            );
//                            $element = array(
//                                'Order'     =>  str_pad($candidate->register_id, 4, '0', STR_PAD_LEFT),
//                                'Room'              => $room->building->code."-".$room->name,
//                                'Name Khmer'        => $candidate->name_kh,
//                                'Name Latin'        => $candidate->name_latin,
//                                'Sexe'              => $candidate->gender->code,
//                                'Birth Date'        => $candidate->dob->formatLocalized("%d/%b/%Y"),
//                                'Signature'         => ''
//                            );

                            $arrayTmpCands[] = $element;
                        }
                    }
                }
                $candidateData[$course->name_en] = $arrayTmpCands;
            }
        }


        $fields = ['លេខបង្កាន់ដៃ', 'បន្ទប់', 'ឈ្មោះ ខ្មែរ', 'ឈ្មោះ ឡាតាំង', 'ភេទ', 'ថ្ងៃខែរឆ្នាំកំនើត', 'ហត្ថលេខា'];

//        $fields= ['Order', 'Room', 'Name Khmer', 'Name Latin', 'Sexe', 'Birth Date', 'Signature'];
        $title = 'បញ្ជីវត្តមានបេក្ខជន';
//        $title = 'Candidates';
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        Excel::create('បញ្ជីវត្តមានបេក្ខជន', function ($excel) use ($candidateData, $title, $alpha, $fields) {
            foreach ($candidateData as $key => $data) {
                $excel->sheet($key, function ($sheet) use ($data, $title, $alpha, $fields) {
                    $sheet->fromArray($data);
                });
            }
        })->export('xls');

    }

    public function export_candidate_list($exam_id)
    {
        $exam = $this->exams->findOrThrowException($exam_id);
        $rooms = $exam->rooms()->with('building')->orderBy('building_id')->orderBy('name')->get();
        $candidateByRoom = [];

        if ($rooms) {
            foreach ($rooms as $room) {
                $candidates = $room->candidates()->with('gender')->orderBy('register_id')->get();
                if ($candidates) {
                    foreach ($candidates as $candidate) {
                        $element = array(
                            'លេខបង្កាន់ដៃ' => str_pad($candidate->register_id, 4, '0', STR_PAD_LEFT),
                            'ឈ្មោះ ខ្មែរ' => $candidate->name_kh,
                            'ឈ្មោះ ឡាតាំង' => $candidate->name_latin,
                            'ភេទ' => $candidate->gender->code,
                            'ថ្ងៃខែរឆ្នាំកំនើត' => $candidate->dob->formatLocalized("%d/%b/%Y")
                        );
                        $candidateByRoom[$room->building->code . "-" . $room->name][] = $element;

                    }
                }
            }
        }

        $fields = ['លេខបង្កាន់ដៃ', 'ឈ្មោះ ខ្មែរ', 'ឈ្មោះ ឡាតាំង', 'ភេទ', 'ថ្ងៃខែរឆ្នាំកំនើត'];
        $title = 'បញ្ជីបេក្ខជន';
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        Excel::create('បញ្ជីបេក្ខជន', function ($excel) use ($candidateByRoom, $title, $alpha, $fields) {
            foreach ($candidateByRoom as $key => $data) {
                $excel->sheet($key, function ($sheet) use ($data, $title, $alpha, $fields) {
                    $sheet->fromArray($data);
                });
            }
        })->export('xls');
    }

    public function export_candidate_list_by_register_id($exam_id)
    {

        $exam = $this->exams->findOrThrowException($exam_id);
        $candidates = $exam->candidates()->with('gender')->with('room')->with('room.building')->orderBy('register_id')->get();
        $candidateByRoom = [];

        if ($candidates) {
            foreach ($candidates as $candidate) {

                $candidateRooms = $candidate->room;
                if ($candidateRooms) {
                    $element = array(
                        'លេខបង្កាន់ដៃ' => str_pad($candidate->register_id, 4, '0', STR_PAD_LEFT),
                        'បន្ទប់' => $candidate->room->building->code . "-" . $candidate->room->name,
                        'ឈ្មោះ ខ្មែរ' => $candidate->name_kh,
                        'ឈ្មោះ ឡាតាំង' => $candidate->name_latin,
                        'ភេទ' => $candidate->gender->code,
                        'ថ្ងៃខែរឆ្នាំកំនើត' => $candidate->dob->formatLocalized("%d/%b/%Y")
                    );
                    $candidateByRoom[] = $element;
                }
            }
        }

        $fields = ['លេខបង្កាន់ដៃ', 'បន្ទប់', 'ឈ្មោះ ខ្មែរ', 'ឈ្មោះ ឡាតាំង', 'ភេទ', 'ថ្ងៃខែរឆ្នាំកំនើត'];
        $title = 'បញ្ជីបេក្ខជន';
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        Excel::create('បញ្ជីបេក្ខជនតាមបន្ទប់ រៀបតាមលេខបង្កាន់ដៃ', function ($excel) use ($candidateByRoom, $title, $alpha, $fields) {

            $excel->sheet($title, function ($sheet) use ($candidateByRoom, $title, $alpha, $fields) {
                $sheet->fromArray($candidateByRoom);
            });
        })->export('xls');
    }

    public function download_attendance_statistic(DownloadExaminationDocumentsRequest $request, $exam_id)
    {
        $exam_scores = SecretRoomScore::join('entranceExamCourses', 'entranceExamCourses.id', '=', 'secret_room_score.course_id')
            ->where('secret_room_score.exam_id', $exam_id)
            ->select(
                'roomcode',
                'secret_room_score.id',
                'order_in_room',
                'score_c',
                'score_w',
                'score_na',
                'sequence',
                'entranceExamCourses.name_kh'
            )
            ->orderBy('roomcode', 'ASC')
            ->orderBy('order_in_room', 'ASC')
            ->orderBy('sequence', 'ASC')
            ->get();
        $exam_score_by_course = $exam_scores->groupBy('name_kh');

        $exam_score_by_course->each(function ($item, $key) {
            $exam_score_by_room = $item->groupBy('roomcode');
            $exam_score_by_room->each(function ($item, $key) {
                $exam_score_by_order = $item->groupBy('order_in_room');
                dd($exam_score_by_order);
            });
        });
        return view('backend.exam.print.attendance_statistic');
    }

    public function download_attendance_list(DownloadExaminationDocumentsRequest $request, $exam_id)
    {

        $academic_year = Exam::leftJoin('academicYears', 'exams.academic_year_id', '=', 'academicYears.id')
            ->where('exams.id', $exam_id)
            ->select('academicYears.id')
            ->first();
        $exam = $this->exams->findOrThrowException($exam_id);
        $courses = $exam->entranceExamCourses()->get();
        $rooms = $exam->rooms()->with('building')->orderBy('building_id')->orderBy('name')->get();

        return view('backend.exam.print.attendance_list', compact('rooms', 'courses', 'academic_year'));
    }

    public function download_candidate_list(DownloadExaminationDocumentsRequest $request, $exam_id)
    {

        $academic_year = Exam::leftJoin('academicYears', 'exams.academic_year_id', '=', 'academicYears.id')
            ->where('exams.id', $exam_id)
            ->select('academicYears.id')
            ->first();
        $exam = $this->exams->findOrThrowException($exam_id);
        $rooms = $exam->rooms()->with('building')->orderBy('building_id')->orderBy('name')->get();

        return view('backend.exam.print.candidate_list', compact('rooms', 'academic_year'));
    }

    public function download_candidate_list_by_register_id(DownloadExaminationDocumentsRequest $request, $exam_id)
    {

        $academic_year = Exam::leftJoin('academicYears', 'exams.academic_year_id', '=', 'academicYears.id')
            ->where('exams.id', $exam_id)
            ->select('academicYears.id')
            ->first();
        $exam = $this->exams->findOrThrowException($exam_id);
        $candidates = $exam->candidates()->with('gender')->with('room')->with('room.building')->orderBy('register_id')->get()->toArray();

        $chunk_candidates = array_chunk($candidates, 30);

        return view('backend.exam.print.candidate_list_order_by_register_id', compact('chunk_candidates', 'academic_year'));
    }

    public function download_room_sticker(DownloadExaminationDocumentsRequest $request, $exam_id)
    {
        $exam = $this->exams->findOrThrowException($exam_id);
        $rooms = $exam->rooms()->with('building')->orderBy('building_id')->orderBy('name')->get();

        return view('backend.exam.print.room_sticker', compact('rooms'));
    }

    public function download_correction_sheet(DownloadExaminationDocumentsRequest $request, $exam_id)
    {
        $academic_year = Exam::leftJoin('academicYears', 'exams.academic_year_id', '=', 'academicYears.id')
            ->where('exams.id', $exam_id)
            ->select('academicYears.id')
            ->first();

        $exam = $this->exams->findOrThrowException($exam_id);
        $courses = $exam->entranceExamCourses()->get();
        $rooms = $exam->rooms()->with('candidates')->get()->toArray();

        foreach ($rooms as &$room) {
            $room['roomcode'] = Crypt::decrypt($room['roomcode']);
        }

        usort($rooms, function ($a, $b) {
            return $a['roomcode'] - $b['roomcode'];
        });

        return view('backend.exam.print.correction_sheet', compact('rooms', 'courses', 'academic_year'));
    }

    public function download_candidate_list_dut(DownloadExaminationDocumentsRequest $request, $exam_id)
    {
        $candidates = Candidate::where('exam_id', $exam_id)
            ->leftJoin('genders', 'candidates.gender_id', '=', 'genders.id')
            ->leftJoin('origins', 'candidates.province_id', '=', 'origins.id')
            //->leftJoin('candidate_department','candidates.id','=','candidate_department.candidate_id')
            ->leftJoin('highSchools', 'candidates.highschool_id', '=', 'highSchools.id')
            ->leftJoin('gdeGrades as bacTotal', 'candidates.bac_total_grade', '=', 'bacTotal.id')
            ->leftJoin('gdeGrades as mathGrade', 'candidates.bac_math_grade', '=', 'mathGrade.id')
            ->leftJoin('gdeGrades as physGrade', 'candidates.bac_phys_grade', '=', 'physGrade.id')
            ->leftJoin('gdeGrades as chemGrade', 'candidates.bac_chem_grade', '=', 'chemGrade.id')
            ->orderBy('register_id')
            ->select([
                'candidates.id',
                'register_id',
                'candidates.name_kh',
                'candidates.name_latin',
                'genders.name_kh as gender',
                'candidates.dob',
                'highSchools.name_kh as highschool',
                'origins.name_kh as origin',
                'candidates.bac_year',
                'bacTotal.name_en as bac_total_grade',
                'mathGrade.name_en as bac_math_grade',
                'physGrade.name_en as bac_phys_grade',
                'chemGrade.name_en as bac_chem_grade',
                'candidates.bac_percentile',
                //'candidate_department.department_id',
                //'candidate_department.rank',
            ])
            ->get()->toArray();
        foreach ($candidates as &$candidate) {
            $department_choices = DB::table('candidate_department')
                ->join('departments', 'candidate_department.department_id', '=', 'departments.id')
                ->where('candidate_id', $candidate['id'])
                ->select([
                    'candidate_department.rank',
                    'departments.code'
                ])
                ->orderBy('departments.code')
                ->get();
            $candidate["departments"] = $department_choices;
        }
        $chunk_candidates = array_chunk($candidates, 25);
        $departments = Department::where('is_specialist', true)->where('parent_id', 11)->orderBy('code')->get();
        $departments = collect($departments)->filter(function($item, $key) {
            if ($item->code != 'GIC' && $item->code != 'GTR' && $item->code != 'OAC' && $item->code != 'GS') {
                return $item;
            }
        });
        return view('backend.exam.print.candidate_list_dut', compact('chunk_candidates', 'departments'));
    }

    public function export_candidate_dut_detail(DownloadExaminationDocumentsRequest $request, $exam_id)
    {

        $allDutCands = [];

        $candidates = $this->getCandidateFromDB($exam_id);

        foreach ($candidates as &$candidate) {
            $department_choices = DB::table('candidate_department')
                ->join('departments', 'candidate_department.department_id', '=', 'departments.id')
                ->where('candidate_id', $candidate['id'])
                ->select([
                    'candidate_department.rank',
                    'departments.code',
                    'candidate_department.is_success'
                ])
                ->orderBy('departments.code')
                ->get();

            $candidateOptions = [];
            $pass = '';
            $reserve = '';
            foreach ($department_choices as $choice) {
                $candidateOptions[$choice->code] = $choice->rank;
                if ($choice->is_success == 'Pass') {
                    $pass = $choice->code;
                } elseif ($choice->is_success == 'Reserve') {
                    $reserve = $choice->code;
                }
            }

            $element = array(
                'លេខបង្កាន់ដៃ' => str_pad($candidate->register_id, 4, '0', STR_PAD_LEFT),
                'ឈ្មោះ ខ្មែរ' => $candidate->name_kh,
                'ឈ្មោះ ឡាតាំង' => $candidate->name_latin,
                'ភេទ' => $candidate->gender,
                'ថ្ងៃខែរឆ្នាំកំនើត' => $candidate->dob->formatLocalized("%d/%b/%Y"),
                'វិទ្យាល័យ' => $candidate->highschool,
                'ប្រភព' => $candidate->origin,
                'Bac Year' => $candidate->bac_year,
                'Score' => $candidate->bac_percentile,
                'Bac' => $candidate->bac_total_grade,
                'Math' => $candidate->bac_math_grade,
                'Phys' => $candidate->bac_phys_grade,
                'Chim' => $candidate->bac_chem_grade,
                'GCA' => $candidateOptions['GCA'],
                'GCI' => $candidateOptions['GCI'],
                'GEE' => $candidateOptions['GEE'],
                'GGG' => $candidateOptions['GGG'],
                'GIC' => $candidateOptions['GIC'],
                'GIM' => $candidateOptions['GIM'],
                'GRU' => $candidateOptions['GRU'],
                'Pass' => $pass,
                'Reserve' => $reserve

            );

            $allDutCands[] = $element;
        }

        $fields = [
            'លេខបង្កាន់ដៃ',
            'ឈ្មោះ ខ្មែរ',
            'ឈ្មោះ ឡាតាំង',
            'ភេទ',
            'ថ្ងៃខែរឆ្នាំកំនើត',
            'វិទ្យាល័យ',
            'ប្រភព',
            'Bac Year',
            'Score',
            'Bac',
            'Math',
            'Phys',
            'Chim',
            'GCA',
            'GCI',
            'GEE',
            'GGG',
            'GIC',
            'GIM',
            'GRU',
            'Pass',
            'Reserve'

        ];

        $title = 'បញ្ជីបេក្ខជន DUT';
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        Excel::create($title, function ($excel) use ($allDutCands, $title, $alpha, $fields) {

            $excel->sheet($title, function ($sheet) use ($allDutCands, $title, $alpha, $fields) {
                $sheet->fromArray($allDutCands);
            });
        })->export('xls');

    }

    private function getCandidateFromDB($exam_id)
    {

        $candidates = Candidate::where('exam_id', $exam_id)
            ->leftJoin('genders', 'candidates.gender_id', '=', 'genders.id')
            ->leftJoin('origins', 'candidates.province_id', '=', 'origins.id')
            //->leftJoin('candidate_department','candidates.id','=','candidate_department.candidate_id')
            ->leftJoin('highSchools', 'candidates.highschool_id', '=', 'highSchools.id')
            ->leftJoin('gdeGrades as bacTotal', 'candidates.bac_total_grade', '=', 'bacTotal.id')
            ->leftJoin('gdeGrades as mathGrade', 'candidates.bac_math_grade', '=', 'mathGrade.id')
            ->leftJoin('gdeGrades as physGrade', 'candidates.bac_phys_grade', '=', 'physGrade.id')
            ->leftJoin('gdeGrades as chemGrade', 'candidates.bac_chem_grade', '=', 'chemGrade.id')
            ->orderBy('register_id')
            ->select([
                'candidates.id',
                'register_id',
                'candidates.name_kh',
                'candidates.name_latin',
                'genders.name_kh as gender',
                'candidates.dob',
                'highSchools.name_kh as highschool',
                'origins.name_kh as origin',
                'candidates.bac_year',
                'bacTotal.name_en as bac_total_grade',
                'mathGrade.name_en as bac_math_grade',
                'physGrade.name_en as bac_phys_grade',
                'chemGrade.name_en as bac_chem_grade',
                'candidates.bac_percentile',
                //'candidate_department.department_id',
                //'candidate_department.rank',
            ])
            ->get();

        return $candidates;
    }

    public function export_candidate_dut_list(DownloadExaminationDocumentsRequest $request, $exam_id)
    {


        $allDutCands = [];

        $candidates = $this->getCandidateFromDB($exam_id);

        foreach ($candidates as &$candidate) {
            $department_choices = DB::table('candidate_department')
                ->join('departments', 'candidate_department.department_id', '=', 'departments.id')
                ->where('candidate_id', $candidate['id'])
                ->select([
                    'candidate_department.rank',
                    'departments.code'
                ])
                ->orderBy('departments.code')
                ->get();

            $candidateOptions = [];
            foreach ($department_choices as $choice) {
                $candidateOptions[$choice->code] = $choice->rank;
            }
            $element = array(
                'លេខបង្កាន់ដៃ' => str_pad($candidate->register_id, 4, '0', STR_PAD_LEFT),
                'ឈ្មោះ ខ្មែរ' => $candidate->name_kh,
                'ឈ្មោះ ឡាតាំង' => $candidate->name_latin,
                'ភេទ' => $candidate->gender,
                'ថ្ងៃខែរឆ្នាំកំនើត' => $candidate->dob->formatLocalized("%d/%b/%Y"),
                'វិទ្យាល័យ' => $candidate->highschool,
                'ប្រភព' => $candidate->origin,
                'Bac Year' => $candidate->bac_year,
                'Score' => $candidate->bac_percentile,
                'Bac' => $candidate->bac_total_grade,
                'Math' => $candidate->bac_math_grade,
                'Phys' => $candidate->bac_phys_grade,
                'Chim' => $candidate->bac_chem_grade,
                'GCA' => $candidateOptions['GCA'],
                'GCI' => $candidateOptions['GCI'],
                'GEE' => $candidateOptions['GEE'],
                'GGG' => $candidateOptions['GGG'],
                'GIC' => $candidateOptions['GIC'],
                'GIM' => $candidateOptions['GIM'],
                'GRU' => $candidateOptions['GRU'],
            );

            $allDutCands[] = $element;
        }

        $fields = [
            'លេខបង្កាន់ដៃ',
            'ឈ្មោះ ខ្មែរ',
            'ឈ្មោះ ឡាតាំង',
            'ភេទ',
            'ថ្ងៃខែរឆ្នាំកំនើត',
            'វិទ្យាល័យ',
            'ប្រភព',
            'Bac Year',
            'Score',
            'Bac',
            'Math',
            'Phys',
            'Chim',
            'GCA',
            'GCI',
            'GEE',
            'GGG',
            'GIC',
            'GIM',
            'GRU'

        ];

        $title = 'បញ្ជីបេក្ខជន DUT';
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        Excel::create($title, function ($excel) use ($allDutCands, $title, $alpha, $fields) {

            $excel->sheet($title, function ($sheet) use ($allDutCands, $title, $alpha, $fields) {
                $sheet->fromArray($allDutCands);
            });
        })->export('xls');

    }

    public function download_candidate_list_ing(DownloadExaminationDocumentsRequest $request, $exam_id)
    {
        $academic_year = Exam::leftJoin('academicYears', 'exams.academic_year_id', '=', 'academicYears.id')
            ->where('exams.id', $exam_id)
            ->select('academicYears.id')
            ->first();
        $candidates = Candidate::where('exam_id', $exam_id)
            ->leftJoin('genders', 'candidates.gender_id', '=', 'genders.id')
            ->leftJoin('origins', 'candidates.province_id', '=', 'origins.id')
            ->leftJoin('highSchools', 'candidates.highschool_id', '=', 'highSchools.id')
            ->leftJoin('gdeGrades as bacTotal', 'candidates.bac_total_grade', '=', 'bacTotal.id')
            ->orderBy('register_id')
            ->select([
                'register_id',
                'candidates.name_kh',
                'candidates.name_latin',
                'genders.name_kh as gender',
                'candidates.dob',
                'highSchools.name_kh as highschool',
                'origins.name_kh as origin',
                'candidates.bac_year',
                'bacTotal.name_en as bac_total_grade',
                'candidates.bac_percentile'
            ])
            ->get()->toArray();

        $chunk_candidates = array_chunk($candidates, 43);


        return view('backend.exam.print.candidate_list_ing', compact('chunk_candidates', 'academic_year'));
    }

    public function download_registration_statistic(DownloadExaminationDocumentsRequest $request, $exam_id)
    {

        $academic_year = Exam::leftJoin('academicYears', 'exams.academic_year_id', '=', 'academicYears.id')
            ->where('exams.id', $exam_id)
            ->select('academicYears.id')
            ->first();
        $dates = Candidate::where('exam_id', $exam_id)
            ->orderBy('created_at')
            ->get()
            ->groupBy(function ($date) {
                $group = [
                    Carbon::parse($date->created_at)->format('d-m-Y')
                ];
                return $group;
            })
            ->toArray();

        $candidates = array();
        foreach ($dates as $key => $date) {
            $candidates[$key][34] = array();
            $candidates[$key][35] = array();
            $candidates[$key][36] = array();
            $candidates[$key][37] = array();
            $candidates[$key][38] = array();
            foreach ($date as $candidate) {
                array_push($candidates[$key][$candidate['bac_total_grade']], $candidate);
            }
        }


        ///------below is : result Candidate Engineer Statistic


        $candidatePassOrReserves = DB::table('candidates')->where('exam_id', $exam_id)
            ->leftJoin('genders', 'candidates.gender_id', '=', 'genders.id')
            ->leftJoin('origins', 'candidates.province_id', '=', 'origins.id')
            ->leftJoin('highSchools', 'candidates.highschool_id', '=', 'highSchools.id')
            ->leftJoin('gdeGrades as bacTotal', 'candidates.bac_total_grade', '=', 'bacTotal.id')
            ->leftJoin('gdeGrades as mathGrade', 'candidates.bac_math_grade', '=', 'mathGrade.id')
            ->leftJoin('gdeGrades as physGrade', 'candidates.bac_phys_grade', '=', 'physGrade.id')
            ->leftJoin('gdeGrades as chemGrade', 'candidates.bac_chem_grade', '=', 'chemGrade.id')
            ->orderBy('bac_total_grade')
            ->where(function ($query) {
                $query->where('candidates.result', "Pass")
                    ->orWhere('candidates.result', "Reserve");
            })
            ->select([
                'candidates.id',
                'register_id',
                'candidates.name_kh',
                'candidates.name_latin',
                'genders.name_kh as gender',
                'genders.code as code_gender',
                'candidates.dob',
                'highSchools.name_kh as highschool',
                'origins.name_kh as origin',
                'candidates.bac_year',
                'bacTotal.name_en as bac_total_grade',
                'mathGrade.name_en as bac_math_grade',
                'physGrade.name_en as bac_phys_grade',
                'chemGrade.name_en as bac_chem_grade',
                'candidates.bac_percentile',
                'candidates.result as can_result'
            ])
            ->get();

        $allCandidates = array();
        $arrayGrades = [
            'A',
            'B',
            'C',
            'D',
            'E',

        ];

        foreach ($candidatePassOrReserves as $candidate) {

            $allCandidates[$candidate->can_result][$candidate->bac_total_grade][$candidate->code_gender][] = $candidate;

        }
        // below is : Student Engineer Registration
        $exam = Exam::where('id', $exam_id)->first();

        $allStudents = [];

        $studentEngineers = DB::table('candidates')->where('exam_id', $exam_id)
            ->Join('genders', 'candidates.gender_id', '=', 'genders.id')
            ->Join('origins', 'candidates.province_id', '=', 'origins.id')
            ->Join('highSchools', 'candidates.highschool_id', '=', 'highSchools.id')
            ->Join('gdeGrades as bacTotal', 'candidates.bac_total_grade', '=', 'bacTotal.id')
            ->leftJoin('gdeGrades as mathGrade', 'candidates.bac_math_grade', '=', 'mathGrade.id')
            ->leftJoin('gdeGrades as physGrade', 'candidates.bac_phys_grade', '=', 'physGrade.id')
            ->leftJoin('gdeGrades as chemGrade', 'candidates.bac_chem_grade', '=', 'chemGrade.id')
            ->join('students', 'students.candidate_id', '=', 'candidates.id')
            ->join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
            ->join('departments', 'departments.id', '=', 'studentAnnuals.department_id')
            ->orderBy('bac_total_grade', 'ASC')
            ->where([
                ['candidates.is_register', true],
                ['studentAnnuals.academic_year_id', $exam->academic_year_id]
            ])
            ->select([
                'candidates.id',
                'register_id',
                'candidates.name_kh',
                'candidates.name_latin',
                'genders.name_kh as gender',
                'genders.code as code_gender',
                'candidates.dob',
                'highSchools.name_kh as highschool',
                'origins.name_kh as origin',
                'candidates.bac_year',
                'bacTotal.name_en as bac_total_grade',
                'mathGrade.name_en as bac_math_grade',
                'physGrade.name_en as bac_phys_grade',
                'chemGrade.name_en as bac_chem_grade',
                'candidates.bac_percentile',
                'candidates.is_register',
                'studentAnnuals.department_id',
                'departments.code as dept_name',
                'studentAnnuals.id as student_annual_id',
                'bacTotal.id as total_id'

                //'candidate_department.rank',
            ])
            ->get();

        foreach ($studentEngineers as $student) {

            $allStudents['TC'][$student->bac_total_grade][$student->code_gender][] = $candidate;

        }

        // candidates is the array of candidate who come for registration
        // allCandidates is the array of candidtes who pass or reserve
        // allStudents is the array of candidate who have register to study at ITC

        if ($request->type == "data_chart") {

            return view('backend.exam.includes.chart_datatable', compact('candidates', 'allCandidates', 'arrayGrades', 'allStudents'))->render();


        } elseif ($request->type == "data_chart_candidate_registration") {

            //this is the chart_data for candidate registration

            $allCandidates = [];

            foreach ($candidates as $key => $candidate) {

                $dateInt = (double)strtotime($key) * 1000;

                foreach ($candidate as $grade => $value) {

                    $allCandidates[$grade][] = [$key, ((double)count($value))];

                }
            }

            return Response::json($allCandidates);

        } else {

            if ($request->download == 'student_registration') {
                $allCandidates = [];//array of candidate result
                $candidates = [];
                return view('backend.exam.print.registration_statistic', compact('candidates', 'allCandidates', 'arrayGrades', 'allStudents'));

            } elseif ($request->download == 'candidate_engineer_result') {

                $allStudents = []; // array of student registration
                $candidates = []; // candidate registration
                return view('backend.exam.print.registration_statistic', compact('candidates', 'allCandidates', 'arrayGrades', 'allStudents'));

            } elseif ($request->download == 'candidate_engineer_registration') {

                $allStudents = []; // array of student registration
                $allCandidates = []; // candidate registration
                return view('backend.exam.print.registration_statistic', compact('candidates', 'allCandidates', 'arrayGrades', 'allStudents', 'academic_year'));

            } else {
                return view('backend.exam.print.registration_statistic', compact('candidates', 'allCandidates', 'arrayGrades', 'allStudents', 'academic_year'));
            }

        }

    }

    public function download_dut_registration_statistic($exam_id)
    {

        $academic_year = Exam::leftJoin('academicYears', 'exams.academic_year_id', '=', 'academicYears.id')
            ->where('exams.id', $exam_id)
            ->select('academicYears.id')
            ->first();
        $candidateDuts = $this->dutRegistration($exam_id);

        $candidates = $candidateDuts[0];
        if (!isset($candidates[34])) {
            $candidates[34] = [];
            $candidates[34]['M'] = [];
            $candidates[34]['F'] = [];
        }
        if (!isset($candidates[35])) {
            $candidates[35] = [];
            $candidates[35]['M'] = [];
            $candidates[35]['F'] = [];
        }
        if (!isset($candidates[36])) {
            $candidates[36] = [];
            $candidates[36]['M'] = [];
            $candidates[36]['F'] = [];
        }
        if (!isset($candidates[37])) {
            $candidates[37] = [];
            $candidates[37]['M'] = [];
            $candidates[37]['F'] = [];
        }
        if (!isset($candidates[38])) {
            $candidates[38] = [];
            $candidates[38]['M'] = [];
            $candidates[38]['F'] = [];
        }
        ksort($candidates);
        $total = $candidateDuts[1];
        return view('backend.exam.print.dut_registration_statistic', compact('candidates', 'total', 'academic_year'));
    }

    private function dutRegistration($exam_id)
    {


        $candidateDuts = Candidate::where('exam_id', $exam_id)
            ->join('genders', 'genders.id', '=', 'candidates.gender_id')
            ->orderBy('bac_total_grade')
            ->get()
            ->groupBy(function ($candidateDut) {
                $group = [
                    $candidateDut->bac_total_grade
                ];
                return $group;
            })
            ->toArray();


        $candidates = array();
        $total = 0;
        foreach ($candidateDuts as $key => $candDUT) {
            $total = $total + count($candDUT);
            $candidates[$key]['M'] = array();
            $candidates[$key]['F'] = array();
            foreach ($candDUT as $candidate) {
                array_push($candidates[$key][$candidate['code']], $candidate);
            }
        }


        return array($candidates, $total);

    }

    public function requestEachChartData($examId, Request $request)
    {

        $CandidateMale = [];
        $CandidateFemale = [];

        if ($request->type == 'candidate_dut_registration') {

            $dataReturn = $this->dutRegistration($examId);
            $candidateDuts = $dataReturn[0];
            $total = $dataReturn[1];
            $status = 'candidate_dut_registration';

            return view('backend.exam.includes.chart_datatable_candidate_dut', compact('candidateDuts', 'total', 'status'));

        } elseif ($request->type == 'result_candidate_dut_statistic') {


            $arrayData = $this->dutResultStatistic($examId);
            $candRes = $arrayData['arrayCands'];
            $depts = $arrayData['allDepts'];
            $grades = $arrayData['arrayGrades'];

            foreach ($grades as $key => $grade) {
                $element_M = [];
                $element_F = [];

                foreach ($depts as $dept) {

                    if (isset($candRes[$dept->name_abr])) {
                        $candByGrades = $candRes[$dept->name_abr];

                        if (isset($candByGrades[$key])) {

                            if (isset($candByGrades[$key]['M'])) {
                                $element_M[] = count($candByGrades[$key]['M']);
                            } else {
                                $element_M[] = 0;
                            }
                            if (isset($candByGrades[$key]['F'])) {
                                $element_F[] = count($candByGrades[$key]['F']);
                            } else {
                                $element_F[] = 0;
                            }

                        } else {
                            $element_M[] = 0;
                            $element_F[] = 0;

                        }
                    } else {
                        $element_M[] = 0;
                        $element_F[] = 0;
                    }

                }

                $CandidateMale[$grade] = $element_M;
                $CandidateFemale[$grade] = $element_F;

            }

            return Response::json(['CandidateMale' => $CandidateMale, 'CandidateFemale' => $CandidateFemale]);

        } elseif ($request->type == 'student_dut_registration') {

            $studentDUTMale = [];
            $studentDUTFemale = [];


            $students = $this->studentDUTRegistration($examId);
            $allDepts = $this->getAllDepartments();
            $grades = ['A', 'B', 'C', 'D', 'E'];
            foreach ($allDepts as $allDept) {

                if ($students) {
                    foreach ($students as $student) {

                        if ($student->department_id == $allDept->department_id) {
                            $candidates[$allDept->name_abr][$student->bac_total_grade][$student->code_gender][] = $student;

                        }
                    }
                }
            }

            foreach ($grades as $grade) {
                $element_M = [];
                $element_F = [];

                foreach ($allDepts as $dept) {

                    if (isset($candidates[$dept->name_abr])) {
                        $candByGrades = $candidates[$dept->name_abr];

                        if (isset($candByGrades[$grade])) {

                            if (isset($candByGrades[$grade]['M'])) {
                                $element_M[] = count($candByGrades[$grade]['M']);
                            } else {
                                $element_M[] = 0;
                            }
                            if (isset($candByGrades[$grade]['F'])) {
                                $element_F[] = count($candByGrades[$grade]['F']);
                            } else {
                                $element_F[] = 0;
                            }

                        } else {
                            $element_M[] = 0;
                            $element_F[] = 0;

                        }
                    } else {
                        $element_M[] = 0;
                        $element_F[] = 0;
                    }
                }
                $studentDUTMale[$grade] = $element_M;
                $studentDUTFemale[$grade] = $element_F;
            }

            return Response::json(['studentMale' => $studentDUTMale, 'studentFemale' => $studentDUTFemale]);

        }

    }

    private function dutResultStatistic($exam_id)
    {
        $studentByDept = $this->arrayStudentPassOrReserveByDept($exam_id, $is_success = 'Pass');//candidates dut pass by department

        $allDepts = $this->getAllDepartments();

        $allCandidateByDept = $studentByDept[1];
        $candidates = array();
        $arrayGrades = [];
        $gradeA_M = 0;
        $gradeA_F = 0;
        $gradeB_M = 0;
        $gradeB_F = 0;
        $gradeC_M = 0;
        $gradeC_F = 0;
        $gradeD_M = 0;
        $gradeD_F = 0;
        $gradeE_M = 0;
        $gradeE_F = 0;
        $totalBydept = [];

        foreach ($allCandidateByDept as $key => $candidate) {

            usort($candidate, function ($a, $b) {
                return $a->total_grade - $b->total_grade;
            });

            foreach ($candidate as $cand) {
                $candidates[$key][$cand->total_grade][$cand->code_gender][] = $cand;
                $arrayGrades[$cand->total_grade] = $cand->total_grade;

                if ($cand->total_grade == 34) {
                    if ($cand->code_gender == 'M') {
                        $gradeA_M++;
                    } else {
                        $gradeA_F++;
                    }
                } elseif ($cand->total_grade == 35) {
                    if ($cand->code_gender == 'M') {
                        $gradeB_M++;
                    } else {
                        $gradeB_F++;
                    }

                } elseif ($cand->total_grade == 36) {
                    if ($cand->code_gender == 'M') {
                        $gradeC_M++;
                    } else {
                        $gradeC_F++;
                    }

                } elseif ($cand->total_grade == 37) {
                    if ($cand->code_gender == 'M') {
                        $gradeD_M++;
                    } else {

                        $gradeD_F++;
                    }

                } else {
                    if ($cand->code_gender == 'M') {
                        $gradeE_M++;
                    } else {

                        $gradeE_F++;
                    }

                }


            }

            $total_dept_grade = $gradeA_M + $gradeA_F + $gradeB_M + $gradeB_F + $gradeC_M + $gradeC_F + $gradeD_M + $gradeD_F + $gradeE_M + $gradeE_F;

            $totalBydept[34]['M'] = $gradeA_M;
            $totalBydept[35]['M'] = $gradeB_M;
            $totalBydept[36]['M'] = $gradeC_M;
            $totalBydept[37]['M'] = $gradeD_M;
            $totalBydept[38]['M'] = $gradeE_M;

            $totalBydept[34]['F'] = $gradeA_F;
            $totalBydept[35]['F'] = $gradeB_F;
            $totalBydept[36]['F'] = $gradeC_F;
            $totalBydept[37]['F'] = $gradeD_F;
            $totalBydept[38]['F'] = $gradeE_F;

        }

        return array(
            'arrayCands' => $candidates,
            'allDepts' => $allDepts,
            'arrayGrades' => $arrayGrades,
            'totalByDept' => $totalBydept,
            'totalGradeByDept' => $total_dept_grade
        );

    }

    private function arrayStudentPassOrReserveByDept($examId, $is_success)
    {

        $uniqueDept = [];
        $allStudentByDept = [];
        $allDepts = $this->getAllDepartments();
        if ($allDepts) {
            foreach ($allDepts as $allDept) {

                $studentPassedByDept = $this->getPassOrReserveByDept($examId, $allDept->department_id, $is_success);

                if ($studentPassedByDept) {
                    $uniqueDept[] = $allDept->name_abr;
                    $allStudentByDept[$allDept->name_abr] = $studentPassedByDept;
                }
            }

            return array($uniqueDept, $allStudentByDept);
        }
    }

    private function getAllDepartments()
    {

        $dept = DB::table('departments')
            ->select('departments.id as department_id', 'departments.name_en as department_name', 'departments.code as name_abr')
            ->where([
                ['departments.active', '=', true],
                ['is_specialist', '=', true],
                ['parent_id', '=', 11]
            ])
            ->orderBy('name_abr', 'ASC')
            ->get();

        return $dept;
    }

    private function getPassOrReserveByDept($examId, $deptId, $is_success)
    {


        $dUTCandidates = DB::table('candidates')
            ->join('candidate_department', 'candidates.id', '=', 'candidate_department.candidate_id')
            ->join('departments', 'departments.id', '=', 'candidate_department.department_id')
            ->join('genders', 'genders.id', '=', 'candidates.gender_id')
            ->join('academicYears', 'academicYears.id', '=', 'candidates.academic_year_id')
            ->join('origins', 'origins.id', '=', 'candidates.province_id')
            ->where([
                ['candidates.exam_id', '=', $examId],
                ['candidate_department.is_success', '=', $is_success],
                ['departments.id', '=', $deptId],
                ['origins.is_province', '=', true],
                ['origins.active', '=', true]

            ])
            ->select('candidates.bac_total_grade as total_grade', 'origins.name_kh as province_name', 'academicYears.name_kh as academic_year', 'candidates.register_id', 'candidates.dob as birth_date', 'candidates.register_from as home_town', 'genders.name_kh as gender', 'genders.code as code_gender', 'candidates.id as candidate_id', 'candidates.name_kh', 'candidates.name_latin', 'candidate_department.is_success', 'candidate_department.rank', 'departments.code as department_name', 'departments.id as department_id', 'candidates.bac_percentile')
            ->orderBy('register_id', 'ASC')
            ->get();

        return $dUTCandidates;
    }

    private function studentDUTRegistration($exam_id)
    {


        $exam = Exam::where('id', $exam_id)->first();

        $students = DB::table('candidates')->where('exam_id', $exam_id)
            ->Join('genders', 'candidates.gender_id', '=', 'genders.id')
            ->Join('origins', 'candidates.province_id', '=', 'origins.id')
            ->Join('highSchools', 'candidates.highschool_id', '=', 'highSchools.id')
            ->Join('gdeGrades as bacTotal', 'candidates.bac_total_grade', '=', 'bacTotal.id')
            ->leftJoin('gdeGrades as mathGrade', 'candidates.bac_math_grade', '=', 'mathGrade.id')
            ->leftJoin('gdeGrades as physGrade', 'candidates.bac_phys_grade', '=', 'physGrade.id')
            ->leftJoin('gdeGrades as chemGrade', 'candidates.bac_chem_grade', '=', 'chemGrade.id')
            ->join('students', 'students.candidate_id', '=', 'candidates.id')
            ->join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
            ->join('departments', 'departments.id', '=', 'studentAnnuals.department_id')
            ->orderBy('bac_total_grade', 'ASC')
            ->where([
                ['candidates.is_register', true],
                ['studentAnnuals.academic_year_id', $exam->academic_year_id]
            ])
            ->select([
                'candidates.id',
                'register_id',
                'candidates.name_kh',
                'candidates.name_latin',
                'genders.name_kh as gender',
                'genders.code as code_gender',
                'candidates.dob',
                'highSchools.name_kh as highschool',
                'origins.name_kh as origin',
                'candidates.bac_year',
                'bacTotal.name_en as bac_total_grade',
                'mathGrade.name_en as bac_math_grade',
                'physGrade.name_en as bac_phys_grade',
                'chemGrade.name_en as bac_chem_grade',
                'candidates.bac_percentile',
                'candidates.is_register',
                'studentAnnuals.department_id',
                'departments.code as dept_name',
                'studentAnnuals.id as student_annual_id',
                'bacTotal.id as total_id'

                //'candidate_department.rank',
            ])
            ->get();


        return $students;


    }

    /*public function request_add_courses($exam_id){
        $exam = $this->exams->findOrThrowException($exam_id);
        if($exam->type_id == 1){  // This ID=1 is for entrance engineer
            return view('backend.exam.includes.popup_add_course',compact('rooms','exam_id'));
        } else{
            return view('backend.exam.includes.popup_add_course',compact('rooms','exam_id'));
        }
    }*/

    public function download_dut_result_statistic($exam_id)
    {

        $arrayData = $this->dutResultStatistic($exam_id);
        $candidates = $arrayData['arrayCands'];
        $allDepts = $arrayData['allDepts'];
        $arrayGrades = $arrayData['arrayGrades'];
        $totalBydept = $arrayData['totalByDept'];
        $total_dept_grade = $arrayData['totalGradeByDept'];

        return view('backend.exam.print.dut_result_statistic', compact('candidates', 'allDepts', 'arrayGrades', 'totalBydept', 'total_dept_grade'));
    }

    public function download_student_dut_registration_statistic($exam_id)
    {

        $academic_year = Exam::leftJoin('academicYears', 'exams.academic_year_id', '=', 'academicYears.id')
            ->where('exams.id', $exam_id)
            ->select('academicYears.id')
            ->first();
        $students = $this->studentDUTRegistration($exam_id);
        $allDepts = $this->getAllDepartments();

        $candidates = [];
        $arrayGrades['A'] = 'A';
        $arrayGrades['B'] = 'B';
        $arrayGrades['C'] = 'C';
        $arrayGrades['D'] = 'D';
        $arrayGrades['E'] = 'E';
        $gradeA_M = 0;
        $gradeA_F = 0;
        $gradeB_M = 0;
        $gradeB_F = 0;
        $gradeC_M = 0;
        $gradeC_F = 0;
        $gradeD_M = 0;
        $gradeD_F = 0;
        $gradeE_M = 0;
        $gradeE_F = 0;
        $totalBydept = [];

        foreach ($allDepts as $allDept) {

            if ($students) {
                foreach ($students as $student) {

                    if ($student->department_id == $allDept->department_id) {
                        $candidates[$allDept->name_abr][$student->bac_total_grade][$student->code_gender][] = $student;


                        if ($student->bac_total_grade == 'A') {
                            if ($student->code_gender == 'M') {
                                $gradeA_M++;
                            } else {
                                $gradeA_F++;
                            }
                        } elseif ($student->bac_total_grade == 'B') {
                            if ($student->code_gender == 'M') {
                                $gradeB_M++;
                            } else {
                                $gradeB_F++;
                            }

                        } elseif ($student->bac_total_grade == 'C') {
                            if ($student->code_gender == 'M') {
                                $gradeC_M++;
                            } else {
                                $gradeC_F++;
                            }

                        } elseif ($student->bac_total_grade == 'D') {
                            if ($student->code_gender == 'M') {
                                $gradeD_M++;
                            } else {

                                $gradeD_F++;
                            }

                        } else {
                            if ($student->code_gender == 'M') {
                                $gradeE_M++;
                            } else {

                                $gradeE_F++;
                            }

                        }

                        $totalBydept['A']['M'] = $gradeA_M;
                        $totalBydept['B']['M'] = $gradeB_M;
                        $totalBydept['C']['M'] = $gradeC_M;
                        $totalBydept['D']['M'] = $gradeD_M;
                        $totalBydept['E']['M'] = $gradeE_M;

                        $totalBydept['A']['F'] = $gradeA_F;
                        $totalBydept['B']['F'] = $gradeB_F;
                        $totalBydept['C']['F'] = $gradeC_F;
                        $totalBydept['D']['F'] = $gradeD_F;
                        $totalBydept['E']['F'] = $gradeE_F;
                    }
                }
            }
        }


        return view('backend.exam.print.student_dut_registration_statistic', compact('candidates', 'allDepts', 'arrayGrades', 'totalBydept', 'academic_year'));


    }

    public function save_entrance_exam_course(CreateEntranceExamCourseRequest $request, $exam_id)
    {
        $input = $request->all();

        $input['create_uid'] = auth()->id();
        $input['created_at'] = Carbon::now();
        $input['exam_id'] = $exam_id;

        if (EntranceExamCourse::create($input)) {
            return Response::json(array('success' => true));
        } else {
            return Response::json(array('success' => false));
        }
    }

    public function requestInputScoreCourses($exam_id)
    {

        $courses = EntranceExamCourse::where('exam_id', $exam_id)->get();
        return view('backend.exam.includes.popup_add_input_score_course', compact('exam_id', 'courses'));
    }

    public function getAllRooms($exam_id, Request $request)
    { // From select course, roomcode, correction number form

        $correction = $request->number_correction;
        $entranceCourseId = $request->entrance_course_id;

        $rooms = $this->roomWithNotInputtedScoreCandidate($exam_id, $correction, $entranceCourseId);


        return view('backend.exam.includes.partial_selection_room_course', compact('rooms'))->render();


    }

    private function roomWithNotInputtedScoreCandidate($exam_id, $correction, $entranceCourseId)
    {

        $readyRooms = [];
        //get all examRooms -> get all candidate in each room -> get all the number correction of candidate from db then compare if the sequence = the requested correction then we dont get that room

        // Room which have already score
        $roomWithIputtedScore = DB::table('secret_room_score')
            ->where('exam_id', $exam_id)
            ->where('sequence', $correction)
            ->where('course_id', $entranceCourseId)
            ->groupBy('roomcode')
            ->lists('roomcode');


        $allRooms = DB::table('examRooms')
            ->where('examRooms.exam_id', '=', $exam_id)
            ->lists('roomcode', 'id');

        $rooms = [];

        foreach ($allRooms as $key => $room) {
            $code = Crypt::decrypt($room);
            if (!in_array($code, $roomWithIputtedScore)) {
                $rooms[$key] = $code;
            }
        }

        asort($rooms);
        return $rooms;

    }

    public function getRequestInputScoreForm($exam_id, Request $request)
    {


        $subject = $request->course_name;
        $subjectId = $request->entrance_course_id;
        $roomId = $request->room_id;
        $roomCode = $request->room_code;
        $number_correction = (int)$request->number_correction;
        $rooms = [];
        //$allRooms = $this->getRoomsFromDB($exam_id);
        $roomForSelection = [];

        $availableRooms = $this->roomWithNotInputtedScoreCandidate($exam_id, $number_correction, $subjectId);

        //dd($availableRooms);
        $all_keys = array_keys($availableRooms);
        $cur_key = array_search($roomId, $all_keys);

        $preRoom = null;
        $nextRoom = null;
        if ($cur_key - 1 > 0) {
            $prev_key = $all_keys[$cur_key - 1];
            $preRoom = ['room_id' => $prev_key, 'room_code' => $availableRooms[$prev_key]];
        }

        if ($cur_key + 1 < count($all_keys)) {
            $next_key = $all_keys[$cur_key + 1];
            $nextRoom = ['room_id' => $next_key, 'room_code' => $availableRooms[$next_key]];
        }


        if ($number_correction !== 0) {

            $candidates = $this->exams->requestInputScoreForm($exam_id, $request, $number_correction);

            if ($candidates) {
                $status = true;
            } else {
                $status = false;
            }

            return view('backend.exam.includes.form_input_score_candidates', compact('status', 'candidates', 'exam_id', 'roomCode', 'subject', 'number_correction', 'subjectId', 'roomId', 'nextRoom', 'preRoom', 'availableRooms'));

        } else {

            return Response::json(['status' => false]);
        }

    }

    public function insertScoreForEachCandiate($exam_id, Requests\Backend\Exam\StoreEntranceExamScoreRequest $request)
    {

        $requestDatas = $_POST;

        $correctorName = $request->corrector_name;

        if ($correctorName) {
            $candidates = $this->exams->insertCandidateScore($exam_id, $requestDatas, $correctorName);

            if ($candidates['status']) {
                return Response::json(['status' => true]);
            } else {
                return Response::json(['status' => false]);
            }
        } else {
            return Response::json(['status' => false]);
        }


    }

    public function reportErrorCandidateScores($exam_id, Request $request)
    {

        $courseId = $request->course_id;
        $errorCandidateScores = $this->exams->getErrorScore($exam_id, $request->course_id);

        $totalQuestions = DB::table('entranceExamCourses')
            ->where([
                ['entranceExamCourses.active', '=', true],
                ['entranceExamCourses.id', '=', $courseId]
            ])
            ->select('total_question', 'name_kh')
            ->first();

        $totalQuestion = $totalQuestions->total_question;
        $courseName = $totalQuestions->name_kh;

        return view('backend.exam.includes.popup_report_error_score_candidate', compact('exam_id', 'errorCandidateScores', 'totalQuestion', 'courseId', 'courseName'));

    }

    public function addNewCorrectionScore($exam_id, NewCorrectionExamScoreRequest $request)
    {

        $correctorName = $request->corrector_name;

        if ($correctorName) {
            $res = $this->exams->addNewCorrectionCandidateScore($exam_id, $request, $correctorName);
            if ($res) {
                return Response::json(['status' => true]);
            } else {
                return Response::json(['status' => false]);
            }
        } else {
            return Response::json(['status' => false]);
        }

    }

    public function candidateResultExamScores($exam_id)
    {

        $courseIds = $this->getAllExamCourses($exam_id);
        $checkScore = $this->checkEntranceExamScores($exam_id, $courseIds);
        $check = $checkScore[0];
        $courses = $checkScore[1];

        if ($courseIds) {
            if ($check == count($courseIds)) {

                return view('backend.exam.includes.popup_get_form_result_score', compact('exam_id', 'courseIds'));

            } else {
                return view('backend.exam.includes.error_popup_message', compact('courses'))->with(['message' => 'There is an existing score error']);
            }
        }


    }

    private function getAllExamCourses($exam_id)
    {

        $courseIds = DB::table('entranceExamCourses')
            ->where([
                ['exam_id', '=', $exam_id],
                ['entranceExamCourses.active', '=', true]
            ])
            ->select('id as course_id', 'name_kh as course_name')->get();

        return $courseIds;
    }

    private function checkEntranceExamScores($exam_id, $courseIds)
    {

        $check = 0;
        $courses = [];
        if ($courseIds) {
            foreach ($courseIds as $courseId) {
                $restult = DB::table('statusCandidateScores')
                    ->select('status')
                    ->where([
                        ['exam_id', '=', $exam_id],
                        ['entrance_exam_course_id', '=', $courseId->course_id]
                    ])
                    ->first();

                if ($restult->status == false) {
                    $check++;
                } else {
                    $courses[] = $courseId->course_name;
                }

            }

            return [$check, $courses];

        }
    }

    public function checkCandidateScores($examId)
    {

        $courseIds = $this->getAllExamCourses($examId);
        $check = 0;
        //$checkScore = $this->checkEntranceExamScores($examId, $courseIds);

        foreach ($courseIds as $courseId) {

            $errorCandidateScores = $this->exams->getErrorScore($examId, $courseId->course_id);

            if (count($errorCandidateScores) > 0) {
                $check++;
            }
        }

        if ($check == count($courseIds)) {
            return Response::json(['status' => true]);//there are error of candidate score
        } else {
            return Response::json(['status' => false]);// there are no error
        }


    }

    public function calculateCandidateScores($examId, CalculateExamScoreRequest $request)
    {

        $requestData = $_POST;
        $passedCandidates = (int)$requestData['total_pass'];
        $reservedCandidates = (int)$requestData['total_reserve'];


        // Clean all data in secret_room_result
        DB::table('secret_room_result')->delete();

        // Calculate total score to each order in secret room
        DB::transaction(function () use ($requestData, $examId) {
            foreach ($requestData['course'] as $course) {
                $total_question = DB::table('entranceExamCourses')->where('id', $course['id'])->first()->total_question;

                $query = DB::select(
                    "select roomcode, order_in_room,course_id,exam_id,score_c,score_w,score_na, count(*), (score_c+score_w+score_na) as total" .
                    " from secret_room_score" .
                    " where exam_id =" . $examId .
                    " and course_id =" . $course['id'] .
                    " group by roomcode,order_in_room,course_id,exam_id,score_c,score_w,score_na" .
                    " HAVING count(*) > 1" .
                    " order by roomcode, order_in_room, course_id;"
                );

                foreach ($query as $result) {
                    if ($result->total == $total_question || $result->total == 0) { // This one is correct, just double check
                        $score = (($result->score_c * (int)$course['correct']) - ($result->score_w * (int)$course['wrong'])) * (int)$course['coe'];

                        $secret_room_result = DB::table('secret_room_result')
                            ->where('roomcode', $result->roomcode)
                            ->where('exam_id', $examId)
                            ->where('order_in_room', $result->order_in_room)
                            ->select('score')
                            ->first();

                        if ($secret_room_result == null) { // Not yet add
                            if ($result->score_c == 0 && $result->score_w == 0 && $result->score_na == 0) { // Once course is absence
                                DB::table('secret_room_result')
                                    ->insert(
                                        ['roomcode' => $result->roomcode, 'exam_id' => $examId, 'order_in_room' => $result->order_in_room, 'score' => 0, 'is_absence' => true]
                                    );
                            } else {
                                DB::table('secret_room_result')
                                    ->insert(
                                        ['roomcode' => $result->roomcode, 'exam_id' => $examId, 'order_in_room' => $result->order_in_room, 'score' => $score]
                                    );
                            }

                        } else {
                            if ($result->score_c == 0 && $result->score_w == 0 && $result->score_na == 0) { // Once course is absence
                                DB::table('secret_room_result')
                                    ->where('roomcode', $result->roomcode)
                                    ->where('exam_id', $examId)
                                    ->where('order_in_room', $result->order_in_room)
                                    ->update(
                                        ['score' => 0, 'is_absence' => true]
                                    );
                            } else {
                                DB::table('secret_room_result')
                                    ->where('roomcode', $result->roomcode)
                                    ->where('exam_id', $examId)
                                    ->where('order_in_room', $result->order_in_room)
                                    ->update(
                                        ['score' => $score + $secret_room_result->score]
                                    );
                            }
                        }
                    }
                }
            }
        });

        // Categorize to pass, reserve or fail or absence

        $final_results = DB::table('secret_room_result')
            ->where('exam_id', $examId)
            ->orderBy('score', 'desc')
            ->get();

        $last_score = null;
        foreach ($final_results as $final_result) {

            if ($final_result->is_absence == true) { // This student have 0 score, that mean they are absence
                DB::table('secret_room_result')
                    ->where('roomcode', $final_result->roomcode)
                    ->where('exam_id', $examId)
                    ->where('order_in_room', $final_result->order_in_room)
                    ->update(
                        ['result' => "Reject"]
                    );
            } else {
                if ($passedCandidates > 0) {
                    DB::table('secret_room_result')
                        ->where('roomcode', $final_result->roomcode)
                        ->where('exam_id', $examId)
                        ->where('order_in_room', $final_result->order_in_room)
                        ->update(
                            ['result' => "Pass"]
                        );
                    $passedCandidates--;

                } else if ($passedCandidates == 0) { // The first candidate out of passed range

                    if ($last_score != null && $last_score->score == $final_result->score) {
                        DB::table('secret_room_result')
                            ->where('roomcode', $final_result->roomcode)
                            ->where('exam_id', $examId)
                            ->where('order_in_room', $final_result->order_in_room)
                            ->update(
                                ['result' => "Pass"]
                            );
                    } else {
                        DB::table('secret_room_result')
                            ->where('roomcode', $final_result->roomcode)
                            ->where('exam_id', $examId)
                            ->where('order_in_room', $final_result->order_in_room)
                            ->update(
                                ['result' => "Reserve"]
                            );
                        $passedCandidates--;
                    }
                } else if ($reservedCandidates > 0) {
                    DB::table('secret_room_result')
                        ->where('roomcode', $final_result->roomcode)
                        ->where('exam_id', $examId)
                        ->where('order_in_room', $final_result->order_in_room)
                        ->update(
                            ['result' => "Reserve"]
                        );
                    $reservedCandidates--;

                } else if ($reservedCandidates == 0) {
                    if ($last_score != null && $last_score->score == $final_result->score) {
                        DB::table('secret_room_result')
                            ->where('roomcode', $final_result->roomcode)
                            ->where('exam_id', $examId)
                            ->where('order_in_room', $final_result->order_in_room)
                            ->update(
                                ['result' => "Reserve"]
                            );
                    } else {
                        DB::table('secret_room_result')
                            ->where('roomcode', $final_result->roomcode)
                            ->where('exam_id', $examId)
                            ->where('order_in_room', $final_result->order_in_room)
                            ->update(
                                ['result' => "Fail"]
                            );
                        $reservedCandidates--;
                    }
                } else {
                    DB::table('secret_room_result')
                        ->where('roomcode', $final_result->roomcode)
                        ->where('exam_id', $examId)
                        ->where('order_in_room', $final_result->order_in_room)
                        ->update(
                            ['result' => "Fail"]
                        );
                }
                $last_score = $final_result;
            }

        }

        // Mapping with secret room

        $exam_rooms = ExamRoom::where('exam_id', $examId)
            ->get();

        foreach ($exam_rooms as $exam_room) {
            $candidates = DB::table('candidates')
                ->where('room_id', $exam_room->id)
                ->orderBy('register_id', 'ASC')
                ->get();

            $candidateResults = [];

            $candResults = DB::table('secret_room_result')
                ->where('secret_room_result.roomcode', '=', Crypt::decrypt($exam_room->roomcode))
                ->get();

            foreach ($candResults as $candResult) {
                $candidateResults[$candResult->order_in_room] = (['result' => $candResult->result, 'score' => $candResult->score]);
            }

            if ($candidateResults) {
                if ($candResults) {

                    $index = 1;
                    if ($index <= count($candidates)) {

                        foreach ($candidates as $candidate) {
                            if (isset($candidateResults[$index])) {
                                $update = DB::table('candidates')
                                    ->where('candidates.id', $candidate->id)
                                    ->update(
                                        ['result' => $candidateResults[$index]['result'], 'total_score' => $candidateResults[$index]['score']]
                                    );
                            }

                            $index++;
                        }
                    }

                }
            }
        }

        return Response::json(['status' => true, 'exam_id' => $examId]);

    }

    public function candidateResultLists(Request $request)
    {

        $examId = $request->exam_id;

        $candidatesResults = $this->getCandidateResult($examId);

        return view('backend.exam.includes.examination_candidates_result', compact('candidatesResults', 'examId'));
    }

    private function getCandidateResult($exam_id)
    {

        $studentPassed = $this->candResFromDB($exam_id, $resultType = 'Pass');


        $studentReserved = $this->candResFromDB($exam_id, $resultType = 'Reserve');

//        $studentFail = $this->candResFromDB($exam_id, $resultType='Fail');

//        $studentReject = $this->candResFromDB($exam_id, $resultType='Reject');

//        usort($studentPassed, array($this, "sortCandidateRank"));
//        usort($studentReserved, array($this, "sortCandidateRank"));
//        usort($studentFail, array($this, "sortCandidateRank"));


//        $candidateTmp1 =  array_merge((array) $studentPassed, (array) $studentReserved);
//        $candidateTmp2 = array_merge((array) $studentFail, (array) $studentReject);

//        $candidateResults = array_merge((array) $candidateTmp1, (array) $candidateTmp2);


        return array('ស្ថាពរ' => $studentPassed, 'បម្រុង' => $studentReserved);


    }

    private function candResFromDB($exam_id, $resultType)
    {

        $candRes = DB::table('candidates')
            ->join('genders', 'genders.id', '=', 'candidates.gender_id')
            ->join('examRooms', 'examRooms.id', '=', 'candidates.room_id')
            ->join('buildings', 'examRooms.building_id', '=', 'buildings.id')
            ->join('origins', 'origins.id', '=', 'candidates.province_id')
            ->where([
                ['candidates.result', '=', $resultType],
                ['candidates.active', '=', true],
                ['candidates.exam_id', '=', $exam_id]

            ])
            ->select(
                'candidates.name_kh',
                'candidates.name_latin',
                'candidates.dob',
                'candidates.result',
                'candidates.total_score',
                'candidates.id as candidate_id',
                'candidates.register_id',
                'genders.code as gender',
                'examRooms.name as room',
                'buildings.code as building',
                'origins.name_kh as origin'
            )
            ->orderBy('register_id', 'ASC')
            ->get();

        return $candRes;
    }

    public function printCandidateResultLists(Request $request)
    {
        if ($request->status == 'request_print_page') {
            return Response::json(['status' => true]);
        } else {
            $academic_year = Exam::leftJoin('academicYears', 'exams.academic_year_id', '=', 'academicYears.id')
                ->where('exams.id', $request->exam_id)
                ->select('academicYears.id')
                ->first();
            $candidateRes = $this->getCandidateResult($request->exam_id);

            if ($candidateRes) {
                $status = true;
//                $candidatesResults = array_chunk($candidatesResults, 30);
                return view('backend.exam.print.examination_candidates_result', compact('candidateRes', 'status', 'academic_year'));
            } else {
                $status = false;
                return view('backend.exam.print.examination_candidates_result', compact('status', 'academic_year'));
            }
        }
    }

    public function export_candidate_ministry_list($exam_id)
    {

        $exam = Exam::where('id', $exam_id)->first();
        $genders = collect(DB::table('genders')->select('*')->get())->keyBy('id');
        $studentBac2sFromMinistry = DB::table('studentBac2s')
            ->where('bac_year', '=', ($exam->academic_year_id-1))
            ->where('status', '=', 'Ministry')
            ->orderBy('can_id')
            ->select('*');

        $highSchoolIds = $studentBac2sFromMinistry->pluck('highschool_id');
        $originIds = $studentBac2sFromMinistry->pluck('pob');
        $origins = collect( DB::table('origins')->whereIN('id', $originIds)->get())->keyBy('id');
        $highSchools = collect( DB::table('highSchools')->whereIN('id', $highSchoolIds)->get())->keyBy('id');
        $grades = collect( DB::table('gdeGrades')->get())->keyBy('id');
        $studentBac2sIds = $studentBac2sFromMinistry->pluck('id');
        $studentBac2sFromMinistry = collect($studentBac2sFromMinistry->get())->keyBy('id');

        $ministryCandidates = DB::table('candidates')
            ->where('exam_id', $exam_id)
            ->where('degree_id', '=', 1)
            ->whereIN('studentBac2_id', $studentBac2sIds)
            ->select('*')
            ->orderBy('candidates.total_score', 'DESC')
            ->get();
        $ministryCandidates = collect($ministryCandidates)->groupBy('result');
        $passedCandidates = $ministryCandidates['Pass']->keyBy('studentBac2_id');
        $reservedCandidates = $ministryCandidates['Reserve']->keyBy('studentBac2_id');
        $failCandidates = $ministryCandidates['Fail']->keyBy('studentBac2_id');
        $rejectedCandidates = $ministryCandidates['Reject']->keyBy('studentBac2_id');

        $count_pass = 0;
        $count_reserve = 0;
        $count_fail = 0;
        $count_reject = 0;
        $selectedStudents = [];
        $rec_pass = $passedCandidates->map(function ($item, $key) use(&$selectedStudents, &$studentBac2sFromMinistry, $genders, $origins, $highSchools, &$count_pass) {
            $count_pass++;
            $rec = [
                'can_id'            => $studentBac2sFromMinistry[$key]->can_id,
                'name_kh'           => $item->name_kh,
                'gender'            => $genders[$item->gender_id]->name_kh,
                'date_of_birth'     => $item->dob,
                'highSchool_name'   => $highSchools[$item->highschool_id]->name_kh,
                'origin'            => $origins[$item->pob]->name_en,
                'bac_math_grade'    => $item->bac_math_grade,
                'bac_phys_grade'    => $item->bac_phys_grade,
                'bac_chem_grade'    => $item->bac_chem_grade,
                'result'            => 'ជាប់',
                'rank'              => $count_pass,
                'total_score'       => $item->total_score
            ];
            $selectedStudents[] = $key;
//            unset($studentBac2sFromMinistry[$key]);// removed student that took entrance exam at itc (both pass and fail)
            return $rec;
        });

        $rec_reserve = $reservedCandidates->map(function ($item, $key) use(&$selectedStudents, &$studentBac2sFromMinistry, $genders, $origins, $highSchools, &$count_reserve) {
            $count_reserve++;
            $rec = [
                'can_id'            => $studentBac2sFromMinistry[$key]->can_id,
                'name_kh'           => $item->name_kh,
                'gender'            => $genders[$item->gender_id]->name_kh,
                'date_of_birth'     => $item->dob,
                'highSchool_name'   => $highSchools[$item->highschool_id]->name_kh,
                'origin'            => $origins[$item->pob]->name_en,
                'bac_math_grade'    => $item->bac_math_grade,
                'bac_phys_grade'    => $item->bac_phys_grade,
                'bac_chem_grade'    => $item->bac_chem_grade,
                'result'            => 'ជាប់បំរុង',
                'rank'              => $count_reserve,
                'total_score'       => $item->total_score
            ];
            $selectedStudents[] = $key;
            return $rec;
        });

        $rec_fail = $failCandidates->map(function ($item, $key) use(&$selectedStudents, &$studentBac2sFromMinistry, $genders, $origins, $highSchools, &$count_fail) {
            $count_fail++;
            $rec = [
                'can_id'            => $studentBac2sFromMinistry[$key]->can_id,
                'name_kh'           => $item->name_kh,
                'gender'            => $genders[$item->gender_id]->name_kh,
                'date_of_birth'     => $item->dob,
                'highSchool_name'   => $highSchools[$item->highschool_id]->name_kh,
                'origin'            => $origins[$item->pob]->name_en,
                'bac_math_grade'    => $item->bac_math_grade,
                'bac_phys_grade'    => $item->bac_phys_grade,
                'bac_chem_grade'    => $item->bac_chem_grade,
                'result'            => 'ធ្លាក់',
                'rank'              => $count_fail,
                'total_score'       => $item->total_score
            ];
            $selectedStudents[] = $key;
            return $rec;
        });

        $rec_reject = $rejectedCandidates->map(function ($item, $key) use(&$selectedStudents, &$studentBac2sFromMinistry, $genders, $origins, $highSchools, &$count_reject) {
            $count_reject++;
            $rec = [
                'can_id'            => $studentBac2sFromMinistry[$key]->can_id,
                'name_kh'           => $item->name_kh,
                'gender'            => $genders[$item->gender_id]->name_kh,
                'date_of_birth'     => $item->dob,
                'highSchool_name'   => $highSchools[$item->highschool_id]->name_kh,
                'origin'            => $origins[$item->pob]->name_en,
                'bac_math_grade'    => $item->bac_math_grade,
                'bac_phys_grade'    => $item->bac_phys_grade,
                'bac_chem_grade'    => $item->bac_chem_grade,
                'result'            => 'ធ្លាក់',
                'rank'              => $count_reject,
                'total_score'       => $item->total_score
            ];
            $selectedStudents[] = $key;
            return $rec;
        });

//        $cands = DB::table('candidates')
//            ->join('studentBac2s', 'studentBac2s.id', '=', 'candidates.studentBac2_id')
//            ->where('candidates.exam_id', $exam_id)
//            ->where('result', "Pass")
//            ->select(
//                'studentBac2s.can_id',
//                'candidates.result',
//                'candidates.total_score',
//                'candidates.can_id'
//            )
//            ->orderBy('candidates.total_score', 'DESC')
//            ->get();
//
//        $candidateByRank = [];
//        $index = 1;
//
//        $last = null;
//        $same_index = 0;
//        foreach ($cands as $cand) {
//            if ($last != null && ($cand->total_score == $last->total_score)) { // the last one and this one have the same score, so he must have the same range
//                $candidateByRank[$cand->can_id] = $index - 1;
//                $same_index++;
//            } else {
//                if ($same_index > 0) {
//                    $index = $index + $same_index;
//                    $same_index = 0;
//                }
//                $candidateByRank[$cand->can_id] = $index;
//                $index++;
//            }
//            $last = $cand;
//        }


        Excel::create('Student Result To Send to DHE', function ($excel) use ($rec_pass, $rec_reserve, $rec_fail, $rec_reject,$studentBac2sFromMinistry, $selectedStudents, $genders, $highSchools, $origins, $grades ) {

            // Set the title
            $excel->setTitle('លទ្ធផលសំរាប់បញ្ជូនទៅគ្រឹះស្ថានឧត្តមសិក្សា');

            // Chain the setters
            $excel->setCreator('Institute of Technology of Cambodia')
                ->setCompany('Institute of Technology of Cambodia');

            $excel->sheet('បញ្ជីនិស្សិត្រ', function ($sheet) use ($rec_pass, $rec_reserve, $rec_fail, $rec_reject,$studentBac2sFromMinistry, $selectedStudents, $genders, $highSchools, $origins, $grades ) {

                $header = array('ល.រ',"can_id", "គោត្តនាមនិងនាម", "ភេទ", "ថ្ងៃខែឆ្នាំកំណើត", "មកពីវិទ្យាល័យ", "ខេត្តក្រុង", "គណិតវិទ្យា", "រូបវិទ្យា", "គីមីវិទ្យា", "result", "ចំណាត់ថ្នាក់", "score");

                $sheet->rows(
                    array($header)
                );

                $index = 1;

                foreach ($studentBac2sFromMinistry as $key => $candidate) {
                    $row = [
                        $index,
                        $candidate->can_id,
                        $candidate->name_kh,
                        $genders[$candidate->gender_id]->code,
                        Carbon::createFromFormat('Y-m-d H:i:s', $candidate->dob)->format("d/m/Y"),
                        $highSchools[$candidate->highschool_id]->name_kh,
                        $origins[$candidate->pob]->name_kh,
                        isset($grades[$candidate->bac_math_grade])?$grades[$candidate->bac_math_grade]->name_en:"N/A",
                        isset($grades[$candidate->bac_phys_grade])?$grades[$candidate->bac_phys_grade]->name_en:"N/A",
                        isset($grades[$candidate->bac_chem_grade])?$grades[$candidate->bac_chem_grade]->name_en:"N/A",
                        "N/A",
                        "N/A",
                        "N/A"
                    ];

                    if(isset($rec_pass[$key])) {
                        $rec1 = $rec_pass[$key];
                        $row = [
                            $index,
                            $rec1['can_id'],
                            $rec1['name_kh'],
                            $rec1['gender'],
                            Carbon::createFromFormat('Y-m-d H:i:s', $rec1['date_of_birth'])->format("d/m/Y"),
                            $rec1['highSchool_name'],
                            $rec1['origin'],
                            isset($grades[$rec1['bac_math_grade']])?$grades[$rec1['bac_math_grade']]->name_en:"N/A",
                            isset( $grades[$rec1['bac_phys_grade']])?$grades[$rec1['bac_phys_grade']]->name_en:"N/A",
                            isset($grades[$rec1['bac_chem_grade']])?$grades[$rec1['bac_chem_grade']]->name_en:"N/A",
                            $rec1['result'],
                            $rec1['rank'],
                            $rec1['total_score']
                        ];
                    }
                    if (isset($rec_reserve[$key])){
                        $rec1 = $rec_reserve[$key];
                        $row = [
                            $index,
                            $rec1['can_id'],
                            $rec1['name_kh'],
                            $rec1['gender'],
                            Carbon::createFromFormat('Y-m-d H:i:s', $rec1['date_of_birth'])->format("d/m/Y"),
                            $rec1['highSchool_name'],
                            $rec1['origin'],
                            isset($grades[$rec1['bac_math_grade']])?$grades[$rec1['bac_math_grade']]->name_en:"N/A",
                            isset( $grades[$rec1['bac_phys_grade']])?$grades[$rec1['bac_phys_grade']]->name_en:"N/A",
                            isset($grades[$rec1['bac_chem_grade']])?$grades[$rec1['bac_chem_grade']]->name_en:"N/A",
                            $rec1['result'],
                            $rec1['rank'],
                            $rec1['total_score']
                        ];

                    }
                    if (isset($rec_fail[$key])){
                        $rec1 = $rec_fail[$key];
                        $row = [
                            $index,
                            $rec1['can_id'],
                            $rec1['name_kh'],
                            $rec1['gender'],
                            Carbon::createFromFormat('Y-m-d H:i:s', $rec1['date_of_birth'])->format("d/m/Y"),
                            $rec1['highSchool_name'],
                            $rec1['origin'],
                            isset($grades[$rec1['bac_math_grade']])?$grades[$rec1['bac_math_grade']]->name_en:"N/A",
                            isset( $grades[$rec1['bac_phys_grade']])?$grades[$rec1['bac_phys_grade']]->name_en:"N/A",
                            isset($grades[$rec1['bac_chem_grade']])?$grades[$rec1['bac_chem_grade']]->name_en:"N/A",
                            $rec1['result'],
                            $rec1['rank'],
                            $rec1['total_score']
                        ];
                    }
                    if (isset($rec_reject[$key])){
                        $rec1 = $rec_reject[$key];
                        $row = [
                            $index,
                            $rec1['can_id'],
                            $rec1['name_kh'],
                            $rec1['gender'],
                            Carbon::createFromFormat('Y-m-d H:i:s', $rec1['date_of_birth'])->format("d/m/Y"),
                            $rec1['highSchool_name'],
                            $rec1['origin'],
                            isset($grades[$rec1['bac_math_grade']])?$grades[$rec1['bac_math_grade']]->name_en:"N/A",
                            isset( $grades[$rec1['bac_phys_grade']])?$grades[$rec1['bac_phys_grade']]->name_en:"N/A",
                            isset($grades[$rec1['bac_chem_grade']])?$grades[$rec1['bac_chem_grade']]->name_en:"N/A",
                            $rec1['result'],
                            $rec1['rank'],
                            $rec1['total_score']
                        ];
                    }

                    $sheet->appendRow(
                        $row
                    );
                    $index++;
                }

                /*$sheet->mergeCells('A1:C1');
                $sheet->cells('A1:C'.count($rooms)+1, function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('middle');
                });
                $sheet->setBorder('A2:C'.(2+count($rooms)), 'thin');*/

            });

        })->export('xls');
    }

    public function export_candidate_ministry_list_v2($exam_id)
    {

    }

    public function export_candidate_result_list($exam_id)
    {

        $candidates = $this->getCandidateResult($exam_id);

        $fields = [
            'ល.រ',
            'លេខបង្កាន់ដៃ',
            'បនទ្ប់ប្រលង',
            'ឈ្មោះ ខ្មែរ',
            'ឈ្មោះ ឡាតាំង',
            'ភេទ',
            'ថ្ងៃខែរឆ្នាំកំនើត',
            "ខេត្តក្រុង"
        ];
        $title = 'បញ្ជីលទ្ទផលរបស់បេក្ខជន';
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        Excel::create('បញ្ជីលទ្ទផលបេក្ខជន', function ($excel) use ($candidates, $title, $alpha, $fields) {

            $excel->sheet("បញ្ជីលទ្ទផលបេក្ខជន", function ($sheet) use ($candidates, $title, $alpha, $fields) {

                $sheet->setOrientation('portrait');
                // Set top, right, bottom, left
                $sheet->setPageMargin(array(
                    0.25, 0.30, 0.25, 0.30
                ));

                // Set all margins
                $sheet->setPageMargin(0.25);

                $sheet->rows(
                    array($fields)
                );

                $last = null;
                $same_index = 0;
                $index = 1;
                $order = 1;
                $passIndex = 1;
                $reserveIndex = 1;

                foreach ($candidates as $candidate) {

                    // This to find candidate's rank
                    if ($last != null && ($candidate->total_score == $last->total_score)) { // the last one and this one have the same score, so he must have the same range
                        $rank = $index - 1;
                        $same_index++;
                    } else {
                        if ($same_index > 0) {
                            $index = $index + $same_index;
                            $same_index = 0;
                        }
                        $rank = $index;
                        $index++;
                    }
                    $last = $candidate;

                    if ($candidate->result == "Pass") {
                        $result = "A" . $passIndex;
                        $passIndex++;
                    } else if ($candidate->result == "Reserve") {
                        $result = "R" . $reserveIndex;
                        $reserveIndex++;
                    } else if ($candidate->result == "Reject") {
                        $result = "AB";
                    } else {
                        $result = "";
                    }
                    $row = array(
                        $order,
                        $candidate->room_name,
                        $candidate->register_id,
                        $candidate->name_kh,
                        $candidate->name_latin,
                        $candidate->gender,
                        Carbon::createFromFormat('Y-m-d H:i:s', $candidate->dob)->format("d/m/Y"),
                        $candidate->highschool,
                        $candidate->origin,
                        $candidate->bac_year,
                        $candidate->math_grade,
                        $candidate->phys_grade,
                        $candidate->chem_grade,
                        $candidate->grade,
                        $candidate->percentile,
                        $candidate->can_id,
                        $candidate->total_score,
                        $result,
                        $rank
                    );

                    $sheet->appendRow(
                        $row
                    );
                    $order++;
                }
            });


        })->export('xls');

    }

    public function export_candidate_result_detail($exam_id)
    {

        $allCandidates = [];
        $exam_rooms = ExamRoom::where('exam_id', $exam_id)
            ->get();

        foreach ($exam_rooms as $exam_room) {
            $candidates = DB::table('candidates')
                ->where('room_id', $exam_room->id)
                ->where('candidates.exam_id', $exam_id)
                ->orderBy('register_id', 'ASC')
                ->get();

            $CandidatesByRoom = [];
            $allCourses = DB::table('entranceExamCourses')
                ->where('entranceExamCourses.exam_id', '=', $exam_id)
                ->select('id', 'name_kh', 'total_question')
                ->orderBy('id')
                ->get();

            foreach ($allCourses as $allCourse) {

                $total_question = $allCourse->total_question;
                $candidateResults = [];

                $query = DB::select(
                    "select roomcode, order_in_room,course_id,exam_id,score_c,score_w,score_na, count(*), (score_c+score_w+score_na) as total" .
                    " from secret_room_score" .
                    " where exam_id =" . $exam_id .
                    " and course_id =" . $allCourse->id .
                    "and roomcode=" . Crypt::decrypt($exam_room->roomcode) .
                    " group by roomcode,order_in_room,course_id,exam_id,score_c,score_w,score_na" .
                    " HAVING count(*) > 1" .
                    " order by roomcode, order_in_room, course_id;"
                );

                foreach ($query as $result) {
                    if ($result->total == $total_question || $result->total == 0) { // This one is correct, just double check
//                    $score = (($result->score_c * (int)$course['correct']) - ($result->score_w * (int)$course['wrong']))*(int)$course['coe'];
                        $candidateResults[$result->order_in_room] = (['course_id' => $result->course_id, 'score_c' => $result->score_c, 'score_w' => $result->score_w, 'score_na' => $result->score_na]);

                    }
                }
                if ($candidateResults) {

                    $index = 1;
                    if ($index <= count($candidates)) {

                        foreach ($candidates as $candidate) {
                            if (isset($candidateResults[$index])) {

                                $element = array(
                                    'score_c' => $candidateResults[$index]['score_c'],
                                    'score_w' => $candidateResults[$index]['score_w'],
                                    'score_na' => $candidateResults[$index]['score_na'],
                                );
                                $CandidatesByRoom[$candidate->register_id][$allCourse->name_kh] = $element;
                            }
                            $index++;
                        }
                    }
                }

            }
            $allCandidates = $allCandidates + $CandidatesByRoom;
        }

        $exam = Exam::where('id', $exam_id)->first();
        $candsRegister = DB::table('candidatesFromMoeys')
            ->join("studentBac2s", "studentBac2s.can_id", '=', "candidatesFromMoeys.can_id")
            ->join("candidates", "candidates.studentBac2_id", '=', "studentBac2s.id")
            ->where('candidatesFromMoeys.bac_year', $exam->academic_year_id - 1)
            ->where('candidates.exam_id', $exam_id)
            ->lists('candidatesFromMoeys.can_id');

        $candidates = DB::table('candidates')
            ->leftJoin('studentBac2s', 'studentBac2s.id', '=', 'candidates.studentBac2_id')
            ->leftJoin('genders', 'candidates.gender_id', '=', 'genders.id')
            ->leftJoin('highSchools', 'candidates.highschool_id', '=', 'highSchools.id')
            ->join('origins', 'candidates.province_id', '=', 'origins.id')
            ->join('examRooms', 'examRooms.id', '=', 'candidates.room_id')
            ->leftJoin('buildings', 'examRooms.building_id', '=', 'buildings.id')
            ->leftJoin('gdeGrades as math', 'candidates.bac_math_grade', '=', 'math.id')
            ->leftJoin('gdeGrades as phys', 'candidates.bac_phys_grade', '=', 'phys.id')
            ->leftJoin('gdeGrades as chem', 'candidates.bac_chem_grade', '=', 'chem.id')
            ->leftJoin('gdeGrades as grade', 'candidates.bac_total_grade', '=', 'grade.id')
            ->where('candidates.exam_id', $exam_id)
            ->select(
                DB::raw('CONCAT(buildings.code,"examRooms".name) as room_name'),
                'candidates.register_id',
                'candidates.name_kh',
                'studentBac2s.can_id',
                'candidates.bac_percentile as percentile',
                'candidates.name_latin',
                'genders.code as gender',
                'candidates.dob',
                'highSchools.name_kh as highschool',
                'origins.name_kh as origin',
                'candidates.bac_year',
                'candidates.register_from',
                'math.name_en as math_grade',
                'phys.name_en as phys_grade',
                'chem.name_en as chem_grade',
                'grade.name_en as grade',
                'candidates.result',
                'candidates.total_score',
                'examRooms.roomcode'
            )
            ->orderBy('candidates.total_score', "DESC")
            ->get();

        $fields = [
            'ល.រ',
            'បនទ្ប់',
            'លេខបន្ទប់សំងាត់',
            'លេខបង្កាន់ដៃ',
            'ឈ្មោះ ខ្មែរ',
            'ឈ្មោះ ឡាតាំង',
            'ភេទ',
            'ថ្ងៃខែរឆ្នាំកំនើត',
            "វិទ្យាល័យ",
            "ខេត្តក្រុង",
            "Register_from",
            "ឆ្នាំជាប់បាក់ឌុប",
            "និទ្ទេសគណិតវិទ្យា",
            "និទ្ទេសរូបវិទ្យា",
            "និទ្ទេសគីមីវិទ្យា",
            "និទ្ទេសសរុប",
            "Percentile",
            "can ID",
            "ពិន្ទុ",
            "លទ្ធផល",
            "ចំណាត់ថ្នាក់",
            "math_c",
            "math_w",
            "math_na",
            "phy_chem_c",
            "phy_chem_w",
            "phy_chem_na",
            "logic_c",
            "logic_w",
            "logic_na"

        ];
        $title = 'បញ្ជីលទ្ទផលរបស់បេក្ខជន';
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        Excel::create('បញ្ជីលទ្ទផលបេក្ខជន', function ($excel) use ($candsRegister, $allCandidates, $allCourses, $candidates, $title, $alpha, $fields) {

            $excel->sheet("បញ្ជីលទ្ទផលបេក្ខជន", function ($sheet) use ($candsRegister, $allCandidates, $allCourses, $candidates, $title, $alpha, $fields) {

                $sheet->setOrientation('portrait');
                // Set top, right, bottom, left
                $sheet->setPageMargin(array(
                    0.25, 0.30, 0.25, 0.30
                ));

                // Set all margins
                $sheet->setPageMargin(0.25);

                $sheet->rows(
                    array($fields)
                );

                $last = null;
                $same_index = 0;
                $index = 1;
                $order = 1;
                $passIndex = 1;
                $reserveIndex = 1;

                foreach ($candidates as $candidate) {
                    $candScoreProperties = [];

                    // This to find candidate's rank
                    if ($last != null && ($candidate->total_score == $last->total_score)) { // the last one and this one have the same score, so he must have the same range
                        $rank = $index - 1;
                        $same_index++;
                    } else {
                        if ($same_index > 0) {
                            $index = $index + $same_index;
                            $same_index = 0;
                        }
                        $rank = $index;
                        $index++;
                    }
                    $last = $candidate;

                    if ($candidate->result == "Pass") {
                        $result = "A" . $passIndex;
                        $passIndex++;
                    } else if ($candidate->result == "Reserve") {
                        $result = "R" . $reserveIndex;
                        $reserveIndex++;
                    } else if ($candidate->result == "Reject") {
                        $result = "AB";
                    } else {
                        $result = "";
                    }

                    $row = array(
                        $order,
                        $candidate->room_name,
                        crypt::decrypt($candidate->roomcode),
                        $candidate->register_id,
                        $candidate->name_kh,
                        $candidate->name_latin,
                        $candidate->gender,
                        Carbon::createFromFormat('Y-m-d H:i:s', $candidate->dob)->format("d/m/Y"),
                        $candidate->highschool,
                        $candidate->origin,
                        $candidate->register_from,
                        $candidate->bac_year,
                        $candidate->math_grade,
                        $candidate->phys_grade,
                        $candidate->chem_grade,
                        $candidate->grade,
                        $candidate->percentile,
                        $candidate->can_id,
                        $candidate->total_score,
                        $result,
                        $rank
                    );

                    foreach ($allCourses as $allCourse) {
                        $elements = array(
                            $allCandidates[$candidate->register_id][$allCourse->name_kh]['score_c'],
                            $allCandidates[$candidate->register_id][$allCourse->name_kh]['score_w'],
                            $allCandidates[$candidate->register_id][$allCourse->name_kh]['score_na'],
                        );

                        $row = array_merge($row, $elements);
                    }

                    $sheet->appendRow(
                        $row
                    );
                    $order++;
                }
            });
        })->export('xls');

    }

    public function printCandidateErrorScore($examId, Request $request)
    {

        $errors = $this->exams->getErrorScore($examId, $request->get('course_id'));
        $academic_year = Exam::find($examId)->first()->academic_year_id + 1;
        $courseName = $request->get('course_name');

        return view('backend.exam.print.candidate_score_error', compact('errors', 'courseName', 'academic_year'));
    }

    public function generate_room(GenerateRoomExamRequest $request, $exam_id)
    { // In candidate section

        $exam = $this->exams->findOrThrowException($exam_id);
        $candidates = $exam->candidates()->where('active', true)->orderBy('register_id')->get()->toArray();
        $rooms = $exam->rooms()->orderBy('building_id')->orderBy('name')->get()->toArray();

        shuffle($rooms);
        $available_seat = 0;
        foreach ($rooms as &$room) {
            $room['current_seat'] = 0;
            $available_seat = $available_seat + $room['nb_chair_exam'];
        }

        if (count($candidates) > $available_seat) {
            return Response::json(array('status' => 'false', 'message' => 'There is not enough seat for candidate!'));
        }

        $current_room = 0;
        foreach ($candidates as &$candidate) {
            $this->update_room_candidate($rooms, $current_room, $candidate);
        }

        foreach ($candidates as $can) {
            DB::table('candidates')
                ->where('id', $can['id'])
                ->update(['room_id' => $can['room_id']]);
        }

        return Response::json(array('status' => 'true', 'message' => 'Operation is successful!'));
    }

    function update_room_candidate(&$rooms, &$current_room, &$candidate)
    {
        if ($rooms[$current_room]['current_seat'] < $rooms[$current_room]['nb_chair_exam']) {
            $candidate['room_id'] = $rooms[$current_room]['id'];
            $rooms[$current_room]['current_seat']++;
            $current_room++;
            if ($current_room >= count($rooms)) $current_room = 0;
            return true;
        } else {
            $current_room++;
            if ($current_room >= count($rooms)) $current_room = 0; // Reset index to 0 if over max
            $this->update_room_candidate($rooms, $current_room, $candidate);
        }
    }

    public function check_missing_candidates($exam_id)
    {
        $candidate_register_ids = Candidate::where('exam_id', $exam_id)->orderBy('register_id', 'ASC')->lists('register_id')->toArray();
        if (count($candidate_register_ids) > 0) {
            $missing = array_diff(range(1, max($candidate_register_ids)), $candidate_register_ids);

            if (count($missing) > 0) {
                return Response::json(array('status' => true)); // There are some missing
            } else {
                return Response::json(array('status' => false));
            }
        } else {
            return Response::json(array('status' => false)); // Nothing is missing
        }
    }

    public function find_missing_candidates($exam_id)
    {
        $candidate_register_ids = Candidate::where('exam_id', $exam_id)->orderBy('register_id', 'ASC')->lists('register_id')->toArray();
        $missing = array_diff(range(1, max($candidate_register_ids)), $candidate_register_ids);

        return view('backend.exam.includes.popup_view_missing_candidate', compact('missing'));
    }

    public function formGenerateScores($examId)
    {

        $departments = $this->getAllDepartments();

        return view('backend.exam.includes.form_generate_DUT_score', compact('examId', 'departments'));
    }

    public function generateCandidateDUTResultTest($examId, Request $request)
    {


        $arrayCandidateInEachDept = $request->number_candidate;
        $test = [];
        $count = 0;
        //$totalCands = $this->isAvalaibleDept($arrayCandidateInEachDept, null, null, $findsum = 'true');

        $dUTCandidates = $this->getAllDUTCandidates($examId); // List of all canidate order by bac percentile

        if ($dUTCandidates) {

            $this->resetCandidateDUTResult();// reset all first then make an update

            foreach ($dUTCandidates as $dUTCandidate) {

                $count++;

                //if($count <= $totalCands) {
                $statusRank = 1;
                $candidateDepts = $this->getCandidateDept($dUTCandidate->candidate_id); // List of all chosen department order by rank
                foreach ($candidateDepts as $candidateDept) {// loop candidate department option from the 1 choice to the end 1->8

                    //if($candidateDept->rank == $statusRank) {

                    foreach ($arrayCandidateInEachDept as $index => $value) { // index: ID of department, value: Number of success student

                        if ((int)$candidateDept->department_id == (int)$index) {

                            $numberStudent = (int)$value;

                            if ($numberStudent > 0) {
//                                        $test[] = array('index='.$index, 'value='.$value, 'dept='.$candidateDept->department_id, 'rank='.$statusRank, 'cand_id='.$dUTCandidate->candidate_id);

                                $numberStudent = $numberStudent - 1;
                                $arrayCandidateInEachDept[$index] = $numberStudent;

                                $update = $this->updateCandiateDepartment($dUTCandidate->candidate_id, $candidateDept->department_id, $candidateDept->rank, $result = 'Pass');

                                if ($update) {
                                    $candResult = $this->updateDutCandResult($examId, $dUTCandidate->candidate_id, $candRes = 'Pass');
                                    break;
                                }
                            } else {
                                $statusRank++;
                            }
                        }
                    }
                    // } else {
                    //    break;
                    //}
                }
//                } else {
//                    $candResult = $this->updateDutCandResult($examId, $dUTCandidate->candidate_id, $candRes = 'Reserve');
//                }
            }


            //return view list of candidate who pass with selected department and student whow reserve with selected department options

            return Response::json(['status' => true]);
        }

        return Response::json(['status' => false]);

    }

    public function generateCandidateDUTResult($examId, GenerateExamScoreDUTRequest $request) {

        $DeptSelectedForStu =[];

        $arrayCandidateInEachDept = $request->department; // Array from form department

        //$totalCands = $this->isAvalaibleDept($arrayCandidateInEachDept, null, null, $findsum = 'true');

        $dUTCandidates = $this->getAllDUTCandidates($examId); // List of all canidate order by bac percentile

        if($dUTCandidates) {

            $this->resetCandidateDUTResult();// reset all first then make an update

            foreach($dUTCandidates as $dUTCandidate) { // loop by each candidate order by percentile

                //$statusRank =1;
                $candidateDepts = $this->getCandidateDept($dUTCandidate->candidate_id); // List of all chosen department order by rank

                $index = 1;
                $reserve_ready = false;
                foreach($candidateDepts as $candidateDept) {// loop candidate department option from the 1 choice to the end 1->8

                    // Candidate ID : $dutCandidate->candidate_id
                    // Sequence of department chosen by current candidate : $candidateDepts
                    $index++;

                    if($arrayCandidateInEachDept[$candidateDept->department_id]['success'] > 0){
                        // update candidate_department.status = true

                        $DeptSelectedForStu[] =$candidateDept->department_id;
                        $arrayCandidateInEachDept[$candidateDept->department_id]['success'] --;

                        $update = $this-> updateCandiateDepartment($dUTCandidate->candidate_id, $candidateDept->department_id,$candidateDept->rank, $result='Pass');

                        if($update) {
                            $candResult = $this->updateDutCandResult($examId, $dUTCandidate->candidate_id, $candRes = 'Pass');
                            break;
                        }
                    } else if(!$reserve_ready) {

                        if($arrayCandidateInEachDept[$candidateDept->department_id]['reserve'] > 0){
                            $arrayCandidateInEachDept[$candidateDept->department_id]['reserve'] --;
                            // sdfsfasfdsdfafd
                            $update = $this-> updateCandiateDepartment($dUTCandidate->candidate_id, $candidateDept->department_id,$candidateDept->rank, $result='Reserve');

                            $reserve_ready = true;
                        }
                    }

                    if($index == count($candidateDepts)){
                        if($reserve_ready){
                            $candResult = $this->updateDutCandResult($examId, $dUTCandidate->candidate_id, $candRes = 'Reserve');
                        } else {
                            $candResult = $this->updateDutCandResult($examId, $dUTCandidate->candidate_id, $candRes = 'Fail');
                        }
                    }
                }
            }

            //return view list of candidate who pass with selected department and student whow reserve with selected department options

            return Response::json(['status'=>true]);
        }

        return Response::json(['status'=>false]);
    }

    public function getDUTCandidateResultLists($examId)
    {

        return view('backend.exam.includes.examination_DUT_candidate_result', compact('examId'));
    }

    public function getDUTCandidateResultListTypes($examId, Request $request)
    {


        $resultType = $request->type;

        if ($resultType == 'Pass') {
            $title = 'Successfully Passed';
            $allStudentByDept = [];
            $candidateDUTs = $this->getSucceedCandidateDUTFromDB($examId, $is_success = 'Pass');

            return view('backend.exam.includes.patial_result_candidate_dut', compact('allStudentByDept', 'candidateDUTs', 'examId', 'title'));

        } else if ($resultType == 'Reserve') {
            $title = 'Reserve';
            $allStudentByDept = [];
            $candidateDUTs = $this->getSucceedCandidateDUTFromDB($examId, $is_success = 'Reserve');
            return view('backend.exam.includes.patial_result_candidate_dut', compact('allStudentByDept', 'candidateDUTs', 'examId', 'title'));


        } else if ($resultType == 'pass_by_dept') {
            $candidateDUTs = [];
            $studentByDept = $this->arrayStudentPassOrReserveByDept($examId, $is_success = 'Pass');
            $allStudentByDept = $studentByDept[1];

            return view('backend.exam.includes.patial_result_candidate_dut', compact('allStudentByDept', 'candidateDUTs', 'examId'));

        } else {
            $candidateDUTs = [];

            $studentByDept = $this->arrayStudentPassOrReserveByDept($examId, $is_success = 'Reserve');
            $allStudentByDept = $studentByDept[1];

            return view('backend.exam.includes.patial_result_candidate_dut', compact('allStudentByDept', 'candidateDUTs', 'examId'));
        }
    }
    private function getAllDUTCandidates($examId) {

        $dUTCandidates = DB::table('candidates')
            ->select('candidates.id as candidate_id', 'candidates.bac_percentile', 'candidates.name_latin')
            ->where('candidates.exam_id', '=', $examId)
            ->orderBy('bac_percentile', 'DESC')
            ->get();

        return $dUTCandidates;
    }

    private function resetCandidateDUTResult() { // set the field is_success to false

        $res = DB::table('candidate_department')

            ->update(array(
                'is_success' => null
            ));
    }

    private function getCandidateDept($candidateId) {

        $candidateDept = DB::table('candidate_department')
            ->where('candidate_department.candidate_id', '=', $candidateId)
            ->select('candidate_department.department_id', 'candidate_department.rank')
            ->orderBy('rank', 'ASC')
            ->get();

        return $candidateDept;
    }

    private function updateCandiateDepartment($candidate_id, $department_id, $rank, $result) {

        $res = DB::table('candidate_department')
            ->where([
                ['candidate_id', '=', $candidate_id],
                ['department_id', '=', $department_id],
                ['rank', '=', $rank ]
            ])
            ->update(array(
                'is_success' => $result
            ));
        return $res;
    }

    private function updateDutCandResult($examId, $candidateId, $canResult) {
        $res = DB::table('candidates')
            ->where([
                ['id', '=', $candidateId],
                ['exam_id', '=', $examId ]
            ])
            ->update(array(
                'result' => $canResult
            ));
        return $res;
    }

    private function getSucceedCandidateDUTFromDB($examId, $is_success)
    {

        if ($is_success != null) {
            $dUTCandidates = DB::table('candidates')
                ->join('candidate_department', 'candidates.id', '=', 'candidate_department.candidate_id')
                ->join('departments', 'departments.id', '=', 'candidate_department.department_id')
                ->join('genders', 'genders.id', '=', 'candidates.gender_id')
                ->join('academicYears', 'academicYears.id', '=', 'candidates.academic_year_id')
                ->join('origins', 'origins.id', '=', 'candidates.province_id')
                ->where([
                    ['candidate_department.is_success', '=', $is_success],
                    ['candidates.exam_id', '=', $examId],
                    ['origins.is_province', '=', true],
                    ['origins.active', '=', true]
                ])
                ->select('origins.name_kh as province_name', 'academicYears.name_kh as academic_year', 'candidates.register_id', 'candidates.dob as birth_date', 'candidates.register_from as home_town', 'genders.name_kh as gender', 'candidates.id as candidate_id', 'candidates.name_kh', 'candidates.name_latin', 'candidate_department.is_success', 'candidate_department.rank', 'departments.code as department_name', 'departments.id as department_id', 'candidates.bac_percentile')
                ->orderBy('register_id', 'ASC')
                ->get();

            return $dUTCandidates;

        } else {

            $dUTCandidates = DB::table('candidates')
                ->join('candidate_department', 'candidates.id', '=', 'candidate_department.candidate_id')
                ->join('departments', 'departments.id', '=', 'candidate_department.department_id')
                ->join('genders', 'genders.id', '=', 'candidates.gender_id')
                ->join('academicYears', 'academicYears.id', '=', 'candidates.academic_year_id')
                ->join('origins', 'origins.id', '=', 'candidates.province_id')
                ->where([
                    ['candidates.exam_id', '=', $examId],
                    ['candidates.result', '=', 'Fail'],
                    ['origins.is_province', '=', true],
                    ['origins.active', '=', true]
                ])
                ->select('origins.name_kh as province_name', 'academicYears.name_kh as academic_year', 'candidates.register_id', 'candidates.dob as birth_date', 'candidates.register_from as home_town', 'genders.name_kh as gender', 'candidates.id as candidate_id', 'candidates.name_kh', 'candidates.name_latin', 'candidate_department.is_success', 'candidate_department.rank', 'departments.code as department_name', 'departments.id as department_id', 'candidates.bac_percentile')
                ->orderBy('register_id', 'ASC')
                ->get();

            return $dUTCandidates;
        }

    }

    public function printCandidateDUTResult($examId, Request $request)
    {

        $resultType = $request->status;

        if ($resultType == 'Pass') {
            $title = "បញ្ជីបេក្ខជន<span style='font-size: 150%'>ជាប់ស្ថាពរ</span>";
            $allStudentByDept = [];
            $candidateDUTs = $this->getSucceedCandidateDUTFromDB($examId, $is_success = 'Pass');
            //$candidateDUTs = array_chunk($candidateDUTs, 27);
            return view('backend.exam.print.print_examination_DUT_candidate_result', compact('allStudentByDept', 'candidateDUTs', 'title'));

        } else if ($resultType == 'Reserve') {
            $title = "បញ្ជីបេក្ខជន<span style='font-size: 150%'>ជាប់បំរុង</span>";
            $allStudentByDept = [];
            $candidateDUTs = $this->getSucceedCandidateDUTFromDB($examId, $is_success = 'Reserve');
            //$candidateDUTs = array_chunk($candidateDUTs, 27);
            return view('backend.exam.print.print_examination_DUT_candidate_result', compact('allStudentByDept', 'candidateDUTs', 'title'));


        } else if ($resultType == 'pass_by_dept') {

            $title = 'បញ្ជីបេក្ខជនជាប់ស្ថាពរ';
//            $allDepts = $this->getAllDepartments();
            $candidateDUTs = [];
            $studentByDept = $this->arrayStudentPassOrReserveByDept($examId, $is_success = 'Pass');
            $allDepts = $studentByDept[0];
            $allStudentByDept = $studentByDept[1];

            return view('backend.exam.print.print_examination_DUT_candidate_result', compact('allStudentByDept', 'candidateDUTs', 'title', 'allDepts'));

        } else {
            $title = 'បញ្ជីបេក្ខជនជាប់បំរុង';
            $candidateDUTs = [];
            $studentByDept = $this->arrayStudentPassOrReserveByDept($examId, $is_success = 'Reserve');
            $allDepts = $studentByDept[0];
            $allStudentByDept = $studentByDept[1];
            return view('backend.exam.print.print_examination_DUT_candidate_result', compact('allStudentByDept', 'candidateDUTs', 'title', 'allDepts'));
        }
    }

    public function exportData($examId)
    {

        dd('Test to export Data');
//        dd($examId);


        $exam = $this->exams->findOrThrowException($examId);

//        $candidate = DB::table('candidates')
//            ->where('exam_id', $examId)
//            ->lists('id');

//        $students = DB::table('students')
//            ->join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
//            ->whereNotIn('academic_year_id', [2017])
//            ->select('name_kh', 'name_latin')
//            ->distinct('name_latin')
//            ->orderBy('name_latin', 'ASC')
//            ->get();

        $studentBac2s = DB::table('studentBac2s')
            ->select('name_kh', 'mother_name', 'father_name')
            ->orderBy('name_kh', 'ASC')
            ->get();

        foreach ($studentBac2s as $student) {

            $el = array(
                'Name_khmer' => $student->name_kh,
                'Mother_name' => $student->mother_name,
                'Father_name' => $student->father_name
            );

            $data[] = $el;

        }
//        $fields= ['លេខបង្កាន់ដៃ', 'បន្ទប់', 'ឈ្មោះ ខ្មែរ', 'ឈ្មោះ ឡាតាំង', 'ភេទ', 'ថ្ងៃខែរឆ្នាំកំនើត', 'ហត្ថលេខា'];

        $fields = ['Name_Khmer', 'Mother_name', 'Father_name'];
        $title = 'Student High School';
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        Excel::create('Student High School Data', function ($excel) use ($data, $title, $alpha, $fields) {

            $excel->sheet($title, function ($sheet) use ($data, $title, $alpha, $fields) {
                $sheet->fromArray($data);
            });

        })->export('xls');
    }

    public function getFormDistributionEngineerFirstYear($examId)
    {
        $departments = $this->getAllDepartments();
        return view('backend.exam.includes.form-distribution-engineer-first-year', compact('examId', 'departments'));
    }

    public function generateDistributionEngineerFirstYear(Request $request)
    {
        $this->validate($request, [
            'exam_id' => 'required',
            'department' => 'required|array'
        ]);
        try {
            $nbDeptNeed = $request->department;
            $exam = Exam::where('id', $request->get('exam_id'))->first();
            //$candidateIds = Candidate::join('candidate_department', 'candidate_department.candidate_id', '=', 'candidates.id')
            //    ->where('candidates.exam_id', $request->exam_id)
            //    ->distinct('candidates.id')
            //    ->pluck('candidate_department.candidate_id');
            $redoubleStudents = DB::table('redouble_student')
                ->where('academic_year_id',$exam->academic_year_id)
                ->pluck('student_id');
            $candidateIds = Student::join('studentAnnuals', 'studentAnnuals.student_id', '=','students.id')
                //sa.academic_year_id = 2019 and sa.grade_id = 1 and degree_id = 1;
                ->where('studentAnnuals.academic_year_id',$exam->academic_year_id)
                ->whereNotIn('studentAnnuals.student_id', $redoubleStudents)
                ->where('studentAnnuals.grade_id', 1)
                ->where('studentAnnuals.degree_id',1)
                ->pluck('students.can_id');

            $candidates = Candidate::whereIn('id', $candidateIds)
                ->orderBy('total_score', 'desc')
                ->select('id', 'total_score')
                ->get()->toArray();

            CandidateDepartment::whereIn('candidate_id', $candidateIds)
                ->update(['is_success' => null]);

            if (count($candidates) > 0) {
                $prevCandidate = null;
                $prevCandidateDeptIdPass = null;
                foreach ($candidates as $candidate) {
                    $deptChooses = CandidateDepartment::where('candidate_id', $candidate['id'])
                        ->orderBy('rank', 'asc')
                        ->get();
                    $deptChooses = $deptChooses->groupBy('department_id')->toArray();

                    foreach ($deptChooses as $deptChoose) {

                        if($nbDeptNeed[$deptChoose[0]['department_id']]['success'] > 0) {
                            $candidateDepartment = CandidateDepartment::find($deptChoose[0]['id']);
                            if ($candidateDepartment instanceof CandidateDepartment) {
                                $candidateDepartment->is_success = 'Pass';
                                $candidateDepartment->update();
                                $prevCandidate = $candidate;
                                $prevCandidateDeptIdPass = $deptChoose[0]['department_id'];
                                $nbDeptNeed[$deptChoose[0]['department_id']]['success']--;
                                break;
                            }
                        }else {
                            if ($prevCandidate !== null) {
                                if (($prevCandidate['total_score'] == $candidate['total_score']) && ($prevCandidateDeptIdPass == $deptChoose[0]['department_id'])) {
                                    $candidateDepartment = CandidateDepartment::find($deptChoose[0]['id']);
                                    if ($candidateDepartment instanceof CandidateDepartment) {
                                        $candidateDepartment->is_success = 'Pass';
                                        $candidateDepartment->update();
                                        $prevCandidate = $candidate;
                                        $prevCandidateDeptIdPass = $deptChoose[0]['department_id'];
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return redirect()->back();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    /**
     * Find
     * @param $scores
     */
    private function find_correct_score($scores)
    {

    }

    private function getRoomsFromDB($exam_id)
    {

        $roomFromDB = DB::table('examRooms')
            ->select('examRooms.roomcode as room_code', 'examRooms.id as room_id')
            ->where('exam_id', $exam_id)
            ->WhereNotNull('examRooms.roomcode')
            ->get();

        return $roomFromDB;

    }

    private function sortRoomCodes($a, $b)
    {
        return $a->room_code - $b->room_code;
    }

    private function sortCandidateRank($a, $b)
    {
        return $b->total_score - $a->total_score;
    }

    private function updateCandidateResultScore($candidateId, $totalScore, $status)
    {

        $candidate = Candidate::where('id', $candidateId)->get();

        $updateCandidateScore = DB::table('candidates')
            ->where([
                ['id', '=', $candidateId],
                ['candidates.active', '=', true]
            ])
            ->update(array(
                'total_score' => $totalScore,
                'result' => $status,
            ));

        if ($updateCandidateScore) {
            //UserLog
            $this->exams->getUserLog($candidate, $model = 'Candidate', $action = 'Update');
        }

        return $updateCandidateScore;
    }

    private function isAvalaibleDept($arrayNumberOfCandInEachDept, $deptId, $studentRate, $findsum)
    {

        $totalSelectionCands = 0;
        if ($findsum != null) { // calculation of the total selection of number of student
            foreach ($arrayNumberOfCandInEachDept as $key => $value) {
                $totalSelectionCands = $totalSelectionCands + (int)$value;
            }
            return $totalSelectionCands;
        }

    }
}
