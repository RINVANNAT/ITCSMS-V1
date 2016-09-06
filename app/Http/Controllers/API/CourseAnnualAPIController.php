<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCourseAnnualAPIRequest;
use App\Http\Requests\API\UpdateCourseAnnualAPIRequest;
use App\Models\AcademicYear;
use App\Models\CourseAnnual;
use App\Repositories\CourseAnnualRepository;

use InfyOm\Generator\Controller\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use InfyOm\Generator\Utils\ResponseUtil;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Employee;
/**
 * Class CourseAnnualController
 * @package App\Http\Controllers\API
 */

class CourseAnnualAPIController extends AppBaseController
{
    /** @var  CourseAnnualRepository */
    private $courseAnnualRepository;

    public function __construct(CourseAnnualRepository $courseAnnualRepo)
    {
        $this->courseAnnualRepository = $courseAnnualRepo;
    }

    /**
     * Display a listing of the CourseAnnual.
     * GET|HEAD /courseAnnuals
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $filters = $request->only('degree_id','grade_id','department_id','academic_year_id');
//        $filters = $request->only('degree_id','grade_id','department_id','academic_year_id','user_id');
        if ($filters["academic_year_id"] == null){
            unset($filters["academic_year_id"]);
            $filters["academic_year_id"] = AcademicYear::orderBy("id","desc")->first()->id;
            
        }

       

//        $courseAnnuals = $this->courseAnnualRepository->findWhere($filters)->with('courses');
        $courseAnnuals = DB::table('course_annuals')
            ->join('courses','course_annuals.course_id', '=', 'courses.id')
            ->select(['course_annuals.id', 'courses.name_en as name', 'course_annuals.course_id', 'course_annuals.semester_id as semester_id'])
            ->where('course_annuals.degree_id', $filters['degree_id'])
            ->where('course_annuals.grade_id', $filters['grade_id'])
            ->where('course_annuals.department_id', $filters['department_id'])
            ->where('course_annuals.academic_year_id', $filters['academic_year_id'])
        ->orderBy('course_annuals.semester_id','asc')
        ->orderBy('courses.name_en','asc');
//        if ($filters["user_id"] != null){
//            $employee = Employee::where("user_id", "=", $filters["user_id"])->first();
//            if ($employee != null){
//                $courseAnnuals->where('course_annuals.employee_id', $employee->id);
//            }
//        }
        $courseAnnuals = $courseAnnuals->get();
        foreach ($courseAnnuals as $courseAnnual){
            $courseAnnual->name = $courseAnnual->name." ".$courseAnnual->semester_id;
        }
        return $this->sendResponse($courseAnnuals, 'CourseAnnuals retrieved successfully');
    }

    /**
     * Store a newly created CourseAnnual in storage.
     * POST /courseAnnuals
     *
     * @param CreateCourseAnnualAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCourseAnnualAPIRequest $request)
    {
        $input = $request->all();
        $courseAnnuals = $this->courseAnnualRepository->create($input);
        return $this->sendResponse($courseAnnuals->toArray(), 'CourseAnnual saved successfully');
    }

    /**
     * Display the specified CourseAnnual.
     * GET|HEAD /courseAnnuals/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CourseAnnual $courseAnnual */
        $courseAnnual = $this->courseAnnualRepository->find($id);

        if (empty($courseAnnual)) {
            return Response::json(ResponseUtil::makeError('CourseAnnual not found'), 400);
        }

        return $this->sendResponse($courseAnnual->toArray(), 'CourseAnnual retrieved successfully');
    }

    /**
     * Update the specified CourseAnnual in storage.
     * PUT/PATCH /courseAnnuals/{id}
     *
     * @param  int $id
     * @param UpdateCourseAnnualAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCourseAnnualAPIRequest $request)
    {
        $input = $request->all();

        /** @var CourseAnnual $courseAnnual */
        $courseAnnual = $this->courseAnnualRepository->find($id);

        if (empty($courseAnnual)) {
            return Response::json(ResponseUtil::makeError('CourseAnnual not found'), 400);
        }

        $courseAnnual = $this->courseAnnualRepository->update($input, $id);

        return $this->sendResponse($courseAnnual->toArray(), 'CourseAnnual updated successfully');
    }

    /**
     * Remove the specified CourseAnnual from storage.
     * DELETE /courseAnnuals/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CourseAnnual $courseAnnual */
        $courseAnnual = $this->courseAnnualRepository->find($id);

        if (empty($courseAnnual)) {
            return Response::json(ResponseUtil::makeError('CourseAnnual not found'), 400);
        }

        $courseAnnual->delete();

        return $this->sendResponse($id, 'CourseAnnual deleted successfully');
    }
}
