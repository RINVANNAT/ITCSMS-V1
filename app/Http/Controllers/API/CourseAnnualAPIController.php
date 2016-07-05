<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateCourseAnnualAPIRequest;
use App\Http\Requests\API\UpdateCourseAnnualAPIRequest;
use App\Models\CourseAnnual;
use App\Repositories\CourseAnnualRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Controller\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use InfyOm\Generator\Utils\ResponseUtil;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Support\Facades\DB;

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
    public function index(CreateCourseAnnualAPIRequest $request)
    {
        $this->courseAnnualRepository->pushCriteria(new RequestCriteria($request));
        $this->courseAnnualRepository->pushCriteria(new LimitOffsetCriteria($request));
        // fix filter null
        $filters = $request->only('degree_id','grade_id','department_id','academic_year_id');
        if ($filters["academic_year_id"] == null){
            unset($filters["academic_year_id"]);
        }



//        $courseAnnuals = $this->courseAnnualRepository->findWhere($filters)->with('courses');
//
//
        $courseAnnuals = DB::table('course_annuals')
            ->leftJoin('courses','course_annuals.course_id', '=', 'courses.id')
            ->where('course_annuals.degree_id', $filters['degree_id'])
            ->where('course_annuals.grade_id', $filters['grade_id'])
            ->where('course_annuals.department_id', $filters['department_id'])
            ->where('course_annuals.academic_year_id', $filters['academic_year_id'])
            ->select(
                ['course_annuals.id',
                    'courses.name_en as name',
                    'course_annuals.course_id'])->get();
//


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
