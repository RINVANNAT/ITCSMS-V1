<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDegreeAPIRequest;
use App\Http\Requests\API\UpdateDegreeAPIRequest;
use App\Models\Degree;
use App\Repositories\DegreeRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Controller\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use InfyOm\Generator\Utils\ResponseUtil;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class DegreeController
 * @package App\Http\Controllers\API
 */

class DegreeAPIController extends AppBaseController
{
    /** @var  DegreeRepository */
    private $degreeRepository;

    public function __construct(DegreeRepository $degreeRepo)
    {
        $this->degreeRepository = $degreeRepo;
    }

    /**
     * Display a listing of the Degree.
     * GET|HEAD /degrees
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->degreeRepository->pushCriteria(new RequestCriteria($request));
        $this->degreeRepository->pushCriteria(new LimitOffsetCriteria($request));
        $degrees = $this->degreeRepository->all();

        return $this->sendResponse($degrees->toArray(), 'Degrees retrieved successfully');
    }

    /**
     * Store a newly created Degree in storage.
     * POST /degrees
     *
     * @param CreateDegreeAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDegreeAPIRequest $request)
    {
        $input = $request->all();

        $degrees = $this->degreeRepository->create($input);

        return $this->sendResponse($degrees->toArray(), 'Degree saved successfully');
    }

    /**
     * Display the specified Degree.
     * GET|HEAD /degrees/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Degree $degree */
        $degree = $this->degreeRepository->find($id);

        if (empty($degree)) {
            return Response::json(ResponseUtil::makeError('Degree not found'), 400);
        }

        return $this->sendResponse($degree->toArray(), 'Degree retrieved successfully');
    }

    /**
     * Update the specified Degree in storage.
     * PUT/PATCH /degrees/{id}
     *
     * @param  int $id
     * @param UpdateDegreeAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDegreeAPIRequest $request)
    {
        $input = $request->all();

        /** @var Degree $degree */
        $degree = $this->degreeRepository->find($id);

        if (empty($degree)) {
            return Response::json(ResponseUtil::makeError('Degree not found'), 400);
        }

        $degree = $this->degreeRepository->update($input, $id);

        return $this->sendResponse($degree->toArray(), 'Degree updated successfully');
    }

    /**
     * Remove the specified Degree from storage.
     * DELETE /degrees/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Degree $degree */
        $degree = $this->degreeRepository->find($id);

        if (empty($degree)) {
            return Response::json(ResponseUtil::makeError('Degree not found'), 400);
        }

        $degree->delete();

        return $this->sendResponse($id, 'Degree deleted successfully');
    }
}
