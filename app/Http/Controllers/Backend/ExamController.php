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

    public function get_buildings($id, $type){
        $data = array(
            array()
        );
        if($type == "selected"){
            $selected_building = DB::table('rooms')
                ->select('buildings.name','buildings.id')
                ->where('rooms.id','exam_room.room_id')
                ->where('exam_room.exam_id',$id)
                ->join('exam_room','rooms.id','=','exam_room.room_id')
                ->join('buildings','rooms.building_id','=','buildings.id')
                ->get();
        } else if($type == "available"){

        }
        /*$buildings = array(
            array(
                "id"=>"building_a",
                "text" => "Building A",
                "children"=>true,
                "type"=>"building"
            ),
            array(
                "id"=>"building_b",
                "text" => "Building B",
                "children"=>true,
                "type"=>"building"
            )
        );*/

        return Response::json($data);
    }

    public function get_rooms($id,$type){
        $rooms = array(
            array(
                "id"=>"207_b",
                "text" => "207B",
                "type"=>"room"
            ),
            array(
                "id"=>"208_b",
                "text" => "208B",
                "type" => "room",
            )
        );
        return Response::json($rooms);
    }

}
