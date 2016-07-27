<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Http\Requests\Backend\EntranceExamCourse\EditEntranceExamCourseRequest;
use App\Http\Requests\Backend\EntranceExamCourse\StoreEntranceExamCourseRequest;
use App\Http\Requests\Backend\EntranceExamCourse\UpdateEntranceExamCourseRequest;
use App\Http\Requests\Backend\EntranceExamCourse\CreateEntranceExamCourseRequest;
use App\Http\Requests\Backend\EntranceExamCourse\DeleteEntranceExamCourseRequest;
use App\Models\Exam;
use App\Repositories\Backend\EntranceExamCourse\EntranceExamCourseRepositoryContract;

use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Response;


class EntranceExamCourseController extends Controller
{
    /**
     * @var EntranceExamCourseRepositoryContract
     */
    protected $entranceExamCourse;

    /**
     * @param EntranceExamCourseRepositoryContract $courseProgramRepo
     */
    public function __construct(
        EntranceExamCourseRepositoryContract $entranceExamCourseRepo
    )
    {
        $this->entranceExamCourse = $entranceExamCourseRepo;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param CreateCourseProgramRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateEntranceExamCourseRequest $request)
    {
        $exam_id = $request->get('exam_id');
        return view('backend.entranceExamCourse.create',compact('exam_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCourseProgramRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEntranceExamCourseRequest $request)
    {

        $input = $request->all();
        if($this->entranceExamCourse->create($input)){

            return Response::json(array('status'=>true));
        } else{
            return Response::json(array('status'=>false));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditCourseProgramRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditEntranceExamCourseRequest $request, $id)
    {
        $entranceExamCourse = $this->entranceExamCourse->findOrThrowException($id);

        return view('backend.course.courseProgram.edit', compact('entranceExamCourse'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCourseProgramRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEntranceExamCourseRequest $request, $id)
    {
        $this->entranceExamCourse->update($id, $request->all());
        return redirect()->route('admin.course.entranceExamCourse.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteCourseProgramRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteEntranceExamCourseRequest $request, $id)
    {
        $this->entranceExamCourse->destroy($id);
        if ($request->ajax()) {
            return json_encode(array('success' => 'true'));
        } else {
            return redirect()->route('admin.course.academicYears.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data(Request $request,$exam_id)
    {

        $exam = Exam::find($exam_id);
        $entranceExamCourse = $exam->entranceExamCourses();


        $datatables =  app('datatables')->of($entranceExamCourse);

        return $datatables
            ->addColumn('action', function ($item) {
                return '<a href="'.route('admin.entranceExamCourses.edit',$item->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.entranceExamCourses.destroy', $item->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }


}
