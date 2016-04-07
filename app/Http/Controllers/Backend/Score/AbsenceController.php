<?php namespace App\Http\Controllers\Backend\Score;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Score\AbsenceRepository51;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use InfyOm\Generator\Controller\AppBaseController;


class AbsenceController extends AppBaseController
{

	/** @var  AbsenceRepository */
	private $absenceRepository;

	function __construct(AbsenceRepository51 $absenceRepo)
	{
		$this->absenceRepository = $absenceRepo;
	}

	/**
	 * Display a listing of the Absence.
	 *
	 * @return Response
	 */
	public function index()
	{
		  return view('backend.score.absence.index');
	}

	/**
	 * Show the form for creating a new Absence.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('absences.create');
	}

    public function indexByGroup(Request $request)
    {

		if ($request->has('filter'))
		{
			$params = json_decode($request["filter"],true);

			$results = $this->absenceRepository->getAbsenceByCourse($params);
			$studentAnnuals = $results["studentAnnuales"];
			$absencesCounts = $results["absencesCounts"];
			return view('backend.score.absence.tableByGroup', compact("studentAnnuals","absencesCounts"));
		}
        $results = $this->absenceRepository->getAbsenceByCourse($request);
        $studentAnnuals = $results["studentAnnuales"];
        $absencesCounts = $results["absencesCounts"];

        return view('backend.score.absence.indexByGroup', compact("studentAnnuals","absencesCounts"));
    }

    public function input(Request $request)
    {
		if ( $request->has('filter') )
		{
			$params = json_decode($request["filter"],true);
			$results = $this->absenceRepository->getAbsenceByCourse($params);
			$studentAnnuals = $results["studentAnnuales"];
			$absencesCounts = $results["absencesCounts"];


            if ($request->has('redirect')){
                return view('backend.score.absence.editMany', compact("studentAnnuals","absencesCounts"));
            }else{
                return view('backend.score.absence.tableEditMany', compact("studentAnnuals","absencesCounts"));
            }
		}

		$test = null;
        $results = $this->absenceRepository->getAbsenceByCourse($test);
        $studentAnnuals = $results["studentAnnuales"];
        $absencesCounts = $results["absencesCounts"];

        return view('backend.score.absence.editMany', compact("studentAnnuals","absencesCounts","abse"));
    }

    public function updateMany(Request $request )
    {

		$fillter = $request["filter"];
		$params = json_decode($request["filter"],true);
		$results = $this->absenceRepository->getAbsenceByCourse($params);
		$studentAnnuals = $results["studentAnnuales"];
		$absencesCounts = $results["absencesCounts"];

        if ($request->isMethod('patch')) {
			$summitabsenceCounts = $request["absencecount"];
			$stuids = $request["stuids"];
            $summitdata = array();

			foreach ($stuids as $key => $stuId) {
                $summitdata[$stuId]["countabsent"]= $summitabsenceCounts[$key];
            }

			foreach ($absencesCounts as $key => $abs) {
				if ($absencesCounts[$key] == $summitdata[$key]["countabsent"]){
                    Debugbar::info(sprintf("no change for %d", $key));
				}else{
                    $this->absenceRepository->createAbsence($summitdata[$key]["countabsent"],$key,$params);
                    Debugbar::info(sprintf("change for %d", $key));
				}
            }
        }
        Flash::success('Absence updated successfully.');


        return redirect(route('absences.indexByGroup')."?redirect=1&filter=".$request["filter"]);
    }

	public function createMany()
	{
        $degree = 1;
        $grade = 14;
        $departement = 1;
        $courseAnnual =  1;
        $results = $this->absenceRepository->getAbsenceByCourse();
        $studentAnnuals = $results["studentAnnuals"];
        $absencesCounts = $results["absencesCounts"];
		return view('absences.createMany');
	}

	public function storeMany(CreateAbsenceRequest $request)
	{
		$input = $request->all();
		$absence = $this->absenceRepository->createMany($input);
		Flash::success('Absence saved successfully.');
		return redirect(route('absences.index'));
	}

	/**
	 * Store a newly created Absence in storage.
	 *
	 * @param CreateAbsenceRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateAbsenceRequest $request)
	{
		$input = $request->all();

		$absence = $this->absenceRepository->create($input);

		Flash::success('Absence saved successfully.');

		return redirect(route('absences.index'));
	}

	/**
	 * Display the specified Absence.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$absence = $this->absenceRepository->find($id);

		if(empty($absence))
		{
			Flash::error('Absence not found');

			return redirect(route('absences.index'));
		}

		return view('absences.show')->with('absence', $absence);
	}

	/**
	 * Show the form for editing the specified Absence.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		$absence = $this->absenceRepository->find($id);

		if(empty($absence))
		{
			Flash::error('Absence not found');

			return redirect(route('absences.index'));
		}

		return view('absences.edit')->with('absence', $absence);
	}

	/**
	 * Update the specified Absence in storage.
	 *
	 * @param  int              $id
	 * @param UpdateAbsenceRequest $request
	 *
	 * @return Response
	 */
	public function update($id, UpdateAbsenceRequest $request)
	{
		$absence = $this->absenceRepository->find($id);

		if(empty($absence))
		{
			Flash::error('Absence not found');

			return redirect(route('absences.index'));
		}

		$this->absenceRepository->updateRich($request->all(), $id);

		Flash::success('Absence updated successfully.');

		return redirect(route('absences.index'));
	}

	/**
	 * Remove the specified Absence from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$absence = $this->absenceRepository->find($id);

		if(empty($absence))
		{
			Flash::error('Absence not found');

			return redirect(route('absences.index'));
		}

		$this->absenceRepository->delete($id);

		Flash::success('Absence deleted successfully.');

		return redirect(route('absences.index'));
	}
}
