<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Http\Requests\Backend\EntranceExamCourse\EditEntranceExamCourseRequest;
use App\Http\Requests\Backend\EntranceExamCourse\StoreEntranceExamCourseRequest;
use App\Http\Requests\Backend\EntranceExamCourse\UpdateEntranceExamCourseRequest;
use App\Http\Requests\Backend\EntranceExamCourse\CreateEntranceExamCourseRequest;
use App\Http\Requests\Backend\EntranceExamCourse\DeleteEntranceExamCourseRequest;
use App\Models\Exam;
use App\Repositories\Backend\EntranceExamCourse\EntranceExamCourseRepositoryContract;
use App\Repositories\Backend\Exam\ExamRepositoryContract;

use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;


class EntranceExamCourseController extends Controller
{
    /**
     * @var EntranceExamCourseRepositoryContract
     */
    protected $entranceExamCourse;
    protected $exams;

    /**
     * @param EntranceExamCourseRepositoryContract $entranceExamCourseRepo
     * @param ExamRepositoryContract $examRepo
     */
    public function __construct(
        EntranceExamCourseRepositoryContract $entranceExamCourseRepo,
        ExamRepositoryContract $examRepo
    )
    {
        $this->entranceExamCourse = $entranceExamCourseRepo;
        $this->exams = $examRepo;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param CreateEntranceExamCourseRequest $request
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
     * @param  StoreEntranceExamCourseRequest $request
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
     * @param EditEntranceExamCourseRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditEntranceExamCourseRequest $request, $id)
    {

        $entranceExamCourse = $this->entranceExamCourse->findOrThrowException($id);
        $exam_id = $entranceExamCourse->exam_id;
        return view('backend.entranceExamCourse.edit', compact('entranceExamCourse','exam_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateEntranceExamCourseRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEntranceExamCourseRequest $request, $id)
    {
        $result = $this->entranceExamCourse->update($id, $request->all());
        if($request->ajax()){
            if($result['status']==true){
                return Response::json($result);
            } else {
                return Response::json($result,422);
            }

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteEntranceExamCourseRequest $request
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
        $entranceExamCourse = $exam->entranceExamCourses()->where('active',true);

        $datatables =  app('datatables')->of($entranceExamCourse);

        return $datatables
            ->addColumn('action', function ($item) use ($exam_id)  {
                $result = '';
                if(Auth::user()->allow('delete-entrance-exam-courses')){
                    $result = $result.' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.entranceExamCourses.destroy', $item->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
                }
                if(Auth::user()->allow('edit-entrance-exam-courses')){
                    $result = $result.' <button class="btn btn-xs btn-info btn_course_edit" data-remote="'.route('admin.entranceExamCourses.edit', $item->id) .'"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></button>';
                }
                if(!Auth::user()->allow('report-error-on-inputted-score')){
                    return $result;
                } else {
                    $errorCandidateScores = $this->exams->reportErrorCandidateExamScores($exam_id, $item->id);

                    if(!empty($errorCandidateScores)){
                        return $result.' <button class="btn btn-xs btn-danger btn-report-error" data-remote="'. $item->id .'">Report Error</button>';
                    } else {
                        return $result;
                    }
                }
            })
            ->make(true);
    }


}
