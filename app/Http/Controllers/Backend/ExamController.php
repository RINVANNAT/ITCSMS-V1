<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Exam\CreateExamRequest;
use App\Http\Requests\Backend\Exam\DeleteExamRequest;
use App\Http\Requests\Backend\Exam\EditExamRequest;
use App\Http\Requests\Backend\Exam\StoreExamRequest;
use App\Http\Requests\Backend\Exam\UpdateExamRequest;
use App\Models\AcademicYear;
use App\Models\Building;
use App\Models\Department;
use App\Models\Exam;
use App\Models\ExamType;
use App\Repositories\Backend\Exam\ExamRepositoryContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ExamController extends Controller
{
    /**
     * @var ExamRepositoryContract
     */
    protected $exams;

    /**
     * @param ExamRepositoryContract $examRepo
     */
    public function __construct(
        ExamRepositoryContract $examRepo
    )
    {
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
        return view('backend.exam.show',compact('exam','type','academicYear','examType'));
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

        //dd($course->get());
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

}
