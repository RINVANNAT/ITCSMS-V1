<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateScoreEvaluationAPIRequest;
use App\Http\Requests\API\UpdateScoreEvaluationAPIRequest;
use App\Models\ScoreEvaluation;
use App\Repositories\ScoreEvaluationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use InfyOm\Generator\Utils\ResponseUtil;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class ScoreEvaluationController
 * @package App\Http\Controllers\API
 */

class ScoreEvaluationAPIController extends AppBaseController
{
    /** @var  ScoreEvaluationRepository */
    private $scoreEvaluationRepository;

    public function __construct(ScoreEvaluationRepository $scoreEvaluationRepo)
    {
        $this->scoreEvaluationRepository = $scoreEvaluationRepo;
    }

    /**
     * Display a listing of the ScoreEvaluation.
     * GET|HEAD /scoreEvaluations
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->scoreEvaluationRepository->pushCriteria(new RequestCriteria($request));
        $this->scoreEvaluationRepository->pushCriteria(new LimitOffsetCriteria($request));
        $scoreEvaluations = $this->scoreEvaluationRepository->all();

        return $this->sendResponse($scoreEvaluations->toArray(), 'ScoreEvaluations retrieved successfully');
    }

    /**
     * Store a newly created ScoreEvaluation in storage.
     * POST /scoreEvaluations
     *
     * @param CreateScoreEvaluationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateScoreEvaluationAPIRequest $request)
    {
        $input = $request->all();

        $scoreEvaluations = $this->scoreEvaluationRepository->create($input);

        return $this->sendResponse($scoreEvaluations->toArray(), 'ScoreEvaluation saved successfully');
    }

    /**
     * Display the specified ScoreEvaluation.
     * GET|HEAD /scoreEvaluations/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ScoreEvaluation $scoreEvaluation */
        $scoreEvaluation = $this->scoreEvaluationRepository->find($id);

        if (empty($scoreEvaluation)) {
            return Response::json(ResponseUtil::makeError('ScoreEvaluation not found'), 400);
        }

        return $this->sendResponse($scoreEvaluation->toArray(), 'ScoreEvaluation retrieved successfully');
    }

    /**
     * Update the specified ScoreEvaluation in storage.
     * PUT/PATCH /scoreEvaluations/{id}
     *
     * @param  int $id
     * @param UpdateScoreEvaluationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateScoreEvaluationAPIRequest $request)
    {
        $input = $request->all();

        /** @var ScoreEvaluation $scoreEvaluation */
        $scoreEvaluation = $this->scoreEvaluationRepository->find($id);

        if (empty($scoreEvaluation)) {
            return Response::json(ResponseUtil::makeError('ScoreEvaluation not found'), 400);
        }

        $scoreEvaluation = $this->scoreEvaluationRepository->update($input, $id);

        return $this->sendResponse($scoreEvaluation->toArray(), 'ScoreEvaluation updated successfully');
    }

    /**
     * Remove the specified ScoreEvaluation from storage.
     * DELETE /scoreEvaluations/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ScoreEvaluation $scoreEvaluation */
        $scoreEvaluation = $this->scoreEvaluationRepository->find($id);

        if (empty($scoreEvaluation)) {
            return Response::json(ResponseUtil::makeError('ScoreEvaluation not found'), 400);
        }

        $scoreEvaluation->delete();

        return $this->sendResponse($id, 'ScoreEvaluation deleted successfully');
    }
}
