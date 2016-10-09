<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Exam\CreateExamRequest;
use App\Http\Requests\Backend\Exam\DeleteExamRequest;
use App\Http\Requests\Backend\Exam\EditExamRequest;
use App\Http\Requests\Backend\Exam\StoreExamRequest;
use App\Http\Requests\Backend\Exam\ViewExamRequest;
use App\Http\Requests\Backend\Exam\UpdateExamRequest;
use App\Models\AcademicYear;
use App\Models\Building;
use App\Models\Candidate;
use App\Models\Department;
use App\Models\EntranceExamCourse;
use App\Models\Exam;
use App\Models\ExamRoom;
use App\Models\ExamType;
use App\Models\Origin;
use App\Models\Room;
use App\Models\StudentBac2;
use App\Repositories\Backend\Exam\ExamRepositoryContract;
use App\Repositories\Backend\TempEmployeeExam\TempEmployeeExamRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\Backend\Exam\ViewSecretCodeRequest;
use App\Http\Requests\Backend\EntranceExamCourse\CreateEntranceExamCourseRequest;
use App\Http\Requests\Backend\Exam\ModifyExamRoomRequest;
use App\Http\Requests\Backend\Candidate\GenerateRoomExamRequest;
use App\Http\Requests\Backend\Exam\DownloadExaminationDocumentsRequest;
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
        return view('backend.exam.index',compact('type'));
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
        $last_academic_year_id =AcademicYear::orderBy('id','desc')->first()->id;
        $academicYear = AcademicYear::where('id',$last_academic_year_id)->orderBy('id')->lists('name_kh','id');
        $examType = ExamType::where('id',$id)->lists('name_kh','id')->toArray();
        $type = $id;
        return view('backend.exam.create',compact('examType','type','academicYear'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreExamRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreExamRequest $request)
    {
       
        $id = $this->exams->create($request->all());
        return redirect()->route('admin.exam.show',[$request->get('type_id'),$id])->withFlashSuccess(trans('alerts.backend.generals.created'));

    }

    /**
     * Display the specified resource.
     *
     * @param ViewExamRequest $request
     * @param int $type_id
     * @param  int  $exam_id
     * @return \Illuminate\Http\Response
     */

    public function show(ViewExamRequest $request, $type_id, $exam_id)
    {
        $exam = $this->exams->findOrThrowException($exam_id);
        $type = $exam->type->id;
        $academicYear = AcademicYear::where('id',$exam->academicYear->id)->lists('name_kh','id');
        $examType = ExamType::where('id',$type)->lists('name_kh','id')->toArray();
        $usable_room_exam = Room::where('is_exam_room',true)->count();
        $exam_rooms = $exam->rooms()->with(['building','candidates'])->orderBy('building_id')->orderBy('name')->get();
        $buildings = Building::lists('name','id');

        // For candidate filtering
        $exams = Exam::where('type_id',$type)->lists('name','id');
        $origins = Origin::lists('name_kh','id');
        $rooms = ExamRoom::where('exam_id',$exam_id)
            ->join('buildings',"examRooms.building_id",'=','buildings.id')
            ->select(
                DB::raw('CONCAT(buildings.code,"examRooms".name) as room_name'),'examRooms.id'
            )
            ->orderBy('room_name','ASC')
            ->lists('room_name', 'id');

        $roles = $this->employeeExams->getRoles();

        foreach($roles as $role) {}
        return view('backend.exam.show',compact('rooms','exam','type','academicYear','examType', 'roles','usable_room_exam','exam_rooms','buildings','exams','origins'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditExamRequest $request
     * @param  int  $type_id
     * @param int $exam_id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditExamRequest $request, $type_id, $exam_id)
    {

        $exam = $this->exams->findOrThrowException($exam_id);

        $type = $type_id;
        $academicYear = AcademicYear::where('id',$exam->academicYear->id)->lists('name_kh','id');
        $examType = ExamType::where('id',$type)->lists('name_kh','id')->toArray();

        return view('backend.exam.edit',compact('exam','type','academicYear','examType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateExamRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateExamRequest $request, $id)
    {
        $this->exams->update($id, $request->all());
        return redirect()->route('admin.exam.index',$request['type_id'])->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteExamRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteExamRequest $request, $id)
    {
        $this->exams->destroy($id);
    }

    public function data(ViewExamRequest $request, $id)
    {
        $exams = DB::table('exams')
            ->where('type_id',$id)
            ->where('active',true)
            ->select([
                'id','type_id','name','date_start','date_end',
                'success_registration_start','success_registration_stop',
                'reserve_registration_start','reserve_registration_stop',
                'description'
            ]);

        $datatables =  app('datatables')->of($exams);


        return $datatables
            ->addColumn('date_start_end',function($exam){
                return Carbon::createFromFormat('Y-m-d h:i:s',$exam->date_start)->toFormattedDateString() ." - ". Carbon::createFromFormat('Y-m-d h:i:s',$exam->date_end)->toFormattedDateString();
            })
            ->addColumn('success_registration_date_start_end',function($exam){
                if($exam->success_registration_start != null){
                    return Carbon::createFromFormat('Y-m-d',$exam->success_registration_start)->toFormattedDateString() ." - ".Carbon::createFromFormat('Y-m-d',$exam->success_registration_stop)->toFormattedDateString();
                } else {
                    return "-";
                }
            })
            ->addColumn('reserve_registration_date_start_end',function($exam){
                if($exam->reserve_registration_start != null){
                    return Carbon::createFromFormat('Y-m-d',$exam->reserve_registration_start)->toFormattedDateString() ." - ".Carbon::createFromFormat('Y-m-d',$exam->reserve_registration_stop)->toFormattedDateString();
                } else {
                    return "-";
                }
            })
            ->addColumn('action', function ($exam) {
                $action = "";
                if(Auth::user()->allow('edit-exams')){
                    $action = $action.'<a href="'.route('admin.exam.edit',[$exam->type_id,$exam->id]).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>';
                }
                if(Auth::user()->allow('delete-exams')){
                    $action = $action.' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.exams.destroy', $exam->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
                }

                $action = $action.' <a href="'.route('admin.exam.show',[$exam->type_id,$exam->id]).'" class="btn btn-xs btn-info"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.view').'"></i> </a>';
                return  $action;
            })
            ->make(true);
    }
    
    public function get_courses($id){
        $exam = $this->exams->findOrThrowException($id);
        $course = $exam->courses();

        $datatables =  app('datatables')->of($course);


        return $datatables
            ->editColumn('name', '{!! $name !!}')
            ->editColumn('date_start', '{!! $date_start !!}')
            ->editColumn('date_end', '{!! $date_end !!}')
            ->editColumn('description', '{!! $description !!}')
            ->addColumn('action', function ($exam) {
                return  '<a href="'.route('admin.exams.edit',$exam->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.exams.destroy', $exam->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>'.
                ' <a href="'.route('admin.exams.show',$exam->id).'" class="btn btn-xs btn-info"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.view').'"></i> </a>';
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

    public function add_room(ModifyExamRoomRequest$request, $id){

        $exam = $this->exams->findOrThrowException($id);

        if(isset($_POST['room_id']) && $_POST['room_id'] != null){ //This one is for edit
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

        $exam_rooms = $exam->rooms()->with(['building','candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list',compact('exam_rooms'));
    }

    public function merge_rooms(ModifyExamRoomRequest $request, $id){

        $exam = $this->exams->findOrThrowException($id);
        $rooms = $_POST['rooms'];

        if(is_numeric($rooms[0])){
            $room_0 = ExamRoom::find($rooms[0]);
        } else {
            $room_0 = ExamRoom::find($rooms[1]); // prevent get header too
        }

        //dd($rooms);
        foreach($rooms as $key => $room){
            if(is_numeric($room)) {
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

        $exam_rooms = $exam->rooms()->with(['building','candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list',compact('exam_rooms'));
    }

    public function split_room(ModifyExamRoomRequest$request, $id){

        //dd($_POST);
        $exam = $this->exams->findOrThrowException($id);
        ExamRoom::destroy($_POST['split_room']);

        for ($i=0;$i<count($_POST['name']);$i++){
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

        $exam_rooms = $exam->rooms()->with(['building','candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list',compact('exam_rooms'));
    }

    public function generate_rooms(ModifyExamRoomRequest $request, $id){ // In Room section

        $exam = $this->exams->findOrThrowException($id);
        $exam_rooms = $exam->rooms()->get();


        foreach($exam_rooms as $room) {
            //$room->delete(); // delete all realted room first because this is the first genration
            ExamRoom::destroy($room->id);
        }

        $rooms = DB::table('rooms')
            ->select('id','nb_chair_exam','name','room_type_id','building_id','department_id')
            ->where('is_exam_room',true)
            ->get();

        foreach($rooms as $room){
            $exam_room = new ExamRoom();
            if($room->nb_chair_exam > $_POST['exam_chair']+ 5 || $room->nb_chair_exam < $_POST['exam_chair'] -5){
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

        $exam_rooms = $exam->rooms()->with(['building','candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list',compact('exam_rooms'));
    }

    public function refresh_room($id){
        $exam = $this->exams->findOrThrowException($id);

        $exam_rooms = $exam->rooms()->with(['building','candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list',compact('exam_rooms'));
    }

    public function sort_room_capacity($id){
        $exam = $this->exams->findOrThrowException($id);

        $exam_rooms = $exam->rooms()->with(['building','candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list',compact('exam_rooms'));
    }

    public function delete_rooms(ModifyExamRoomRequest $request, $id){
        $exam = $this->exams->findOrThrowException($id);

        $room_ids = $_POST['exam_room'];

        foreach($room_ids as $room_id){
            if(is_numeric($room_id)){
                ExamRoom::destroy($room_id);
            }
        }

        $exam_rooms = $exam->rooms()->with(['building','candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list',compact('exam_rooms'));

    }

    public function edit_seats(ModifyExamRoomRequest $request, $id){
        $exam = $this->exams->findOrThrowException($id);

        $room_ids = $_POST['rooms'];

        //dd($_POST['rooms']);
        foreach($room_ids as $room_id){
            if(is_numeric($room_id)){
                DB::table("examRooms")->where('id',$room_id)->update(['nb_chair_exam'=>$_POST['nb_chair_exam']]);
            }
        }

        $exam_rooms = $exam->rooms()->with(['building','candidates'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list',compact('exam_rooms'));

    }

    public function count_seat_exam($id){
        $exam = $this->exams->findOrThrowException($id);
        $rooms = $exam->rooms()->get();

        $seat_exam = 0;

        foreach ($rooms as $room){
            $seat_exam = $seat_exam + $room->nb_chair_exam;
        }

        return Response::json(array("seat_exam"=>$seat_exam));
    }

    public function count_assigned_seat($id){
        $rooms = DB::table('candidates')
            ->where('exam_id',$id)
            ->select(DB::raw('count(*) as room_count, room_id'))
            ->groupBy('room_id')
            ->get();

        $result = [];
        foreach($rooms as $room){
            if(isset($result[$room->room_count])){
                $result[$room->room_count] = $result[$room->room_count]+1;
            } else {
                $result[$room->room_count] = 1;
            }
        }
        return Response::json($result);
    }

    public function view_room_secret_code(ViewSecretCodeRequest $request, $exam_id){
        $exam = $this->exams->findOrThrowException($exam_id);

        $rooms = $exam->rooms()->with('building')->orderBy('building_id')->orderBy('name')->get()->toArray();

        foreach($rooms as &$room){
            if($room['roomcode'] == ""){
                $room['roomcode'] = "";
            } else {
                $room['roomcode'] = Crypt::decrypt($room['roomcode']);
            }
        }

        return view('backend.exam.includes.popup_room_secret_code',compact('rooms','exam_id'));
    }

    public function save_room_secret_code(ViewSecretCodeRequest $request, $exam_id){

        $rooms = json_decode($_POST['room_ids']);
        //dd($rooms);
        foreach($rooms as $room){
            DB::table('examRooms')
                ->where('exam_id',$exam_id)
                ->where('id',$room->room_id)
                ->update(['roomcode'=> Crypt::encrypt($room->secret_code)]);
        }

        return Response::json(array('success'=>true,'message'=>"Secret code is saved successfully"));
    }

    public function export_room_secret_code(ViewSecretCodeRequest $request, $exam_id){
        $exam = $this->exams->findOrThrowException($exam_id);

        $rooms = $exam->rooms()->with('building')->orderBy('building_id')->orderBy('name')->get()->toArray();

        foreach($rooms as &$room){
            if($room['roomcode'] == ""){
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
        Excel::create('Room secret code', function($excel) use ($rooms,$alpha) {

            // Set the title
            $excel->setTitle('Room secret code');

            // Chain the setters
            $excel->setCreator('Director of development & planning')
                ->setCompany('Institute of Technology of Cambodia');

            $excel->sheet('Secret codes', function($sheet) use ($rooms,$alpha) {

                $header = array('#',"Room Name","Room Code");
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

                    $row = array($index,$item['building']['code'].$item['name'],$item['roomcode']);

                    $sheet->appendRow(
                        $row
                    );
                    $index++;
                }

                $sheet->mergeCells('A1:C1');
                $sheet->cells('A1:C'.count($rooms)+1, function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('middle');
                });
                $sheet->setBorder('A2:C'.(2+count($rooms)), 'thin');

            });

        })->export('xls');
    }

    public function export_attendance_list(DownloadExaminationDocumentsRequest $request,$exam_id) {

        $exam = $this->exams->findOrThrowException($exam_id);
        $courses = $exam->entranceExamCourses()->get();
        $rooms = $exam->rooms()->with('building')->orderBy('building_id')->orderBy('name')->get();
        $candidateData=[];

        if($courses) {
            foreach($courses as $course) {
                $arrayTmpCands = [];
                if($rooms) {
                    foreach($rooms as $room) {
                        $candidates =  $room->candidates()->with('gender')->orderBy('register_id')->get();
                        foreach($candidates as $candidate) {
                            $element = array(
                                'លេខបង្កាន់ដៃ'              => str_pad($candidate->register_id, 4, '0', STR_PAD_LEFT),
                                'បន្ទប់'                    => $room->building->code."-".$room->name,
                                'ឈ្មោះ ខ្មែរ'             => $candidate->name_kh,
                                'ឈ្មោះ ឡាតាំង'            => $candidate->name_latin,
                                'ភេទ'                      => $candidate->gender->code,
                                'ថ្ងៃខែរឆ្នាំកំនើត'           => $candidate->dob->formatLocalized("%d/%b/%Y"),
                                'ហត្ថលេខា'                => ''
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

//        dd($candidateData);

        $fields= ['លេខបង្កាន់ដៃ', 'បន្ទប់', 'ឈ្មោះ ខ្មែរ', 'ឈ្មោះ ឡាតាំង', 'ភេទ', 'ថ្ងៃខែរឆ្នាំកំនើត', 'ហត្ថលេខា'];

//        $fields= ['Order', 'Room', 'Name Khmer', 'Name Latin', 'Sexe', 'Birth Date', 'Signature'];
        $title = 'បញ្ជីវត្តមានបេក្ខជន';
//        $title = 'Candidates';
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        Excel::create('បញ្ជីវត្តមានបេក្ខជន', function($excel) use ($candidateData, $title,$alpha,$fields) {
            foreach($candidateData as $key => $data) {
                $excel->sheet($key, function($sheet) use($data,$title,$alpha,$fields) {
                    $sheet->fromArray($data);
                });
            }
        })->export('xls');

    }

    public function export_candidate_list($exam_id) {
        $exam = $this->exams->findOrThrowException($exam_id);
        $rooms = $exam->rooms()->with('building')->orderBy('building_id')->orderBy('name')->get();
        $candidateByRoom =[];

        if($rooms) {
            foreach($rooms as $room) {
                $candidates = $room->candidates()->with('gender')->orderBy('register_id')->get();
                if($candidates) {
                    foreach($candidates as $candidate) {
                        $element = array(
                            'លេខបង្កាន់ដៃ'              => str_pad($candidate->register_id, 4, '0', STR_PAD_LEFT),
                            'ឈ្មោះ ខ្មែរ'             => $candidate->name_kh,
                            'ឈ្មោះ ឡាតាំង'            => $candidate->name_latin,
                            'ភេទ'                      => $candidate->gender->code,
                            'ថ្ងៃខែរឆ្នាំកំនើត'           => $candidate->dob->formatLocalized("%d/%b/%Y")
                        );
                        $candidateByRoom[$room->building->code."-".$room->name][] = $element;

                    }
                }
            }
        }

        $fields= ['លេខបង្កាន់ដៃ', 'ឈ្មោះ ខ្មែរ', 'ឈ្មោះ ឡាតាំង', 'ភេទ', 'ថ្ងៃខែរឆ្នាំកំនើត'];
        $title = 'បញ្ជីបេក្ខជន';
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        Excel::create('បញ្ជីបេក្ខជន', function($excel) use ($candidateByRoom, $title,$alpha,$fields) {
            foreach($candidateByRoom as $key => $data) {
                $excel->sheet($key, function($sheet) use($data,$title,$alpha,$fields) {
                    $sheet->fromArray($data);
                });
            }
        })->export('xls');
    }

    public function export_candidate_list_by_register_id ($exam_id) {

        $exam = $this->exams->findOrThrowException($exam_id);
        $candidates = $exam->candidates()->with('gender')->with('room')->with('room.building')->orderBy('register_id')->get();
        $candidateByRoom=[];

        if($candidates) {
            foreach($candidates as $candidate) {

                $candidateRooms = $candidate->room;
                if($candidateRooms) {
                    $element = array(
                        'លេខបង្កាន់ដៃ'              => str_pad($candidate->register_id, 4, '0', STR_PAD_LEFT),
                        'បន្ទប់'                    => $candidate->room->building->code."-".$candidate->room->name,
                        'ឈ្មោះ ខ្មែរ'             => $candidate->name_kh,
                        'ឈ្មោះ ឡាតាំង'            => $candidate->name_latin,
                        'ភេទ'                      => $candidate->gender->code,
                        'ថ្ងៃខែរឆ្នាំកំនើត'           => $candidate->dob->formatLocalized("%d/%b/%Y")
                    );
                    $candidateByRoom[] = $element;
                }
            }
        }

        $fields= ['លេខបង្កាន់ដៃ', 'បន្ទប់', 'ឈ្មោះ ខ្មែរ', 'ឈ្មោះ ឡាតាំង', 'ភេទ', 'ថ្ងៃខែរឆ្នាំកំនើត'];
        $title = 'បញ្ជីបេក្ខជន';
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        Excel::create('បញ្ជីបេក្ខជនតាមបន្ទប់ រៀបតាមលេខបង្កាន់ដៃ', function($excel) use ($candidateByRoom, $title,$alpha,$fields) {

                $excel->sheet($title, function($sheet) use($candidateByRoom,$title,$alpha,$fields) {
                    $sheet->fromArray($candidateByRoom);
                });
        })->export('xls');
    }

    public function download_attendance_list(DownloadExaminationDocumentsRequest $request, $exam_id){

        $exam = $this->exams->findOrThrowException($exam_id);
        $courses = $exam->entranceExamCourses()->get();
        $rooms = $exam->rooms()->with('building')->orderBy('building_id')->orderBy('name')->get();

        return view('backend.exam.print.attendance_list',compact('rooms','courses'));
    }

    public function download_candidate_list(DownloadExaminationDocumentsRequest $request,$exam_id){

        $exam = $this->exams->findOrThrowException($exam_id);
        $rooms = $exam->rooms()->with('building')->orderBy('building_id')->orderBy('name')->get();

        return view('backend.exam.print.candidate_list',compact('rooms'));
    }

    public function download_candidate_list_by_register_id(DownloadExaminationDocumentsRequest $request,$exam_id){

        $exam = $this->exams->findOrThrowException($exam_id);
        $candidates = $exam->candidates()->with('gender')->with('room')->with('room.building')->orderBy('register_id')->get()->toArray();

        $chunk_candidates = array_chunk($candidates,30);

        return view('backend.exam.print.candidate_list_order_by_register_id',compact('chunk_candidates'));
    }

    public function download_room_sticker(DownloadExaminationDocumentsRequest $request,$exam_id){
        $exam = $this->exams->findOrThrowException($exam_id);
        $rooms = $exam->rooms()->with('building')->orderBy('building_id')->orderBy('name')->get();

        return view('backend.exam.print.room_sticker',compact('rooms'));
    }

    public function download_correction_sheet(DownloadExaminationDocumentsRequest $request,$exam_id){
        $exam = $this->exams->findOrThrowException($exam_id);
        $courses = $exam->entranceExamCourses()->get();
        $rooms = $exam->rooms()->with('candidates')->get()->toArray();

        foreach($rooms as &$room){
            $room['roomcode'] = Crypt::decrypt($room['roomcode']);
        }

        usort($rooms, function($a, $b) {
            return $a['roomcode'] - $b['roomcode'];
        });

        return view('backend.exam.print.correction_sheet',compact('rooms','courses'));
    }

    public function download_candidate_list_dut(DownloadExaminationDocumentsRequest $request,$exam_id){
        $candidates = Candidate::where('exam_id',$exam_id)
            ->leftJoin('genders','candidates.gender_id','=','genders.id')
            ->leftJoin('origins','candidates.province_id','=','origins.id')
            //->leftJoin('candidate_department','candidates.id','=','candidate_department.candidate_id')
            ->leftJoin('highSchools','candidates.highschool_id','=','highSchools.id')
            ->leftJoin('gdeGrades as bacTotal','candidates.bac_total_grade','=','bacTotal.id')
            ->leftJoin('gdeGrades as mathGrade','candidates.bac_math_grade','=','mathGrade.id')
            ->leftJoin('gdeGrades as physGrade','candidates.bac_phys_grade','=','physGrade.id')
            ->leftJoin('gdeGrades as chemGrade','candidates.bac_chem_grade','=','chemGrade.id')
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
        foreach($candidates as &$candidate){
            $department_choices = DB::table('candidate_department')
                ->join('departments','candidate_department.department_id','=','departments.id')
                ->where('candidate_id',$candidate['id'])
                ->select([
                    'candidate_department.rank',
                    'departments.code'
                ])
                ->orderBy('departments.code')
                ->get();
            $candidate["departments"] = $department_choices;
        }

        //dd($candidates);
        $chunk_candidates = array_chunk($candidates,25);

        $departments = Department::where('is_specialist',true)->where('parent_id',11)->orderBy('code')->get();

        //dd($chunk_candidates);
        return view('backend.exam.print.candidate_list_dut',compact('chunk_candidates','departments'));
    }

    public function download_candidate_list_ing(DownloadExaminationDocumentsRequest $request,$exam_id){
//        $exam = $this->exams->findOrThrowException($exam_id);
//        $candidates = $exam->candidates()
//            ->with('gender')
//            ->with('origin')
//            ->with('bacTotal')
//            ->orderBy('register_id')
//            ->get()->toArray();

        $candidates = Candidate::where('exam_id',$exam_id)
                        ->leftJoin('genders','candidates.gender_id','=','genders.id')
                        ->leftJoin('origins','candidates.province_id','=','origins.id')
                        ->leftJoin('highSchools','candidates.highschool_id','=','highSchools.id')
                        ->leftJoin('gdeGrades as bacTotal','candidates.bac_total_grade','=','bacTotal.id')
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

        $chunk_candidates = array_chunk($candidates,43);

        //dd($chunk_candidates);
        return view('backend.exam.print.candidate_list_ing',compact('chunk_candidates'));
    }

    public function download_registration_statistic(DownloadExaminationDocumentsRequest $request,$exam_id){

        $dates = Candidate::where('exam_id',$exam_id)
            ->orderBy('created_at')
            ->get()
            ->groupBy(function($date) {
                $group = [
                    Carbon::parse($date->created_at)->format('d-m-Y')
                ];
                return $group;
            })
            ->toArray();

        $candidates = array();
        foreach($dates as $key=>$date){
            $candidates[$key][34] = array();
            $candidates[$key][35] = array();
            $candidates[$key][36] = array();
            $candidates[$key][37] = array();
            $candidates[$key][38] = array();
            foreach($date as $candidate){
                array_push($candidates[$key][$candidate['bac_total_grade']],$candidate);
            }
        }

        //dd($candidates);
        return view('backend.exam.print.registration_statistic',compact('candidates'));
    }

    /*public function request_add_courses($exam_id){
        $exam = $this->exams->findOrThrowException($exam_id);
        if($exam->type_id == 1){  // This ID=1 is for entrance engineer
            return view('backend.exam.includes.popup_add_course',compact('rooms','exam_id'));
        } else{
            return view('backend.exam.includes.popup_add_course',compact('rooms','exam_id'));
        }
    }*/

    public function save_entrance_exam_course(CreateEntranceExamCourseRequest $request, $exam_id){
        $input = $request->all();

        $input['create_uid'] = auth()->id();
        $input['created_at'] = Carbon::now();
        $input['exam_id'] = $exam_id;

        if(EntranceExamCourse::create($input)){
            return Response::json(array('success'=>true));
        } else {
            return Response::json(array('success'=>false));
        }
    }

    public function requestInputScoreCourses($exam_id) {

        $courses = EntranceExamCourse::where('exam_id',$exam_id)->get();
        return view('backend.exam.includes.popup_add_input_score_course',compact('exam_id', 'courses'));
    }


    public function getAllRooms($exam_id, Request $request) { // From select course, roomcode, correction number form

        $correction = $request->number_correction;
        $entranceCourseId = $request->entrance_course_id;

        $rooms = $this->roomWithNotInputtedScoreCandidate($exam_id, $correction, $entranceCourseId);


        return view('backend.exam.includes.partial_selection_room_course', compact('rooms'))->render();


    }

    private function getRoomsFromDB($exam_id) {

        $roomFromDB = DB::table('examRooms')
            ->select('examRooms.roomcode as room_code', 'examRooms.id as room_id')
            ->where('exam_id',$exam_id)
            ->WhereNotNull('examRooms.roomcode')
            ->get();

        return $roomFromDB;

    }


    private  function roomWithNotInputtedScoreCandidate ($exam_id, $correction, $entranceCourseId) {

        $readyRooms = [];
        //get all examRooms -> get all candidate in each room -> get all the number correction of candidate from db then compare if the sequence = the requested correction then we dont get that room

        // Room which have already score
        $roomWithIputtedScore = DB::table('secret_room_score')
            ->where('exam_id',$exam_id)
            ->where('sequence',$correction)
            ->where('course_id',$entranceCourseId)
            ->groupBy('roomcode')
            ->lists('roomcode');


        $allRooms = DB::table('examRooms')
            ->where('examRooms.exam_id', '=', $exam_id)
            ->lists('roomcode','id');

        $rooms = [];

        foreach($allRooms as $key => $room) {
            $code = Crypt::decrypt($room);
            if(!in_array($code,$roomWithIputtedScore)) {
                $rooms[$key] = $code;
            }
        }

        asort($rooms);
        return $rooms;

    }

    private function sortRoomCodes($a, $b)
    {
        return  $a->room_code - $b->room_code ;
    }

    public function getRequestInputScoreForm($exam_id, Request $request) {


        $subject = $request->course_name;
        $subjectId = $request->entrance_course_id;
        $roomId = $request->room_id;
        $roomCode = $request->room_code;
        $number_correction = (int)$request->number_correction;
        $rooms = [];
        //$allRooms = $this->getRoomsFromDB($exam_id);
        $roomForSelection = [];

        $availableRooms = $this->roomWithNotInputtedScoreCandidate($exam_id, $number_correction, $subjectId);

        $all_keys= array_keys($availableRooms);
        $cur_key = array_search($roomId,$all_keys);

        $preRoom = null;
        $nextRoom = null;
        if($cur_key-1 > 0){
            $prev_key = $all_keys[$cur_key-1];
            $preRoom = ['room_id'=>$prev_key,'room_code'=>$availableRooms[$prev_key]];
        }

        if($cur_key+1 < count($all_keys)){
            $next_key = $all_keys[$cur_key+1];
            $nextRoom = ['room_id'=>$next_key, 'room_code'=>$availableRooms[$next_key]];
        }


        if( $number_correction !== 0) {

            $candidates = $this->exams->requestInputScoreForm($exam_id, $request, $number_correction);

            if($candidates) {
                $status = true;
            } else {
                $status = false;
            }

            return view('backend.exam.includes.form_input_score_candidates',compact('status', 'candidates','exam_id','roomCode', 'subject','number_correction', 'subjectId', 'roomId', 'nextRoom', 'preRoom', 'availableRooms'));

        } else {

            return Response::json(['status' => false]);
        }

    }

    public function insertScoreForEachCandiate($exam_id, Requests\Backend\Exam\StoreEntranceExamScoreRequest $request) {

        $requestDatas = $_POST;
        $correctorName = $request->corrector_name;

        if($correctorName) {
            $candidates = $this->exams->insertCandidateScore($exam_id, $requestDatas, $correctorName);

            if($candidates['status']) {
                return Response::json(['status'=>true]);
            } else {
                return Response::json(['status'=>false]);
            }
        } else {
            return Response::json(['status'=>false]);
        }


    }

    public function reportErrorCandidateScores($exam_id, Request $request) {

        $courseId = $request->course_id;
        $errorCandidateScores = $this->exams->getErrorScore($exam_id, $request->course_id);

        $totalQuestions = DB::table('entranceExamCourses')
            ->where([
                ['entranceExamCourses.active', '=', true],
                ['entranceExamCourses.id', '=',$courseId ]
            ])
            ->select('total_question', 'name_kh')
            ->first();

        $totalQuestion = $totalQuestions->total_question;
        $courseName = $totalQuestions->name_kh;

        return view('backend.exam.includes.popup_report_error_score_candidate1', compact('exam_id', 'errorCandidateScores', 'totalQuestion', 'courseId', 'courseName'));

    }

    public function addNewCorrectionScore($exam_id, Request $request) {
        //Requests\Backend\Exam\StoreEntranceExamScoreRequest

        //dd($request->serializ_data);
        $correctorName  = $request->corrector_name;

        if($correctorName) {
            $res = $this->exams->addNewCorrectionCandidateScore($exam_id, $request, $correctorName);
            if($res) {
                return Response::json(['status'=> true]);
            } else {
                return Response::json(['status'=> false]);
            }
        } else {
            return Response::json(['status'=> false]);
        }


    }

    public function candidateResultExamScores($exam_id) {

        $courseIds = $this->getAllExamCourses($exam_id);
        $checkScore = $this-> checkEntranceExamScores($exam_id, $courseIds);
        $check =$checkScore[0];
        $courses = $checkScore[1];

        if($courseIds) {
            if($check == count($courseIds)) {

                return view('backend.exam.includes.popup_get_form_result_score', compact('exam_id', 'courseIds'));

            } else {
                return view('backend.exam.includes.error_popup_message', compact('courses'))->with(['message'=>'There is an existing score error']);
            }
        }


    }

    private function getAllExamCourses($exam_id) {

        $courseIds = DB::table('entranceExamCourses')
            ->where([
                ['exam_id', '=', $exam_id],
                ['entranceExamCourses.active', '=', true]
            ])
            ->select('id as course_id', 'name_kh as course_name')->get();

        return $courseIds;
    }

    private function checkEntranceExamScores($exam_id, $courseIds) {

        $check =0;
        $courses = [];
        if($courseIds) {
            foreach($courseIds as $courseId) {
                $restult = DB::table('statusCandidateScores')
                    ->select('status')
                    ->where([
                        ['exam_id', '=', $exam_id],
                        ['entrance_exam_course_id', '=', $courseId->course_id]
                    ])
                    ->first();

                if($restult->status == false) {
                    $check++;
                } else {
                    $courses[]=$courseId->course_name;
                }

            }

            return [$check, $courses];

        }
    }

    public function checkCandidateScores($examId) {

        $courseIds = $this->getAllExamCourses($examId);
        $check=0;
        //$checkScore = $this->checkEntranceExamScores($examId, $courseIds);

        foreach($courseIds as $courseId) {

            $errorCandidateScores = $this->exams->getErrorScore($examId, $courseId->course_id);

            if(count($errorCandidateScores)>0) {
                $check++;
            }
        }

        if($check== count($courseIds)) {
            return Response::json(['status'=> true]);//there are error of candidate score
        } else{
            return Response::json(['status'=> false]);// there are no error
        }


    }

    public function calculateCandidateScores($examId, Request $request) {

        $requestData = $_POST;
        $passedCandidates = (int)$requestData['total_pass'];
        $reservedCandidates = (int)$requestData['total_reserve'];

        // Clean all data in secret_room_result
        DB::table('secret_room_result')->delete();

        // Calculate total score to each order in secret room
        DB::transaction(function () use($requestData, $examId) {
            foreach($requestData['course'] as $course){
                $total_question = DB::table('entranceExamCourses')->where('id',$course['id'])->first()->total_question;
                //dd($course);
                $query = DB::select(
                    "select roomcode, order_in_room,course_id,exam_id,score_c,score_w,score_na, count(*), (score_c+score_w+score_na) as total".
                    " from secret_room_score".
                    " where exam_id =".$examId.
                    " and course_id =".$course['id'].
                    " group by roomcode,order_in_room,course_id,exam_id,score_c,score_w,score_na".
                    " HAVING count(*) > 1".
                    " order by roomcode, order_in_room, course_id;"
                );

                foreach($query as $result){
                    if($result->total == $total_question || $result->total == 0){ // This one is correct, just double check
                        $score = (($result->score_c * (int)$course['correct']) - ($result->score_w * (int)$course['wrong']))*(int)$course['coe'];

                        $secret_room_result = DB::table('secret_room_result')
                            ->where('roomcode',$result->roomcode)
                            ->where('exam_id',$examId)
                            ->where('order_in_room',$result->order_in_room)
                            ->select('score')
                            ->first();

                        if($secret_room_result == null){ // Not yet add
                            if($result->score_c == 0 && $result->score_w == 0 && $result->score_na == 0){ // Once course is absence
                                DB::table('secret_room_result')
                                    ->insert(
                                        ['roomcode' => $result->roomcode, 'exam_id' => $examId, 'order_in_room'=>$result->order_in_room,'score'=>0,'is_absence'=>true]
                                    );
                            } else {
                                DB::table('secret_room_result')
                                    ->insert(
                                        ['roomcode' => $result->roomcode, 'exam_id' => $examId, 'order_in_room'=>$result->order_in_room,'score'=>$score]
                                    );
                            }

                        } else {
                            if($result->score_c == 0 && $result->score_w == 0 && $result->score_na == 0){ // Once course is absence
                                DB::table('secret_room_result')
                                    ->where('roomcode',$result->roomcode)
                                    ->where('exam_id',$examId)
                                    ->where('order_in_room',$result->order_in_room)
                                    ->update(
                                        ['score'=>0,'is_absence'=>true]
                                    );
                            } else {
                                DB::table('secret_room_result')
                                    ->where('roomcode',$result->roomcode)
                                    ->where('exam_id',$examId)
                                    ->where('order_in_room',$result->order_in_room)
                                    ->update(
                                        ['score'=>$score+$secret_room_result->score]
                                    );
                            }
                        }
                    }
                }
            }
        });

        // Categorize to pass, reserve or fail or absence

        $final_results = DB::table('secret_room_result')
            ->where('exam_id',$examId)
            ->orderBy('score','desc')
            ->get();

        $last_score = null;
        foreach($final_results as $final_result){

            if($final_result->is_absence == true){ // This student have 0 score, that mean they are absence
                DB::table('secret_room_result')
                    ->where('roomcode',$final_result->roomcode)
                    ->where('exam_id',$examId)
                    ->where('order_in_room',$final_result->order_in_room)
                    ->update(
                        ['result'=>"Reject"]
                    );
            } else {
                if($passedCandidates> -1){
                    DB::table('secret_room_result')
                        ->where('roomcode',$final_result->roomcode)
                        ->where('exam_id',$examId)
                        ->where('order_in_room',$final_result->order_in_room)
                        ->update(
                            ['result'=>"Pass"]
                        );
                    $passedCandidates--;

                } else if($passedCandidates == -1){ // The first candidate out of passed range

                    if($last_score!= null && $last_score->score == $final_result->score){
                        DB::table('secret_room_result')
                            ->where('roomcode',$final_result->roomcode)
                            ->where('exam_id',$examId)
                            ->where('order_in_room',$final_result->order_in_room)
                            ->update(
                                ['result'=>"Pass"]
                            );
                    } else {
                        $passedCandidates--;
                    }
                } else if ($reservedCandidates > -1) {
                    DB::table('secret_room_result')
                        ->where('roomcode',$final_result->roomcode)
                        ->where('exam_id',$examId)
                        ->where('order_in_room',$final_result->order_in_room)
                        ->update(
                            ['result'=>"Reserve"]
                        );
                    $reservedCandidates--;

                } else if ($reservedCandidates == -1) {
                    if($last_score!= null && $last_score->score == $final_result->score) {
                        DB::table('secret_room_result')
                            ->where('roomcode', $final_result->roomcode)
                            ->where('exam_id', $examId)
                            ->where('order_in_room', $final_result->order_in_room)
                            ->update(
                                ['result' => "Reserve"]
                            );
                    } else {
                        $reservedCandidates--;
                    }
                } else {
                    DB::table('secret_room_result')
                        ->where('roomcode',$final_result->roomcode)
                        ->where('exam_id',$examId)
                        ->where('order_in_room',$final_result->order_in_room)
                        ->update(
                            ['result'=>"Fail"]
                        );
                }
                $last_score = $final_result;
            }

        }

        // Mapping with secret room

        $exam_rooms = ExamRoom::where('exam_id',$examId)
            ->get();

        foreach($exam_rooms as $exam_room){
            $candidates = DB::table('candidates')
                ->where('room_id',$exam_room->id)
                ->orderBy('register_id','ASC')
                ->get();

            $candidateResults = [];

            $candResults = DB::table('secret_room_result')
                ->where('secret_room_result.roomcode', '=', Crypt::decrypt($exam_room->roomcode))
                ->get();

            foreach($candResults as $candResult) {
                $candidateResults[$candResult->order_in_room] = (['result'=>$candResult->result, 'score'=> $candResult->score]);
            }

            if($candidateResults) {
                if($candResults) {

                    $index =1;
                    if($index <=count($candidates)) {

                        foreach($candidates as $candidate) {
                            if(isset($candidateResults[$index])){
                                $update = DB::table('candidates')
                                    ->where('candidates.id',$candidate->id)
                                    ->update(
                                        ['result'=>$candidateResults[$index]['result'], 'total_score'=>$candidateResults[$index]['score']]
                                    );
                            }

                            $index++;
                        }
                    }

                }
            }
        }

        return Response::json(['status'=> true, 'exam_id'=>$examId]);

    }

    public function candidateResultLists(Request $request) {

        $examId = $request->exam_id;

//        dd($examId);
        $candidatesResults = $this->getCandidateResult($examId);


        dd($candidatesResults);

        return view('backend.exam.includes.examination_candidates_result', compact('candidatesResults', 'examId'));
    }

    private function candResFromDB($exam_id, $resultType) {

        $candRes = DB::table('candidates')
            ->join('genders', 'genders.id', '=', 'candidates.gender_id')
            ->join('examRooms','examRooms.id', '=','candidates.room_id')
            ->join('buildings','examRooms.building_id', '=','buildings.id')
            ->join('origins','origins.id', '=','candidates.province_id')
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

    private function getCandidateResult($exam_id) {

        $studentPassed = $this->candResFromDB($exam_id, $resultType='Pass');

        //dd($studentPassed);

        $studentReserved = $this->candResFromDB($exam_id, $resultType='Reserve');

//        $studentFail = $this->candResFromDB($exam_id, $resultType='Fail');

//        $studentReject = $this->candResFromDB($exam_id, $resultType='Reject');

//        usort($studentPassed, array($this, "sortCandidateRank"));
//        usort($studentReserved, array($this, "sortCandidateRank"));
//        usort($studentFail, array($this, "sortCandidateRank"));


//        $candidateTmp1 =  array_merge((array) $studentPassed, (array) $studentReserved);
//        $candidateTmp2 = array_merge((array) $studentFail, (array) $studentReject);

//        $candidateResults = array_merge((array) $candidateTmp1, (array) $candidateTmp2);


        return array('ស្ថាពរ'=>$studentPassed, 'បំរុង'=>$studentReserved);


    }

    public function printCandidateResultLists(Request $request) {

        if($request->status == 'request_print_page') {
            return Response::json(['status'=> true]);

        } else {

            $candidateRes = $this->getCandidateResult($request->exam_id);

//            $candidatesResults = $candRes[0];

            if($candidateRes) {
                $status = true;
//                $candidatesResults = array_chunk($candidatesResults, 30);
                return view('backend.exam.print.examination_candidates_result', compact('candidateRes', 'status'));
            } else {
                $status = false;
                return view('backend.exam.print.examination_candidates_result', compact('status'));
            }
        }
    }

    public function export_candidate_ministry_list($exam_id) {


        $cands = DB::table('candidates')
            ->join('studentBac2s', 'studentBac2s.id', '=', 'candidates.studentBac2_id')
            ->where('candidates.exam_id', $exam_id)
            ->select(
                'studentBac2s.can_id',
                'candidates.result',
                'candidates.total_score'
            )
            ->orderBy('candidates.total_score', 'DESC')
            ->get();

        $candidateByRank=[];
        $index =1;
        foreach($cands as $cand) {
            $candidateByRank[$cand->can_id] = $index;
            $index++;
        }


        $candidates = DB::table('candidatesFromMoeys')
            ->join('studentBac2s', 'studentBac2s.can_id','=', 'candidatesFromMoeys.can_id')
            ->join('genders','studentBac2s.gender_id','=','genders.id')
            ->join('highSchools','studentBac2s.highschool_id','=','highSchools.id')
            ->join('origins','studentBac2s.province_id','=','origins.id')
            ->leftJoin('gdeGrades as math','studentBac2s.bac_math_grade','=','math.id')
            ->leftJoin('gdeGrades as phys','studentBac2s.bac_phys_grade','=','phys.id')
            ->leftJoin('gdeGrades as chem','studentBac2s.bac_chem_grade','=','chem.id')
            ->select(
                'candidatesFromMoeys.id',
                'candidatesFromMoeys.can_id',
                'studentBac2s.name_kh',
                'highSchools.name_kh as highschool',
                'genders.code as gender',
                'studentBac2s.dob',
                'origins.name_kh as origin',
                'studentBac2s.bac_year',
                'math.name_en as math_grade',
                'phys.name_en as phys_grade',
                'chem.name_en as chem_grade'
            )
            ->orderBy('candidatesFromMoeys.can_id')
            ->get();


        foreach($candidates as &$candidate){
            if(isset($candidateByRank[$candidate->can_id])){
                $candidate->result = $candidateByRank[$candidate->can_id];
            } else {
                $candidate->result = "Reject";
            }
        }

        Excel::create('Student Result To Send to DHE', function($excel) use ($candidates) {

            // Set the title
            $excel->setTitle('លទ្ធផលសំរាប់បញ្ជូនទៅគ្រឹះស្ថានឧត្តមសិក្សា');

            // Chain the setters
            $excel->setCreator('Institute of Technology of Cambodia')
                ->setCompany('Institute of Technology of Cambodia');

            $excel->sheet('បញ្ជីនិស្សិត្រ', function($sheet) use ($candidates) {

                $header = array('ល.រ',"គោត្តនាមនិងនាម","ភេទ","ថ្ងៃខែឆ្នាំកំណើត","មកពីវិទ្យាល័យ","ឆ្នាំជាប់ទុតិយភូមិ","គណិតវិទ្យា","រូបវិទ្យា","គីមីវិទ្យា","ចំណាត់ថ្នាក់");
                $sheet->setOrientation('portrait');
                // Set top, right, bottom, left
                $sheet->setPageMargin(array(
                    0.25, 0.30, 0.25, 0.30
                ));

                // Set all margins
                $sheet->setPageMargin(0.25);

                $sheet->rows(
                    array($header)
                );

                $index = 1;
                dd($candidates);
                foreach ($candidates as $candidate) {

                    if($candidate->result == "Reject"){
                        $result = "ធ្លាក់";
                    } else if($candidate->result == "Reserve"){
                        $result = "ធ្លាក់";
                    } else if ($candidate->result == "Pass"){
                        $result= "ជាប់";
                    } else {
                        $result = $candidate->result;
                    }
                    $row = array($index,$candidate->name_kh,$candidate->gender,
                        Carbon::createFromFormat('Y-m-d H:i:s',$candidate->dob)->format("d/m/Y"),$candidate->highschool,$candidate->origin,$candidate->bac_year,
                        $candidate->math_grade,$candidate->phys_grade,$candidate->chem_grade,
                        $result
                    );

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

    public function export_candidate_result_list ($exam_id) {

        $candidatesResults = $this->getCandidateResult($exam_id);
        $candiateResult=[];

        if($candidatesResults) {
            foreach($candidatesResults as $key => $candidates) {
                foreach($candidates as $candidate) {
                    $element = array(
                        'ឈ្មោះ ខ្មែរ'             => $candidate->name_kh,
                        'ឈ្មោះ ឡាតាំង'            => $candidate->name_latin,
                        'ភេទ'                      => $candidate->gender,
                        'លទ្ទផល'                  => $candidate->result,
                        'ពិន្ទុសរុប'                  => $candidate->total_score
                    );
                    $candiateResult[$key][] = $element;
                }
            }
        }


        $fields= ['លេខបង្កាន់ដៃ', 'ឈ្មោះ ខ្មែរ', 'ឈ្មោះ ឡាតាំង', 'ភេទ', 'ថ្ងៃខែរឆ្នាំកំនើត'];
        $title = 'បញ្ជីលទ្ទផលរបស់បេក្ខជន';
        $alpha = [];
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        Excel::create('បញ្ជីលទ្ទផលបេក្ខជន', function($excel) use ($candiateResult, $title,$alpha,$fields) {

            foreach($candiateResult as $key => $value) {
                $excel->sheet($key, function($sheet) use($value,$title,$alpha,$fields) {
                    $sheet->fromArray($value);
                });
            }



        })->export('xls');

    }

    private function sortCandidateRank($a, $b)
    {
        return $b->total_score -$a->total_score;
    }

    private function updateCandidateResultScore($candidateId,$totalScore, $status) {

        $candidate = Candidate::where('id',$candidateId )->get();

        $updateCandidateScore = DB::table('candidates')
            ->where([
                ['id', '=', $candidateId ],
                ['candidates.active', '=', true]
            ])
            ->update(array(
                'total_score' => $totalScore,
                'result' => $status,
            ));

        if($updateCandidateScore) {
            //UserLog
            $this->exams->getUserLog($candidate,$model='Candidate', $action='Update');
        }

        return $updateCandidateScore;
    }


    public function printCandidateErrorScore($examId, Request $request) {

        $errors = $this->exams->getErrorScore($examId,$request->get('course_id'));
        $courseName = $request->get('course_name');

        return view('backend.exam.print.candidate_score_error', compact('errors', 'courseName'));
    }



    public function generate_room(GenerateRoomExamRequest $request, $exam_id){ // In candidate section

        $exam = $this->exams->findOrThrowException($exam_id);
        $candidates = $exam->candidates()->where('active',true)->orderBy('register_id')->get()->toArray();
        $rooms = $exam->rooms()->orderBy('building_id')->orderBy('name')->get()->toArray();

        shuffle($rooms);
        //dd($rooms);
        $available_seat = 0;
        foreach($rooms as &$room){
            $room['current_seat'] = 0;
            $available_seat = $available_seat + $room['nb_chair_exam'];
        }

        if(count($candidates) > $available_seat){
            return Response::json(array('status'=>'false','message'=>'There is not enough seat for candidate!'));
        }

        $current_room = 0;
        foreach($candidates as &$candidate){
            $this->update_room_candidate($rooms,$current_room,$candidate);
        }

        foreach($candidates as $can){
            DB::table('candidates')
                ->where('id', $can['id'])
                ->update(['room_id' => $can['room_id']]);
        }

        return Response::json(array('status'=>'true','message'=>'Operation is successful!'));
    }

    function update_room_candidate(&$rooms, &$current_room, &$candidate){
        if($rooms[$current_room]['current_seat'] < $rooms[$current_room]['nb_chair_exam']){
            $candidate['room_id'] = $rooms[$current_room]['id'];
            $rooms[$current_room]['current_seat']++;
            $current_room++;
            if($current_room>=count($rooms)) $current_room = 0;
            return true;
        } else {
            $current_room++;
            if($current_room>=count($rooms)) $current_room = 0; // Reset index to 0 if over max
            $this->update_room_candidate($rooms,$current_room,$candidate);
        }
    }

    public function check_missing_candidates($exam_id){
        $candidate_register_ids = Candidate::where('exam_id',$exam_id)->orderBy('register_id', 'ASC')->lists('register_id')->toArray();
        if(count($candidate_register_ids)>0){
            $missing = array_diff(range(1, max($candidate_register_ids)), $candidate_register_ids);

            if(count($missing)>0){
                return Response::json(array('status'=>true)); // There are some missing
            } else {
                return Response::json(array('status'=>false));
            }
        } else {
            return Response::json(array('status'=>false)); // Nothing is missing
        }
    }

    public function find_missing_candidates($exam_id){
        $candidate_register_ids = Candidate::where('exam_id',$exam_id)->orderBy('register_id', 'ASC')->lists('register_id')->toArray();
        $missing = array_diff(range(1, max($candidate_register_ids)), $candidate_register_ids);

        return view('backend.exam.includes.popup_view_missing_candidate', compact('missing'));
    }


    public function formGenerateScores($examId) {

        $departments = $this->getAllDepartments();

        return view('backend.exam.includes.form_generate_DUT_score', compact('examId', 'departments'));
    }



    private function isAvalaibleDept($arrayNumberOfCandInEachDept, $deptId, $studentRate, $findsum) {

        $totalSelectionCands =0;
          if($findsum != null) { // calculation of the total selection of number of student
              foreach( $arrayNumberOfCandInEachDept as $key => $value) {
                  $totalSelectionCands = $totalSelectionCands + (int)$value;
              }
              return $totalSelectionCands;
          }

    }

    public function generateCandidateDUTResultTest($examId, Request $request) {



        $arrayCandidateInEachDept = $request->number_candidate;
        $test = [];
        $count =0;
        //$totalCands = $this->isAvalaibleDept($arrayCandidateInEachDept, null, null, $findsum = 'true');

        $dUTCandidates = $this->getAllDUTCandidates($examId); // List of all canidate order by bac percentile

        if($dUTCandidates) {

            $this->resetCandidateDUTResult();// reset all first then make an update

            foreach($dUTCandidates as $dUTCandidate) {

                   $count++;

                //if($count <= $totalCands) {
                    $statusRank =1;
                    $candidateDepts = $this->getCandidateDept($dUTCandidate->candidate_id); // List of all chosen department order by rank
                    foreach($candidateDepts as $candidateDept) {// loop candidate department option from the 1 choice to the end 1->8

                        //if($candidateDept->rank == $statusRank) {

                            foreach($arrayCandidateInEachDept as $index => $value) { // index: ID of department, value: Number of success student

                                if((int)$candidateDept->department_id == (int)$index) {

                                    $numberStudent = (int)$value;

                                    if($numberStudent > 0) {
//                                        $test[] = array('index='.$index, 'value='.$value, 'dept='.$candidateDept->department_id, 'rank='.$statusRank, 'cand_id='.$dUTCandidate->candidate_id);

                                        $numberStudent = $numberStudent -1;
                                        $arrayCandidateInEachDept[$index] = $numberStudent;

                                        $update = $this-> updateCandiateDepartment($dUTCandidate->candidate_id, $candidateDept->department_id,$candidateDept->rank, $result='Pass');

                                        if($update) {
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


//            dd($test);
            //return view list of candidate who pass with selected department and student whow reserve with selected department options

            return Response::json(['status'=>true]);
        }

        return Response::json(['status'=>false]);

    }

    public function generateCandidateDUTResult($examId, Request $request) {

//        dd($request->department);

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

    public function getDUTCandidateResultLists ($examId) {

        return  view('backend.exam.includes.examination_DUT_candidate_result', compact('examId'));
    }

    public function getDUTCandidateResultListTypes ($examId, Request $request) {

//        dd($request->type);
        $resultType = $request->type;

        if($resultType == 'Pass') {
            $title = 'Successfully Passed';
            $allStudentByDept=[];
            $candidateDUTs = $this->getSucceedCandidateDUTFromDB($examId, $is_success='Pass');

            return  view('backend.exam.includes.patial_result_candidate_dut', compact('allStudentByDept','candidateDUTs', 'examId', 'title'));

        } else if($resultType == 'Reserve') {
            $title = 'Reserve';
            $allStudentByDept=[];
            $candidateDUTs = $this->getSucceedCandidateDUTFromDB($examId, $is_success='Reserve');
            return  view('backend.exam.includes.patial_result_candidate_dut', compact('allStudentByDept','candidateDUTs', 'examId', 'title'));


        } else if($resultType == 'pass_by_dept') {
            $candidateDUTs=[];
            $studentByDept = $this->arrayStudentPassOrReserveByDept($examId, $is_success='Pass');
            $allStudentByDept = $studentByDept[1];

            return  view('backend.exam.includes.patial_result_candidate_dut', compact('allStudentByDept','candidateDUTs', 'examId'));

        } else {
            $candidateDUTs=[];

            $studentByDept = $this->arrayStudentPassOrReserveByDept($examId, $is_success='Reserve');
            $allStudentByDept = $studentByDept[1];

            return  view('backend.exam.includes.patial_result_candidate_dut', compact('allStudentByDept','candidateDUTs', 'examId'));
        }
    }

    public function printCandidateDUTResult($examId, Request $request) {

        $resultType = $request->status;

        if($resultType == 'Pass') {
            $title = 'បញ្ជីបេក្ខជនជាប់ស្ថាពរ';
            $allStudentByDept=[];
            $candidateDUTs = $this->getSucceedCandidateDUTFromDB($examId, $is_success='Pass');
            $candidateDUTs = array_chunk($candidateDUTs, 27);
            return  view('backend.exam.print.print_examination_DUT_candidate_result', compact('allStudentByDept', 'candidateDUTs', 'title'));

        } else if($resultType == 'Reserve') {
            $title = 'បញ្ជីបេក្ខជនជាប់បំរុង';
            $allStudentByDept=[];
            $candidateDUTs = $this->getSucceedCandidateDUTFromDB($examId, $is_success='Reserve');
            $candidateDUTs = array_chunk($candidateDUTs, 27);
            return  view('backend.exam.print.print_examination_DUT_candidate_result', compact('allStudentByDept', 'candidateDUTs', 'title'));


        } else if($resultType == 'pass_by_dept') {

            $title = 'បញ្ជីបេក្ខជនជាប់ស្ថាពរ';
//            $allDepts = $this->getAllDepartments();
            $candidateDUTs=[];
            $studentByDept = $this->arrayStudentPassOrReserveByDept($examId, $is_success='Pass');
            $allDepts = $studentByDept[0];
            $allStudentByDept = $studentByDept[1];

            return  view('backend.exam.print.print_examination_DUT_candidate_result', compact('allStudentByDept', 'candidateDUTs', 'title', 'allDepts'));

        } else {
            $title = 'បញ្ជីបេក្ខជនជាប់បំរុង';
            $candidateDUTs=[];
            $studentByDept = $this->arrayStudentPassOrReserveByDept($examId, $is_success='Reserve');
            $allDepts = $studentByDept[0];
            $allStudentByDept = $studentByDept[1];
            return  view('backend.exam.print.print_examination_DUT_candidate_result', compact('allStudentByDept', 'candidateDUTs', 'title','allDepts'));
        }
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

    private function resetCandidateDUTResult() { // set the field is_success to false

        $res = DB::table('candidate_department')

            ->update(array(
                'is_success' => null
            ));
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

    private function getAllDepartments() {

        $dept = DB::table('departments')
            ->select('departments.id as department_id', 'departments.name_en as department_name', 'departments.code as name_abr')
            ->where([
                ['departments.active', '=', true],
                ['is_specialist', '=', true],
                ['parent_id', '=', 11]
            ])
            ->get();

        return $dept;
    }

    private function getCandidateDept($candidateId) {

        $candidateDept = DB::table('candidate_department')
            ->where('candidate_department.candidate_id', '=', $candidateId)
            ->select('candidate_department.department_id', 'candidate_department.rank')
            ->orderBy('rank', 'ASC')
            ->get();

        return $candidateDept;
    }


    private function getAllDUTCandidates($examId) {

        $dUTCandidates = DB::table('candidates')
            ->select('candidates.id as candidate_id', 'candidates.bac_percentile', 'candidates.name_latin')
            ->where('candidates.exam_id', '=', $examId)
            ->orderBy('bac_percentile', 'DESC')
            ->get();

        return $dUTCandidates;
    }

    private function getSucceedCandidateDUTFromDB($examId, $is_success) {

         if($is_success != null) {
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
                 ->select('origins.name_kh as province_name', 'academicYears.name_kh as academic_year', 'candidates.register_id','candidates.dob as birth_date', 'candidates.register_from as home_town', 'genders.name_kh as gender', 'candidates.id as candidate_id', 'candidates.name_kh', 'candidates.name_latin', 'candidate_department.is_success', 'candidate_department.rank', 'departments.code as department_name', 'departments.id as department_id', 'candidates.bac_percentile')
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
                 ->select('origins.name_kh as province_name', 'academicYears.name_kh as academic_year', 'candidates.register_id','candidates.dob as birth_date', 'candidates.register_from as home_town', 'genders.name_kh as gender', 'candidates.id as candidate_id', 'candidates.name_kh', 'candidates.name_latin', 'candidate_department.is_success', 'candidate_department.rank', 'departments.code as department_name', 'departments.id as department_id', 'candidates.bac_percentile')
                 ->orderBy('register_id', 'ASC')
                 ->get();

             return $dUTCandidates;
         }

    }

    private function getPassOrReserveByDept($examId, $deptId, $is_success) {


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
            ->select('origins.name_kh as province_name', 'academicYears.name_kh as academic_year', 'candidates.register_id','candidates.dob as birth_date', 'candidates.register_from as home_town', 'genders.name_kh as gender', 'candidates.id as candidate_id', 'candidates.name_kh', 'candidates.name_latin', 'candidate_department.is_success', 'candidate_department.rank', 'departments.code as department_name', 'departments.id as department_id', 'candidates.bac_percentile')
            ->orderBy('register_id', 'ASC')
            ->get();

        return $dUTCandidates;
    }

    private function arrayStudentPassOrReserveByDept($examId, $is_success) {

        $uniqueDept =[];
        $allStudentByDept = [];
        $allDepts = $this->getAllDepartments();
        if($allDepts) {
            foreach($allDepts as $allDept) {

                $studentPassedByDept = $this->getPassOrReserveByDept($examId, $allDept->department_id, $is_success);

                if($studentPassedByDept) {
                    $uniqueDept[] = $allDept->name_abr;
                    $allStudentByDept[$allDept->name_abr] = $studentPassedByDept;
                }
            }

            return array($uniqueDept, $allStudentByDept);
        }
    }

}
