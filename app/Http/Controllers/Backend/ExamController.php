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
use App\Models\ExamRoom;
use App\Models\ExamType;
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
        $exam_rooms = $exam->rooms()->with(['building'])->orderBy('building_id')->orderBy('name')->get();
        $buildings = Building::lists('name','id');

        $roles = $this->employeeExams->getRoles();

        foreach($roles as $role) {}
        return view('backend.exam.show',compact('exam','type','academicYear','examType', 'roles','usable_room_exam','exam_rooms','buildings'));
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

        $exam_rooms = $exam->rooms()->with(['building'])->orderBy('building_id')->orderBy('name')->get();
        return view('backend.exam.includes.exam_room_list',compact('exam_rooms'));
    }

    public function merge_rooms(ModifyExamRoomRequest $request, $id){

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

        $exam_rooms = $exam->rooms()->with(['building'])->orderBy('building_id')->orderBy('name')->get();
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

        $exam_rooms = $exam->rooms()->with(['building'])->get();
        return view('backend.exam.includes.exam_room_list',compact('exam_rooms'));
    }

//    public function save_rooms($id){
//        $exam = $this->exams->findOrThrowException($id);
//
//        $room_ids = json_decode($_POST['room_ids']);
//        $ids = [];
//        foreach($room_ids as $room_id){
//            $tmp = explode('_',$room_id);
//            if($tmp[0] == "room"){  // Because ids that are pass alongs include buildings as well. We need to remove that.
//                array_push($ids,$tmp[1]);
//            }
//        }
//
//        if($exam->rooms()->sync($ids,false)) {  // Add room ids without deleting old ids
//            return Response::json(array("success"=>true));
//        } else {
//            return Response::json(array("success"=>false));
//        }
//
//    }

    public function delete_rooms(ModifyExamRoomRequest $request, $id){
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

    public function count_assigned_seat($id){
        $rooms = DB::table('candidates')
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

        $rooms = $exam->rooms()->with('building')->get()->toArray();

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

        $rooms = $exam->rooms()->with('building')->get()->toArray();

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

    public function download_attendance_list(DownloadExaminationDocumentsRequest $request, $exam_id){

        $exam = $this->exams->findOrThrowException($exam_id);
        $courses = $exam->entranceExamCourses()->get();
        $rooms = $exam->rooms()->with('building')->get();

        return view('backend.exam.print.attendance_list',compact('rooms','courses'));
    }

    public function download_candidate_list(DownloadExaminationDocumentsRequest $request,$exam_id){

        $exam = $this->exams->findOrThrowException($exam_id);
        $rooms = $exam->rooms()->with('building')->get();

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
        $rooms = $exam->rooms()->with('building')->get();

        return view('backend.exam.print.room_sticker',compact('rooms'));
    }

    public function download_correction_sheet(DownloadExaminationDocumentsRequest $request,$exam_id){
        $exam = $this->exams->findOrThrowException($exam_id);
        $courses = $exam->entranceExamCourses()->get();
        $rooms = $exam->rooms()->with('building')->with('candidates')->with('candidates.gender')->get();


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
            ->leftJoin('gdeGrades as physGrade','candidates.bac_phys_grade','=','candidates.id')
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
        $chunk_candidates = array_chunk($candidates,30);

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

        $chunk_candidates = array_chunk($candidates,37);

        //dd($chunk_candidates);
        return view('backend.exam.print.candidate_list_ing',compact('chunk_candidates'));
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


    public function getAllRooms($exam_id, Request $request) {

        $rooms = [];
        $roomFromDB = $this->getRoomsFromDB();
        if($roomFromDB) {
            foreach ($roomFromDB as $room) {
                $rooms[$room->room_id] = Crypt::decrypt($room->room_code);
            }
            asort($rooms);
            return view('backend.exam.includes.partial_selection_room_course', compact('rooms'))->render();

        }
    }

    private function getRoomsFromDB() {

        $roomFromDB = DB::table('examRooms')
            ->select('examRooms.roomcode as room_code', 'examRooms.id as room_id')
            ->WhereNotNull('examRooms.roomcode')
            ->get();

//        dd($roomFromDB);

        return $roomFromDB;

    }


    public function requestRoomCourseSelection ($exam_id, Request $request) {

        $correction = $request->number_correction;
        $subjectId = $request->entrance_course_id;
        $rooms = [];

        if($correction != null) {

            $roomCodes = DB::table('examRooms')
                ->select('examRooms.roomcode as room_code', 'examRooms.id as room_id')
                ->orderBy('room_code', 'ASC')->get();

            if($roomCodes) {

                foreach($roomCodes as $roomCode) {

                    $check =0;
                    $numberOfCandidateInEachRoom = DB::table('candidates')
                        ->where([
                            ['candidates.room_id', $roomCode->room_id],
                            ['candidates.active', '=', true]
                        ])
                        ->select('candidates.id as candidate_id')
                        ->get();

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

                            $rooms[$roomCode->room_id]=Crypt::decrypt($roomCode->room_code);
                        }
                    }
                }

                asort($rooms);

                return view('backend.exam.includes.partial_selection_room_course', compact('rooms'))->render();
            } else {

                return 'There are not Selected Room For Exam';
            }
        }
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
        $allRooms = $this->getRoomsFromDB();
        $roomForSelection = [];

        if($allRooms) {

            foreach ($allRooms as $room) {
                $rooms[] = (object)['room_id' => $room->room_id, 'room_code' =>Crypt::decrypt($room->room_code)];
                $roomForSelection[$room->room_id] = Crypt::decrypt($room->room_code);
            }
            asort($roomForSelection);
            usort($rooms, array($this, "sortRoomCodes"));
        }

        for($index =0; $index< count($rooms); $index++) {

            if($rooms[$index]->room_id == $roomId) {
                if($index == count($rooms)-1) {
                    $nextRoom = ['room_id'=>$rooms[0]->room_id, 'room_code'=> $rooms[0]->room_code];
                } else {
                    $nextRoom = ['room_id'=>$rooms[$index+1]->room_id, 'room_code'=> $rooms[$index+1]->room_code];
                }
                if($index == 0) {
                    $preRoom = ['room_id'=>$rooms[count($rooms)-1]->room_id, 'room_code'=> $rooms[count($rooms)-1]->room_code];
                } else {
                    $preRoom = ['room_id'=>$rooms[$index-1]->room_id, 'room_code'=> $rooms[$index-1]->room_code];
                }
            }
        }

        if( $number_correction !== 0) {

            $candidates = $this->exams->requestInputScoreForm($exam_id, $request, $number_correction);

            if($candidates) {
                $status = true;
            } else {
                $status = false;
            }

            return view('backend.exam.includes.form_input_score_candidates',compact('status', 'candidates','exam_id','roomCode', 'subject','number_correction', 'subjectId', 'roomId', 'nextRoom', 'preRoom', 'roomForSelection'));

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
        $errorCandidateScores = $this->exams->reportErrorCandidateExamScores($exam_id, $request->course_id);
        $totalQuestions = DB::table('entranceExamCourses')
            ->where([
                ['entranceExamCourses.active', '=', true],
                ['entranceExamCourses.id', '=',$courseId ]
            ])
            ->select('total_question', 'name_en')
            ->first();

        $totalQuestion = $totalQuestions->total_question;
        $courseName = $totalQuestions->name_en;


        return view('backend.exam.includes.popup_report_error_score_candidate', compact('exam_id', 'errorCandidateScores', 'totalQuestion', 'courseId', 'courseName'));

    }

    public function addNewCorrectionScore($exam_id, Requests\Backend\Exam\StoreEntranceExamScoreRequest $request) {

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
            ->select('id as course_id', 'name_en as course_name')->get();

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
        $checkScore = $this->checkEntranceExamScores($examId, $courseIds);

        if($checkScore[0]== count($courseIds)) {
             return Response::json(['status'=> false]);
        } else{
            return Response::json(['status'=> true]);
        }

    }

    public function calculateCandidateScores($examId, Request $request) {

        $requestData = $_POST;
        $arrayResults = [];
        $candidateResult = [];
        $ids = [];
        $candidateCompletedScoreIds = [];
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
                        ['candidates.active', '=', true],
                        ['candidateEntranceExamScores.is_completed', '=', true]
                    ])
                    ->select('candidateEntranceExamScores.entrance_exam_course_id','candidates.id as candidate_id','candidates.name_kh', 'candidateEntranceExamScores.score_c', 'candidateEntranceExamScores.score_w', 'candidateEntranceExamScores.score_na')
                    ->get();

//                dd($candidateScores);

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
                        $candidateCompletedScoreIds[] =  $candidateScore->candidate_id;
                    }
                }

            }
        }
        $candidateCompletedScoreIds = array_unique($candidateCompletedScoreIds);
        $candidateIds = [];
        foreach($candidateCompletedScoreIds as $id) {
            array_push($candidateIds, $id);
        }

        $nonExamingCandidateIds = DB::table('candidates')
            ->select('candidates.id as candidate_id')
            ->whereNotIn('candidates.id', $candidateIds)
            ->where('candidates.active', '=', true)
            ->get();

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

                if($candidateResult[$passedCandidates-1]->total_score == $candidateResult[$key]->total_score) {
                    $statusStudentPassed++;
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

        // this where to update candidate score base on passed or reserved

        if($passedCandidates == count($candidateResult)) {

            for($index =0; $index < $passedCandidates; $index++) {
                $pass = $this->updateCandidateResultScore($candidateResult[$index]->candidate_id,$candidateResult[$index]->total_score, 'Pass' );
                if($pass) {
                    $checkPass++;
                }
            }
            foreach($nonExamingCandidateIds as $nonExamingCandidateId) {
                $fail = $this->updateCandidateResultScore($nonExamingCandidateId->candidate_id,null, 'Reject' );
            }
        } else {
            if($passedCandidates + $reservedCandidates > count($candidateResult)) {
            return Response::json(array('status'=>false,'message'=>'There are not enough candidates!'));
//                dd($passedCandidates + $reservedCandidates);

            } else {
                for($index =0; $index < $passedCandidates; $index++) {
                    $pass = $this->updateCandidateResultScore($candidateResult[$index]->candidate_id,$candidateResult[$index]->total_score, 'Pass' );
                    if($pass) {
                        $checkPass++;
                    }
                }
                for($index=$passedCandidates; $index < count($candidateResult); $index++) {

                    $reserve = $this->updateCandidateResultScore($candidateResult[$index]->candidate_id,$candidateResult[$index]->total_score, 'Reserve' );
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

                foreach($nonExamingCandidateIds as $nonExamingCandidateId) {
                    $fail = $this->updateCandidateResultScore($nonExamingCandidateId->candidate_id,null, 'Reject' );
                }
            }
        }

        if($checkPass != 0 || $checkReserve != 0 || $checkFail != 0) {

            return Response::json(['status' => true, 'exam_id' => $examId, 'each_subject_result' => $arrayResults]);
        }
    }

    public function candidateResultLists(Request $request) {

        $examId = $request->exam_id;
        $candidatesResults = $this->getCandidateResult();

        return view('backend.exam.includes.examination_candidates_result', compact('candidatesResults', 'examId'));
    }

    private function getCandidateResult() {

        $studentPassed = DB::table('candidates')
            ->where([
                ['candidates.result', '=', 'Pass'],
                ['candidates.active', '=', true]

            ])
            ->select('name_kh','name_latin', 'result','total_score', 'id')
            ->get();

        $studentReserved = DB::table('candidates')
            ->where([
                ['candidates.result', '=', 'Reserve'],
                ['candidates.active', '=', true]
            ])
            ->select('name_kh','name_latin', 'result','total_score', 'id')
            ->get();

        $studentFail = DB::table('candidates')
            ->where([
                ['candidates.result', '=', 'Fail'],
                ['candidates.active', '=', true]
        ])
            ->select('name_kh','name_latin', 'result','total_score', 'id')
            ->get();

        $studentReject = DB::table('candidates')
            ->where([
                ['candidates.result', '=', 'Reject'],
                ['candidates.active', '=', true]
            ])
            ->select('name_kh','name_latin', 'result','total_score', 'id')
            ->get();

        usort($studentPassed, array($this, "sortCandidateRank"));
        usort($studentReserved, array($this, "sortCandidateRank"));
        usort($studentFail, array($this, "sortCandidateRank"));


        $candidateTmp1 =  array_merge((array) $studentPassed, (array) $studentReserved);
        $candidateTmp2 = array_merge((array) $studentFail, (array) $studentReject);

        $candidateResults = array_merge((array) $candidateTmp1, (array) $candidateTmp2);


        return $candidateResults;


    }

    public function printCandidateResultLists(Request $request) {

        if($request->status == 'request_print_page') {
            return Response::json(['status'=> true]);

        } else {

            $candidatesResults = $this->getCandidateResult();

            if($candidatesResults) {
                $status = true;
                $candidatesResults = array_chunk($candidatesResults, 30);
                return view('backend.exam.print.examination_candidates_result', compact('candidatesResults', 'status'));
            } else {
                $status = false;
                return view('backend.exam.print.examination_candidates_result', compact('status'));
            }


        }
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


        $roomCodes = [];
        $tmp =0;
        $arraySplitPages =[];
        $pages = [];
        $orderInRoom = explode(',',$request->order_in_room );
        $room_Code = explode(',', $request->room_code_ids);
        $courseName = $request->course_name;
        $tmpArray = array_unique($room_Code);
        asort($tmpArray);
        foreach ($tmpArray as $val) {
            $roomCodes[] = $val;
        }

        for($key = 0; $key <count($roomCodes); $key++) {

            $status_object1= 0;
            $object1=[];

            for($j = 0; $j <count($room_Code); $j++) {
                if($room_Code[$j] == $roomCodes[$key]) {
                    $status_object1++;
                    $object1[$roomCodes[$key]][] = $orderInRoom[$j];

                }
            }

            if($status_object1 < 20) {

                $tmp = $tmp + $status_object1;

                if($tmp < 20) {

                    $pages = $pages + $object1;
                    if($key == count($roomCodes)-1) {
                        $arraySplitPages[] =  $pages ;
                    }
                } else if($tmp == 20) {
                    $pages = $pages + $object1;
                    $arraySplitPages[] = $pages;
                    $pages=[];
                    $tmp =0;
                } else {
                    $arraySplitPages[] = $object1;
                    $tmp = $tmp - $status_object1;

                    if($key == count($roomCodes)-1) {
                        $arraySplitPages[] =  $pages ;
                    }
                }
            } else {

                $arraySplitPages[] = $object1;

                if($key == count($roomCodes)-1) {
                    $arraySplitPages[] =  $pages ;
                }
            }
        }


        return view('backend.exam.print.candidate_score_error', compact('arraySplitPages', 'courseName'));
    }



    public function generate_room(GenerateRoomExamRequest $request, $exam_id){ // In candidate section

        $candidates = Candidate::orderBy('register_id')->get();

        $yes = 0;
        $no = 0;
        foreach($candidates as $candidate)
        {
            $studentbac2 = StudentBac2::where('id',$candidate->studentBac2_id)->first();
            if($studentbac2!=null){
                Candidate::where('id', $candidate->id)
                    ->update(['highschool_id' => $studentbac2->highschool_id.""]);
                $yes++;
            } else {
                $no++;
            }
        }

        dd("success:".$yes." yes -".$no." no");

        $exam = $this->exams->findOrThrowException($exam_id);
        $candidates = $exam->candidates()->where('active',true)->orderBy('register_id')->get()->toArray();
        $rooms = $exam->rooms()->get()->toArray();

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
        $numberStudent =0;
        $selectedDepartment=[];
        $check =0;



          if($findsum != null) { // calculation of the total selection of number of student
              foreach( $arrayNumberOfCandInEachDept as $key => $value) {
                  $totalSelectionCands = $totalSelectionCands + (int)$value;
              }
              return $totalSelectionCands;
          }

//    if($findsum) {
//        if($deptId == 2) {
//            $val = (int)$arrayNumberOfCandInEachDept[$deptId];
//            if($val > 0) {
//                $val = $val -1;
//                $arrayNumberOfCandInEachDept[$deptId] = $val;
//            }
//        }
//
//        return $arrayNumberOfCandInEachDept;
//    }


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


//            dd($arrayCandidateInEachDept);
            //return view list of candidate who pass with selected department and student whow reserve with selected department options

            return Response::json(['status'=>true]);
        }

        return Response::json(['status'=>false]);
    }

    public function getDUTCandidateResultLists ($examId) {
        $candidateDUTs = [];
//        $passedCandDUTs = $this->getSucceedCandidateDUTFromDB($examId, $is_success='Pass');
//        $reservedCandDUTs = $this->getSucceedCandidateDUTFromDB($examId, $is_success='Reserve');
//
//        $failedCandDUTs = $this->getSucceedCandidateDUTFromDB($examId, $is_success=null);
//
//        $candidateDUTs = (object) array_merge((array) $passedCandDUTs, (array) $reservedCandDUTs, (array)$failedCandDUTs);

        return  view('backend.exam.includes.examination_DUT_candidate_result', compact('examId'));
    }

    public function getDUTCandidateResultListTypes ($examId, Request $request) {

        dd($request->type);

        $passedCandDUTs = $this->getSucceedCandidateDUTFromDB($examId, $is_success='Pass');
        $reservedCandDUTs = $this->getSucceedCandidateDUTFromDB($examId, $is_success='Reserve');

        $failedCandDUTs = $this->getSucceedCandidateDUTFromDB($examId, $is_success=null);



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
                 ->where([
                     ['candidate_department.is_success', '=', $is_success],
                     ['candidates.exam_id', '=', $examId],
                 ])
                 ->select('candidates.register_id','candidates.dob as birth_date', 'candidates.register_from as home_town', 'genders.name_kh as gender', 'candidates.id as candidate_id', 'candidates.name_kh', 'candidates.name_latin', 'candidate_department.is_success', 'candidate_department.rank', 'departments.code as department_name', 'departments.id as department_id', 'candidates.bac_percentile')
                 ->orderBy('register_id', 'ASC')
                 ->get();

             return $dUTCandidates;

         } else {

             $dUTCandidates = DB::table('candidates')
                 ->join('candidate_department', 'candidates.id', '=', 'candidate_department.candidate_id')
                 ->join('departments', 'departments.id', '=', 'candidate_department.department_id')
                 ->join('genders', 'genders.id', '=', 'candidates.gender_id')
                 ->where([
                     ['candidates.exam_id', '=', $examId],
                     ['candidates.result', '=', 'Fail']
                 ])
                 ->select('candidates.register_id','candidates.dob as birth_date', 'candidates.register_from as home_town', 'genders.name_kh as gender', 'candidates.id as candidate_id', 'candidates.name_kh', 'candidates.name_latin', 'candidate_department.is_success', 'candidate_department.rank', 'departments.code as department_name', 'departments.id as department_id', 'candidates.bac_percentile')
                 ->orderBy('register_id', 'ASC')
                 ->get();

             return $dUTCandidates;
         }

    }

    private function getReservedCandidateDUTFromDB($examId, $arrayPassedCands, $deptId) {


        $dUTCandidates = DB::table('candidates')
            ->join('candidate_department', 'candidates.id', '=', 'candidate_department.candidate_id')
            ->join('departments', 'departments.id', '=', 'candidate_department.department_id')
            ->where([
                ['candidates.exam_id', '=', $examId],
                ['departments.id', '=', $deptId]
            ])
            ->whereNotIn('candidates.id', $arrayPassedCands)
            ->select('candidates.register_id', 'candidates.id as candidate_id', 'candidates.name_kh', 'candidates.name_latin', 'candidates.result', 'candidate_department.rank', 'departments.code as department_name', 'departments.id as department_id', 'candidates.bac_percentile')
            ->orderBy('candidates.bac_percentile', 'DESC')
            ->get();

        return $dUTCandidates;
    }



}
