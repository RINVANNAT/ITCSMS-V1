<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAcademicYearAPIRequest;
use App\Http\Requests\API\UpdateAcademicYearAPIRequest;
use App\Models\AcademicYear;
use App\Repositories\AcademicYearRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Controller\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use InfyOm\Generator\Utils\ResponseUtil;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class AcademicYearController
 * @package App\Http\Controllers\API
 */

class AcademicYearAPIController extends AppBaseController
{
    /** @var  AcademicYearRepository */
    private $academicYearRepository;

    public function __construct(AcademicYearRepository $academicYearRepo)
    {
        $this->academicYearRepository = $academicYearRepo;
    }

    /**
     * Display a listing of the AcademicYear.
     * GET|HEAD /academicYears
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->academicYearRepository->pushCriteria(new RequestCriteria($request));
        $this->academicYearRepository->pushCriteria(new LimitOffsetCriteria($request));
        $academicYears = $this->academicYearRepository->all();
        return $this->sendResponse($academicYears->toArray(), 'AcademicYears retrieved successfully');
    }

    /**
     * Store a newly created AcademicYear in storage.
     * POST /academicYears
     *
     * @param CreateAcademicYearAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateAcademicYearAPIRequest $request)
    {
        $input = $request->all();

        $academicYears = $this->academicYearRepository->create($input);

        return $this->sendResponse($academicYears->toArray(), 'AcademicYear saved successfully');
    }

    /**
     * Display the specified AcademicYear.
     * GET|HEAD /academicYears/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var AcademicYear $academicYear */
        $academicYear = $this->academicYearRepository->find($id);

        if (empty($academicYear)) {
            return Response::json(ResponseUtil::makeError('AcademicYear not found'), 400);
        }

        return $this->sendResponse($academicYear->toArray(), 'AcademicYear retrieved successfully');
    }

    /**
     * Update the specified AcademicYear in storage.
     * PUT/PATCH /academicYears/{id}
     *
     * @param  int $id
     * @param UpdateAcademicYearAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAcademicYearAPIRequest $request)
    {
        $input = $request->all();

        /** @var AcademicYear $academicYear */
        $academicYear = $this->academicYearRepository->find($id);

        if (empty($academicYear)) {
            return Response::json(ResponseUtil::makeError('AcademicYear not found'), 400);
        }

        $academicYear = $this->academicYearRepository->update($input, $id);

        return $this->sendResponse($academicYear->toArray(), 'AcademicYear updated successfully');
    }

    /**
     * Remove the specified AcademicYear from storage.
     * DELETE /academicYears/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var AcademicYear $academicYear */
        $academicYear = $this->academicYearRepository->find($id);

        if (empty($academicYear)) {
            return Response::json(ResponseUtil::makeError('AcademicYear not found'), 400);
        }

        $academicYear->delete();

        return $this->sendResponse($id, 'AcademicYear deleted successfully');
    }
}
