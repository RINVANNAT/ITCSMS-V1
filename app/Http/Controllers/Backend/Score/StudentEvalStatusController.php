<?php namespace App\Http\Controllers\Backend\Score;

use App\Http\Requests;
use App\Http\Requests\CreateStudentEvalStatuRequest;
use App\Http\Requests\UpdateStudentEvalStatuRequest;
use App\Repositories\Backend\Score\StudentEvalStatusRepository;
use Flash;
use Response;
use InfyOm\Generator\Controller\AppBaseController;


class StudentEvalStatusController extends AppBaseController
{

	/** @var  StudentEvalStatusRepository */
	private $studentEvalStatusRepository;

	function __construct(StudentEvalStatusRepository $studentEvalStatusRepo)
	{
		$this->studentEvalStatusRepository = $studentEvalStatusRepo;
	}

	/**
	 * Display a listing of the StudentEvalStatu.
	 *
	 * @return Response
	 */
	public function index()
	{

		$studentEvalStatus = $this->studentEvalStatusRepository->paginate(10);





		return view('studentEvalStatuses.index')
			->with('studentEvalStatus', $studentEvalStatus);


	}


	/**
	 * Show the form for creating a new StudentEvalStatu.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('studentEvalStatuses.create');
	}

	/**
	 * Store a newly created StudentEvalStatu in storage.
	 *
	 * @param CreateStudentEvalStatuRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateStudentEvalStatuRequest $request)
	{
		$input = $request->all();

		$studentEvalStatu = $this->studentEvalStatusRepository->create($input);

		Flash::success('StudentEvalStatu saved successfully.');

		return redirect(route('studentEvalStatuses.index'));
	}

	/**
	 * Display the specified StudentEvalStatu.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$studentEvalStatu = $this->studentEvalStatusRepository->find($id);

		if(empty($studentEvalStatu))
		{
			Flash::error('StudentEvalStatu not found');

			return redirect(route('studentEvalStatuses.index'));
		}

		return view('studentEvalStatuses.show')->with('studentEvalStatu', $studentEvalStatu);
	}

	/**
	 * Show the form for editing the specified StudentEvalStatu.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		$studentEvalStatu = $this->studentEvalStatusRepository->find($id);

		if(empty($studentEvalStatu))
		{
			Flash::error('StudentEvalStatu not found');

			return redirect(route('studentEvalStatuses.index'));
		}

		return view('studentEvalStatuses.edit')->with('studentEvalStatu', $studentEvalStatu);
	}

	/**
	 * Update the specified StudentEvalStatu in storage.
	 *
	 * @param  int              $id
	 * @param UpdateStudentEvalStatuRequest $request
	 *
	 * @return Response
	 */
	public function update($id, UpdateStudentEvalStatuRequest $request)
	{
		$studentEvalStatu = $this->studentEvalStatusRepository->find($id);

		if(empty($studentEvalStatu))
		{
			Flash::error('StudentEvalStatu not found');

			return redirect(route('studentEvalStatuses.index'));
		}

		$this->studentEvalStatusRepository->updateRich($request->all(), $id);

		Flash::success('StudentEvalStatu updated successfully.');

		return redirect(route('studentEvalStatuses.index'));
	}

	/**
	 * Remove the specified StudentEvalStatu from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$studentEvalStatu = $this->studentEvalStatusRepository->find($id);

		if(empty($studentEvalStatu))
		{
			Flash::error('StudentEvalStatu not found');
			return redirect(route('studentEvalStatuses.index'));
		}

		$this->studentEvalStatusRepository->delete($id);

		Flash::success('StudentEvalStatu deleted successfully.');

		return redirect(route('studentEvalStatuses.index'));
	}
}
