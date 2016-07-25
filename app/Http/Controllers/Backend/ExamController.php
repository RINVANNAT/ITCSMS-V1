<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Exam\CreateEntranceExamCourseRequest;
use App\Http\Requests\Backend\Exam\CreateExamRequest;
use App\Http\Requests\Backend\Exam\DeleteExamRequest;
use App\Http\Requests\Backend\Exam\EditExamRequest;
use App\Http\Requests\Backend\Exam\StoreExamRequest;
use App\Http\Requests\Backend\Exam\UpdateExamRequest;
use App\Models\AcademicYear;
use App\Models\Building;
use App\Models\Candidate;
use App\Models\Department;
use App\Models\EntranceExamCourse;
use App\Models\Exam;
use App\Models\ExamType;
use App\Models\Room;
use App\Repositories\Backend\Exam\ExamRepositoryContract;
use App\Repositories\Backend\TempEmployeeExam\TempEmployeeExamRepositoryContract;
use Carbon\Carbon;
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function index($id)
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
        return redirect()->route('admin.exams.show',$id)->withFlashSuccess(trans('alerts.backend.generals.created'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $exam = $this->exams->findOrThrowException($id);

        $type = $exam->type->id;
   
        $academicYear = AcademicYear::where('id',$exam->academicYear->id)->lists('name_kh','id');

        $examType = ExamType::where('id',$type)->lists('name_kh','id')->toArray();

        $roles = $this->employeeExams->getRoles();

        foreach($roles as $role) {}

//        dd($roles);
        return view('backend.exam.show',compact('exam','type','academicYear','examType', 'roles'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteExamRequest $request, $id)
    {
            $this->exams->destroy($id);
        if($request->ajax()){
            return json_encode(array("success"=>true));
        } else {
            return redirect()->route('admin.exams.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data($id)
    {
        $exams = DB::table('exams')
            ->where('type_id',$id)
            ->select(['id','name','date_start','date_end','description']);

        $datatables =  app('datatables')->of($exams);


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

    public function get_entranceExamCourses($id){
        $exam = $this->exams->findOrThrowException($id);
        $entranceExamCourse = $exam->entranceExamCourses();


        $datatables =  app('datatables')->of($entranceExamCourse);

        return $datatables
            ->addColumn('action', function ($exam) {
                return ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.exams.destroy', $exam->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);

    }

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

        $room_ids = json_decode($_POST['room_ids']);
        $ids = [];
        foreach($room_ids as $room_id){
            $tmp = explode('_',$room_id);
            if($tmp[0] == "room"){  // Because ids that are pass alongs include buildings as well. We need to remove that.
                array_push($ids,$tmp[1]);
            }
        }

        if($exam->rooms()->detach($ids)) {  // Add room ids without deleting old ids
            return Response::json(array("success"=>true));
        } else {
            return Response::json(array("success"=>false));
        }

    }

    public function get_rooms($id){
        $type = $_GET['type'];
        $building = explode('_', $_GET['id'])[1];
        $data = array();
        $all_ids = $this->get_all_room_ids();
        $ids = $this->get_selected_room_ids($id);

        if($type == "available"){
            $ids = $this->get_available_room_ids($all_ids,$ids);
        }

        $rooms = DB::table('rooms')
            ->select('rooms.name','rooms.id','rooms.nb_chair_exam', 'buildings.code')
            ->where('rooms.building_id',$building)
            ->whereIN('rooms.id',$ids)
            ->join('buildings','rooms.building_id','=','buildings.id')
            ->get();

        foreach ($rooms as $room){
            $element = array(
                "id" => 'room_'.$room->id,
                "text" => $room->name.'-'.$room->code.' ('.$room->nb_chair_exam.')',
                "data" => array("chair_exam" =>$room->nb_chair_exam),
                "children"=>false,
                "type"=>"room"
            );
            array_push($data,$element);
        }
        return Response::json($data);
    }

    public function count_seat_exam($id){
        $type = $_GET['type'];
        $all_ids = $this->get_all_room_ids();
        $ids = $this->get_selected_room_ids($id);

        $seat_exam = 0;
        if($type == "available"){
            $ids = $this->get_available_room_ids($all_ids,$ids);
        }

        $rooms = DB::table('rooms')
            ->select('rooms.nb_chair_exam')
            ->whereIN('rooms.id',$ids)
            ->get();

        foreach ($rooms as $room){
            $seat_exam = $seat_exam + $room->nb_chair_exam;
        }

        return Response::json(array("seat_exam"=>$seat_exam));

    }

    private function get_all_room_ids(){
        $ids = DB::table('rooms')->where('is_exam_room',true)->lists('id');

        return $ids;
    }

    private function get_selected_room_ids($exam_id){
        $ids = DB::table('rooms')
            ->where('exam_room.exam_id',$exam_id)
            ->join('exam_room','rooms.id','=','exam_room.room_id')
            ->lists('rooms.id');

        return $ids;
    }

    private function get_available_room_ids($all_ids, $selected_ids){
        $ids = array_diff($all_ids,$selected_ids);
        return $ids;
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
            DB::table('exam_room')
                ->where('exam_id',$exam_id)
                ->where('room_id',$room->room_id)
                ->update(['roomcode'=>$room->secret_code]);
        }

        return Response::json(array('success'=>true));
    }

    public function request_add_courses($exam_id){
        $exam = $this->exams->findOrThrowException($exam_id);
        if($exam->type_id == 1){  // This ID=1 is for entrance engineer
            return view('backend.exam.includes.popup_add_course',compact('rooms','exam_id'));
        } else{
            return view('backend.exam.includes.popup_add_course',compact('rooms','exam_id'));
        }
    }

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

    public function requestInputScoreCourses($id) {

        $exam_id = $id;
        $exam = $this->exams->findOrThrowException($id);
        $courses = EntranceExamCourse::where('exam_id',$id)->get();

        $buildings = Building::get();
        foreach ($buildings as $building) {
            $firstBuildingId = $building->id;
            $rooms = $exam->rooms()->withPivot('room_id')->where('building_id', $firstBuildingId)->get();
            return view('backend.exam.includes.popup_add_input_score_course',compact('rooms','buildings','courses', 'exam_id'));
        }
    }

    public function getBuildingRequestion($id, Request $request) {

        $roomSelectedByBuilding = [];
        $rooms = DB::table('rooms')
                ->join('exam_room', 'rooms.id','=','exam_room.room_id')
                ->join('exams', 'exams.id', '=', 'exam_room.exam_id')
                ->where([
                    ['exam_room.exam_id', '=', $id],
                    ['rooms.building_id', '=', $request->building_id]
                ])
                ->select('rooms.name', 'rooms.id as room_id')->get();
        foreach($rooms as $room) {
            $element = array(
                "room_id" =>  $room->room_id,
                "room_name" => $room->name
            );
            array_push($roomSelectedByBuilding,$element);
        }
        return Response::json($roomSelectedByBuilding);
    }

    public function getRequestInputScoreForm($id, Request $request) {

        $res = $this->exams->requestInputScoreForm($id, $request);
        return $res;
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
