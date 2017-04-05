<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateGradeAPIRequest;
use App\Http\Requests\API\UpdateGradeAPIRequest;
use App\Models\Grade;
use App\Repositories\GradeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use InfyOm\Generator\Controller\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use InfyOm\Generator\Utils\ResponseUtil;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

use App\Http\Requests\API\Server88APIRequest;
use App\Utils\FormParamManager;
/**
 * Class GradeController
 * @package App\Http\Controllers\API
 */

class GradeAPIController extends AppBaseController
{
    /** @var  GradeRepository */
    private $gradeRepository;

    public function __construct(GradeRepository $gradeRepo)
    {
        $this->gradeRepository = $gradeRepo;
    }

    /**
     * Display a listing of the Grade.
     * GET|HEAD /grades
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->gradeRepository->pushCriteria(new RequestCriteria($request));
        $this->gradeRepository->pushCriteria(new LimitOffsetCriteria($request));
        $grades = $this->gradeRepository->all();

        return $this->sendResponse($grades->toArray(), 'Grades retrieved successfully');
    }

    /**
     * Store a newly created Grade in storage.
     * POST /grades
     *
     * @param CreateGradeAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateGradeAPIRequest $request)
    {
        $input = $request->all();

        $grades = $this->gradeRepository->create($input);

        return $this->sendResponse($grades->toArray(), 'Grade saved successfully');
    }

    /**
     * Display the specified Grade.
     * GET|HEAD /grades/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Grade $grade */
        $grade = $this->gradeRepository->find($id);

        if (empty($grade)) {
            return Response::json(ResponseUtil::makeError('Grade not found'), 400);
        }

        return $this->sendResponse($grade->toArray(), 'Grade retrieved successfully');
    }

    /**
     * Update the specified Grade in storage.
     * PUT/PATCH /grades/{id}
     *
     * @param  int $id
     * @param UpdateGradeAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateGradeAPIRequest $request)
    {
        $input = $request->all();

        /** @var Grade $grade */
        $grade = $this->gradeRepository->find($id);

        if (empty($grade)) {
            return Response::json(ResponseUtil::makeError('Grade not found'), 400);
        }

        $grade = $this->gradeRepository->update($input, $id);

        return $this->sendResponse($grade->toArray(), 'Grade updated successfully');
    }

    /**
     * Remove the specified Grade from storage.
     * DELETE /grades/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Grade $grade */
        $grade = $this->gradeRepository->find($id);

        if (empty($grade)) {
            return Response::json(ResponseUtil::makeError('Grade not found'), 400);
        }

        $grade->delete();

        return $this->sendResponse($id, 'Grade deleted successfully');
    }


    public function getAll(Server88APIRequest $request) {

       $grades = DB::table('grades')->get();

        return $grades;

    }

    public function unique(Server88APIRequest $request) {

        $param = FormParamManager::getFormParams($request);
        $grade =  DB::table('grades')->where('id', $param['grade_id'])->first();
        return (array)$grade;
    }
}
