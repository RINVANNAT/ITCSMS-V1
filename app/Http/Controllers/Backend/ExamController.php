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
use App\Models\EntranceExamCourse;
use App\Models\ExamRoom;
use App\Models\ExamType;
use App\Models\Room;
use App\Repositories\Backend\Exam\ExamRepositoryContract;
use App\Repositories\Backend\TempEmployeeExam\TempEmployeeExamRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Http\Requests;

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show(ViewExamRequest $request, $type_id, $exam_id)
    {
        $exam = $this->exams->findOrThrowException($exam_id);
        $type = $exam->type->id;
        $academicYear = AcademicYear::where('id',$exam->academicYear->id)->lists('name_kh','id');
        $examType = ExamType::where('id',$type)->lists('name_kh','id')->toArray();
        $usable_room_exam = Room::where('is_exam_room',true)->count();
        $exam_rooms = $exam->rooms()->with(['building'])->get();
        $buildings = Building::lists('name','id');

        $roles = $this->employeeExams->getRoles();

        foreach($roles as $role) {}
        return view('backend.exam.show',compact('exam','type','academicYear','examType', 'roles','usable_room_exam','exam_rooms','buildings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditExamRequest $request, $id)
    {

        $exam = $this->exams->findOrThrowException($id);

        $type = $exam->type->id;
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
        $exam = $this->exams->findOrThrowException($id);
        $this->exams->destroy($id);

    }

    public function data($id)
    {
        $exams = DB::table('exams')
            ->where('type_id',$id)
            ->select(['id','type_id','name','date_start','date_end','description']);

        $datatables =  app('datatables')->of($exams);


        return $datatables
            ->editColumn('date_start',function($exam){
                return Carbon::createFromFormat('Y-m-d h:i:s',$exam->date_start)->toFormattedDateString();
            })
            ->editColumn('date_end',function($exam){
                return Carbon::createFromFormat('Y-m-d h:i:s',$exam->date_end)->toFormattedDateString();
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

    public function get_buildings($id){
        $type = $_GET['type'];
        $data = array();
        $all_ids = $this->get_all_room_ids();
        $ids = $this->get_selected_room_ids($id);

        if($type == "available"){
            $ids = $this->get_available_room_ids($all_ids,$ids);
        }

        $buildings = DB::table('rooms')
            ->select('buildings.name','buildings.id')
            ->whereIN('rooms.id',$ids)
            ->join('buildings','rooms.building_id','=','buildings.id')
            ->groupBy('buildings.id')
            ->get();

        foreach ($buildings as $building){
            $element = array(
                "id"=>'building_'.$building->id,
                "text" => $building->name,
                "children"=>true,
                "type"=>"building"
            );
            array_push($data,$element);
        }

        return Response::json($data);
    }

    public function add_room($id){

        $exam = $this->exams->findOrThrowException($id);

        $exam_room = new ExamRoom();
        $exam_room->name = $_POST['name'];
        $exam_room->building_id = $_POST['building_id'];
        $exam_room->nb_chair_exam = $_POST['nb_chair_exam'];
        $exam_room->description = $_POST['description'];
        $exam_room->exam_id = $id;
        $exam_room->create_uid = auth()->id();
        $exam_room->created_at = Carbon::now();

        $exam_room->save();

        $exam_rooms = $exam->rooms()->with(['building'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list',compact('exam_rooms'));
    }

    public function merge_rooms($id){

        $exam = $this->exams->findOrThrowException($id);
        $rooms = $_POST['rooms'];

        $room_0 = ExamRoom::find($rooms[0]);
        foreach($rooms as $room){
            ExamRoom::destroy($room);
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

        $exam_rooms = $exam->rooms()->with(['building'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list',compact('exam_rooms'));
    }

    public function split_room($id){

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

        $exam_rooms = $exam->rooms()->with(['building'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list',compact('exam_rooms'));
    }

    public function generate_rooms($id){

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

        $exam_rooms = $exam->rooms()->with(['building'])->get();
        return view('backend.exam.includes.exam_room_list',compact('exam_rooms'));
    }

    public function save_rooms($id){
        $exam = $this->exams->findOrThrowException($id);

        $room_ids = json_decode($_POST['room_ids']);
        $ids = [];
        foreach($room_ids as $room_id){
            $tmp = explode('_',$room_id);
            if($tmp[0] == "room"){  // Because ids that are pass alongs include buildings as well. We need to remove that.
                array_push($ids,$tmp[1]);
            }
        }

        if($exam->rooms()->sync($ids,false)) {  // Add room ids without deleting old ids
            return Response::json(array("success"=>true));
        } else {
            return Response::json(array("success"=>false));
        }

    }

    public function delete_rooms($id){
        $exam = $this->exams->findOrThrowException($id);

        $room_ids = $_POST['exam_room'];
        ExamRoom::destroy($room_ids);

        $exam_rooms = $exam->rooms()->with(['building'])->get();
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

    public function view_room_secret_code($exam_id){
        $exam = $this->exams->findOrThrowException($exam_id);

        $rooms = $exam->rooms()->with('building')->withPivot('roomcode')->get()->toArray();

        return view('backend.exam.includes.popup_room_secret_code',compact('rooms','exam_id'));
    }

    public function save_room_secret_code($exam_id){

        $rooms = json_decode($_POST['room_ids']);
        //dd($rooms);
        foreach($rooms as $room){
            DB::table('examRooms')
                ->where('exam_id',$exam_id)
                ->where('id',$room->room_id)
                ->update(['roomcode'=>$room->secret_code]);
        }

        return Response::json(array('success'=>true));
    }

    public function download_attendance_list($exam_id){

        $exam = $this->exams->findOrThrowException($exam_id);
        $courses = $exam->entranceExamCourses()->get();
        $rooms = $exam->rooms()->with('building')->withPivot('roomcode')->get();

        return view('backend.exam.print.attendance_list',compact('rooms','courses'));
    }

    public function download_candidate_list($exam_id){

        $exam = $this->exams->findOrThrowException($exam_id);
        $rooms = $exam->rooms()->with('building')->withPivot('roomcode')->get();

        return view('backend.exam.print.candidate_list',compact('rooms'));
    }

    public function download_candidate_list_by_register_id($exam_id){

        $exam = $this->exams->findOrThrowException($exam_id);
        $candidates = $exam->candidates()->with('gender')->with('room')->with('room.building')->orderBy('register_id')->get()->toArray();

        $chunk_candidates = array_chunk($candidates,30);

        return view('backend.exam.print.candidate_list_order_by_register_id',compact('chunk_candidates'));
    }

    public function download_room_sticker($exam_id){
        $exam = $this->exams->findOrThrowException($exam_id);
        $rooms = $exam->rooms()->with('building')->withPivot('roomcode')->get();

        return view('backend.exam.print.room_sticker',compact('rooms'));
    }

    public function download_correction_sheet($exam_id){
        $exam = $this->exams->findOrThrowException($exam_id);
        $courses = $exam->entranceExamCourses()->get();
        $rooms = $exam->rooms()->with('building')->with('candidates')->with('candidates.gender')->withPivot('roomcode')->get();


        return view('backend.exam.print.correction_sheet',compact('rooms','courses'));
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

    public function requestRoomCourseSelection ($exam_id, Request $request) {

        $correction = $request->number_correction;
        $subjectId = $request->entrance_course_id;
        $rooms = [];

        if($correction != null) {

            $roomCodes = DB::table('examRooms')
                ->select('examRooms.roomcode as room_code', 'examRooms.id as room_id')->get();

            if($roomCodes) {

                foreach($roomCodes as $roomCode) {

                    $check =0;
                    $numberOfCandidateInEachRoom = DB::table('candidates')->where('candidates.room_id', $roomCode->room_id)->select('candidates.id as candidate_id')->get();

                    if($numberOfCandidateInEachRoom) {

                        foreach($numberOfCandidateInEachRoom as $eachCandidateRoom) {

                            $sequences = DB::table('candidateEntranceExamScores')
                                ->where([
                                    ['candidateEntranceExamScores.candidate_id', $eachCandidateRoom->candidate_id],
                                    ['candidateEntranceExamScores.entrance_exam_course_id', $subjectId]
                                ])
                                ->select('candidateEntranceExamScores.sequence as number_correction')
                                ->get();

                            if($sequences) {
                                foreach($sequences as $sequence) {
                                    if($sequence->number_correction == $correction) {
                                        $check++;
                                    }
                                }
                            }
                        }
                        if($check !== count($numberOfCandidateInEachRoom)) {
                            $rooms[$roomCode->room_id]=$roomCode->room_code;
                        }
                    }
                }

                return view('backend.exam.includes.partial_selection_room_course', compact('rooms'))->render();
            } else {

                return 'There are not Selected Room For Exam';
            }
        }
    }

    public function getRequestInputScoreForm($exam_id, Request $request) {

        $subject = $request->course_name;
        $subjectId = $request->entrance_course_id;
        $roomId = $request->room_id;

        $roomCode = $request->room_code;
        $number_correction = (int)$request->number_correction;

        if( $number_correction !== 0) {

            $candidates = $this->exams->requestInputScoreForm($exam_id, $request, $number_correction);

            if($candidates) {
                $status = true;
            } else {
                $status = false;
            }

            return view('backend.exam.includes.form_input_score_candidates',compact('status', 'candidates','exam_id','roomCode', 'subject','number_correction', 'subjectId', 'roomId'));

        } else {

            return Response::json(['status' => false]);
        }

    }

    public function insertScoreForEachCandiate($exam_id, Request $request) {
//        Requests\Backend\Exam\StoreEntranceExamScoreRequest
        $requestDatas = $_POST;
//        dd($requestDatas);
        $candidates = $this->exams->insertCandidateScore($exam_id, $requestDatas);

        if($candidates['status']) {
            return Response::json(['status'=>true]);
        } else {
            return Response::json(['status'=>false]);
        }

    }

    public function reportErrorCandidateScores($exam_id, Request $request) {

        $errorCandidateScores = $this->exams->reportErrorCandidateExamScores($exam_id, $request->course_id);
        $totalQuestions = DB::table('entranceExamCourses')->select('total_question')->get();
        foreach($totalQuestions as $totalQuestion) {
            $totalQuestion = $totalQuestion->total_question;
        }

        return view('backend.exam.includes.popup_report_error_score_candidate', compact('exam_id', 'errorCandidateScores', 'totalQuestion'));



    }

    public function addNewCorrectionScore($exam_id, Requests\Backend\Exam\StoreEntranceExamScoreRequest $request) {

        $res = $this->exams->addNewCorrectionCandidateScore($exam_id, $request);
        if($res) {
            return Response::json(['status'=> true]);
        } else {
            return Response::json(['status'=> false]);
        }
    }

    public function candidateResultExamScores($exam_id) {

        $errorStatus=false;
        $courses = [];
        $courseIds = DB::table('entranceExamCourses')
                    ->where('exam_id', '=', $exam_id)
                    ->select('id as course_id', 'name_en as course_name')->get();
        if($courseIds) {
            foreach($courseIds as $courseId) {
                $errorCandidateScores = $this->exams->reportErrorCandidateExamScores($exam_id, $courseId->course_id);
                if($errorCandidateScores) {
                    $courses[]=$courseId->course_name;
                    $errorStatus=true;
                }
            }

            if($errorStatus !== false){

                return view('backend.exam.includes.error_popup_message', compact('courses'))->with(['message'=>'There is an existing score error']);


            } else{
                return view('backend.exam.includes.popup_get_form_result_score', compact('exam_id', 'courseIds'));
            }
        }

    }

    public function calculateCandidateScores($examId, Request $request) {

        $requestData = $_POST;
        $arrayResults = [];
        $candidateResult = [];
        $ids = [];
        $passedCandidates = (int)$requestData['course_factor']['total_pass'];
        $reservedCandidates = (int)$requestData['course_factor']['total_reserve'];
        $checkPass = 0;
        $checkReserve = 0;
        $checkFail = 0;

        foreach ($requestData['course_factor'] as $courseId => $factorValue) {

            if(is_numeric($courseId)) {

                // this query is to get candidate score by each subject
                // calculate only for copleted score of the candidate
                $candidateScores = DB::table('candidates')
                    ->join('exams', 'exams.id', '=', 'candidates.exam_id')
                    ->join('candidateEntranceExamScores', 'candidateEntranceExamScores.candidate_id', '=', 'candidates.id')
                    ->where([
                        ['candidates.exam_id', '=', $examId],
                        ['candidateEntranceExamScores.entrance_exam_course_id', '=', $courseId ],
                        ['candidateEntranceExamScores.is_completed', '=', true]
                    ])
                    ->select('candidateEntranceExamScores.entrance_exam_course_id','candidates.id as candidate_id','candidates.name_kh', 'candidateEntranceExamScores.score_c', 'candidateEntranceExamScores.score_w', 'candidateEntranceExamScores.score_na')
                    ->get();

                if($candidateScores) {

                    $subjectCoefficient = $requestData['course_factor']['subject_coe_'.$courseId];
                    $wrongCoefficient = $requestData['course_factor']['wrong_coe_'.$courseId];


                    foreach($candidateScores as $candidateScore) {

                        // total score foreach subject: (score_correct * factor)- (score_wrong * 1)) * coefficient

                        $totalScore = (($candidateScore->score_c * $factorValue) - ($candidateScore->score_w * $wrongCoefficient) ) * $subjectCoefficient;

                        $element = (object)array(
                            'candidate_name'    => $candidateScore->name_kh,
                            'candidate_id'      => $candidateScore->candidate_id,
                            'course_id'         => $candidateScore->entrance_exam_course_id,
                            'score_by_course'   => $totalScore
                        );
                        array_push($arrayResults, $element);
                    }
                }

            }
        }
        //this query will get candidate with completed score in all subject for the exam
        $candidateIds = DB::table('candidateEntranceExamScores')
                    ->where('is_completed', '=', true)
                    ->select('candidate_id')->get();

        foreach($candidateIds as $candidateId) {
            array_push($ids, $candidateId->candidate_id);
        }
        $ids = array_unique($ids);
        $candidateIds = [];
        foreach($ids as $id) {
            array_push($candidateIds, $id);
        }
        //this is to calculate each candidate score for all subjects == (total_math + total_physic....)
        for($i=0; $i<count($candidateIds); $i++) {
            $totalSum = 0;
            foreach($arrayResults as $arrayResult) {
                if($candidateIds[$i] == $arrayResult->candidate_id){
                    $totalSum = $totalSum + $arrayResult->score_by_course;
                }
            }
            array_push($candidateResult, (object)(['candidate_id'=> $candidateIds[$i], 'total_score' =>$totalSum]));
        }
        //arrank the candidate from high score to the lowest score
        usort($candidateResult, array($this, "sortCandidateRank"));

        if(count($candidateResult) > $passedCandidates + $reservedCandidates) {
            $statusStudentPassed = 0;
            $statusStudentReserved = 0;

            for($key = $passedCandidates; $key < count($candidateResult); $key++) {

                if($key+1 < count($candidateResult)) {

                    if($candidateResult[$passedCandidates-1]->total_score == $candidateResult[$key]->total_score) {
                        $statusStudentPassed++;
                    }
                }
            }

            for($key = $passedCandidates + $reservedCandidates + $statusStudentPassed ; $key < count($candidateResult); $key++) {

                if($candidateResult[ $passedCandidates + $reservedCandidates + $statusStudentPassed -1]->total_score == $candidateResult[$key]->total_score) {
                    $statusStudentReserved++;
                }
            }

            $passedCandidates = $passedCandidates + $statusStudentPassed;
            $reservedCandidates = $reservedCandidates + $statusStudentReserved;// -1 because we compare redandancy of the index
        }
//        dd($passedCandidates.'--'.$reservedCandidates);

        // this where to update candidate score base on passed or reserved
        $candidateIds = [];

        if($passedCandidates + $reservedCandidates > count($candidateResult)) {
            return Response::json(array('status'=>false,'message'=>'There are not enough candidates!'));

        } else {
            for($index =0; $index < $passedCandidates; $index++) {
                $pass = $this->updateCandidateResultScore($candidateResult[$index]->candidate_id,$candidateResult[$index]->total_score, 'Pass' );
                array_push($candidateIds,$candidateResult[$index]->candidate_id);
                if($pass) {
                    $checkPass++;
                }
            }
            for($index=$passedCandidates; $index < count($candidateResult); $index++) {

                $reserve = $this->updateCandidateResultScore($candidateResult[$index]->candidate_id,$candidateResult[$index]->total_score, 'Reserve' );
                array_push($candidateIds,$candidateResult[$index]->candidate_id);
                if($reserve) {
                    $checkReserve++;
                }
            }
            for($index=$passedCandidates + $reservedCandidates; $index < count($candidateResult); $index++) {

                $fail = $this->updateCandidateResultScore($candidateResult[$index]->candidate_id,$candidateResult[$index]->total_score, 'Fail' );
                array_push($candidateIds,$candidateResult[$index]->candidate_id);
                if($fail) {
                    $checkFail++;
                }
            }

            $nonExamingCandidateIds = DB::table('candidates')->select('candidates.id as candidate_id')
                ->whereNotIn('candidates.id', $candidateIds)
                ->get();

            foreach($nonExamingCandidateIds as $nonExamingCandidateId) {
                $fail = $this->updateCandidateResultScore($nonExamingCandidateId->candidate_id,0, 'Fail' );
            }
        }

        if($checkPass != 0 || $checkReserve != 0 || $checkFail != 0) {

            return Response::json(['status' => true, 'exam_id' => $examId, 'each_subject_result' => $arrayResults]);
        }
    }

    public function candidateResultLists(Request $request) {

        $examId = $request->exam_id;
        $candidatesResults = DB::table('candidates')->select('name_kh','name_latin', 'result','total_score', 'id')->get();

        usort($candidatesResults, array($this, "sortCandidateRank"));

        return view('backend.exam.includes.examination_candidates_result', compact('candidatesResults', 'examId'));
    }

    public function printCandidateResultLists(Request $request) {

        if($request->status == 'request_print_page') {
            return Response::json(['status'=> true]);

        } else {

            $candidatesResults = DB::table('candidates')->select('name_kh','name_latin', 'result','total_score', 'id')->get();

            usort($candidatesResults, array($this, "sortCandidateRank"));

            $candidatesResults = array_chunk($candidatesResults, 30);

            return view('backend.exam.print.examination_candidates_result', compact('candidatesResults'));
        }
    }

    private function sortCandidateRank($a, $b)
    {
        return $b->total_score -$a->total_score;
    }

    private function updateCandidateResultScore($candidateId,$totalScore, $status) {

        $updateCandidateScore = DB::table('candidates')
            ->where('id', '=', $candidateId )
            ->update(array(
                'total_score' => $totalScore,
                'result' => $status,
            ));

        return $updateCandidateScore;
    }



    public function generate_room($exam_id){
        $exam = $this->exams->findOrThrowException($exam_id);
        $candidates = $exam->candidates()->orderBy('register_id')->get()->toArray();
        $rooms = $exam->rooms()->get()->toArray();

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

        //dd($candidates);
        // Update candidate

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

}
