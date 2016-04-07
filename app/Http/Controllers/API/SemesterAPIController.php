<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateSemesterAPIRequest;
use App\Http\Requests\API\UpdateSemesterAPIRequest;
use App\Models\Semester;
use App\Repositories\SemesterRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Controller\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use InfyOm\Generator\Utils\ResponseUtil;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class SemesterController
 * @package App\Http\Controllers\API
 */

class SemesterAPIController extends AppBaseController
{
    /** @var  SemesterRepository */
    private $semesterRepository;

    public function __construct(SemesterRepository $semesterRepo)
    {
        $this->semesterRepository = $semesterRepo;
    }

    /**
     * Display a listing of the Semester.
     * GET|HEAD /semesters
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->semesterRepository->pushCriteria(new RequestCriteria($request));
        $this->semesterRepository->pushCriteria(new LimitOffsetCriteria($request));
        $semesters = $this->semesterRepository->all();

        return $this->sendResponse($semesters->toArray(), 'Semesters retrieved successfully');
    }

    /**
     * Store a newly created Semester in storage.
     * POST /semesters
     *
     * @param CreateSemesterAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateSemesterAPIRequest $request)
    {
        $input = $request->all();

        $semesters = $this->semesterRepository->create($input);

        return $this->sendResponse($semesters->toArray(), 'Semester saved successfully');
    }

    /**
     * Display the specified Semester.
     * GET|HEAD /semesters/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Semester $semester */
        $semester = $this->semesterRepository->find($id);

        if (empty($semester)) {
            return Response::json(ResponseUtil::makeError('Semester not found'), 400);
        }

        return $this->sendResponse($semester->toArray(), 'Semester retrieved successfully');
    }

    /**
     * Update the specified Semester in storage.
     * PUT/PATCH /semesters/{id}
     *
     * @param  int $id
     * @param UpdateSemesterAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSemesterAPIRequest $request)
    {
        $input = $request->all();

        /** @var Semester $semester */
        $semester = $this->semesterRepository->find($id);

        if (empty($semester)) {
            return Response::json(ResponseUtil::makeError('Semester not found'), 400);
        }

        $semester = $this->semesterRepository->update($input, $id);

        return $this->sendResponse($semester->toArray(), 'Semester updated successfully');
    }

    /**
     * Remove the specified Semester from storage.
     * DELETE /semesters/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Semester $semester */
        $semester = $this->semesterRepository->find($id);

        if (empty($semester)) {
            return Response::json(ResponseUtil::makeError('Semester not found'), 400);
        }

        $semester->delete();

        return $this->sendResponse($id, 'Semester deleted successfully');
    }
}
