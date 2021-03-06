<?php namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateStudentEvaStatusAPIRequest;
use App\Http\Requests\API\UpdateStudentEvaStatusAPIRequest;
use App\Models\StudentEvaStatus;
use App\Repositories\StudentEvaStatusRepository;
use Illuminate\Http\Request;
use InfyOm\Generator\Controller\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use InfyOm\Generator\Utils\ResponseUtil;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\StudentAnnual;

/**
 * Class StudentEvaStatusAPIController
 * @package App\Http\Controllers\API
 */
class StudentEvaStatusAPIController extends AppBaseController
{
    /** @var  StudentEvaStatusRepository */
    private $studentEvaStatusRepository;

    /**
     * StudentEvaStatusAPIController constructor.
     * @param StudentEvaStatusRepository $studentEvaStatusRepo
     */
    public function __construct(StudentEvaStatusRepository $studentEvaStatusRepo)
    {
        $this->studentEvaStatusRepository = $studentEvaStatusRepo;
    }

    /**
     * Display a listing of the StudentEvaStatus.
     * GET|HEAD /studentEvaStatuses
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->studentEvaStatusRepository->pushCriteria(new RequestCriteria($request));
        $this->studentEvaStatusRepository->pushCriteria(new LimitOffsetCriteria($request));
        $studentEvaStatuses = $this->studentEvaStatusRepository->all();

        return $this->sendResponse($studentEvaStatuses->toArray(), 'StudentEvaStatuses retrieved successfully');
    }

    public function attache(Request $request){

        if($request->has("stuId") & $request->has("evalId")){
            $studentAnnual = StudentAnnual::find($request["stuId"]);
            $olsstatus = $studentAnnual->evalStatus()->get();
            foreach($olsstatus as $olsstatu){
                $studentAnnual->evalStatus()->detach($olsstatu);
            }
            $studentAnnual->evalStatus()->attach($request["evalId"]);
        }
        return 1;
    }


    /**
     * Store a newly created StudentEvaStatus in storage.
     * POST /studentEvaStatuses
     *
     * @param CreateStudentEvaStatusAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateStudentEvaStatusAPIRequest $request)
    {
        $input = $request->all();

        $studentEvaStatuses = $this->studentEvaStatusRepository->create($input);

        return $this->sendResponse($studentEvaStatuses->toArray(), 'StudentEvaStatus saved successfully');
    }

    /**
     * Display the specified StudentEvaStatus.
     * GET|HEAD /studentEvaStatuses/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var StudentEvaStatus $studentEvaStatus */
        $studentEvaStatus = $this->studentEvaStatusRepository->find($id);

        if (empty($studentEvaStatus)) {
            return Response::json(ResponseUtil::makeError('StudentEvaStatus not found'), 400);
        }

        return $this->sendResponse($studentEvaStatus->toArray(), 'StudentEvaStatus retrieved successfully');
    }

    /**
     * Update the specified StudentEvaStatus in storage.
     * PUT/PATCH /studentEvaStatuses/{id}
     *
     * @param  int $id
     * @param UpdateStudentEvaStatusAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateStudentEvaStatusAPIRequest $request)
    {
        $input = $request->all();

        /** @var StudentEvaStatus $studentEvaStatus */
        $studentEvaStatus = $this->studentEvaStatusRepository->find($id);

        if (empty($studentEvaStatus)) {
            return Response::json(ResponseUtil::makeError('StudentEvaStatus not found'), 400);
        }

        $studentEvaStatus = $this->studentEvaStatusRepository->update($input, $id);

        return $this->sendResponse($studentEvaStatus->toArray(), 'StudentEvaStatus updated successfully');
    }

    /**
     * Remove the specified StudentEvaStatus from storage.
     * DELETE /studentEvaStatuses/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var StudentEvaStatus $studentEvaStatus */
        $studentEvaStatus = $this->studentEvaStatusRepository->find($id);

        if (empty($studentEvaStatus)) {
            return Response::json(ResponseUtil::makeError('StudentEvaStatus not found'), 400);
        }

        $studentEvaStatus->delete();

        return $this->sendResponse($id, 'StudentEvaStatus deleted successfully');
    }
}
