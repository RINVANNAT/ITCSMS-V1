<?php namespace App\Http\Controllers\Backend\Score;

use App\Http\Requests;
use App\Http\Requests\CreateScoreRequest;
use App\Http\Requests\UpdateScoreRequest;
use App\Models\Score;
use App\Repositories\Backend\Score\ScoreRepository;
use App\Models\Absence;
use App\Models\UserLog;
use Debugbar;
use Flash;
use Response;

use App\Http\Controllers\Controller;
use App\Repositories\Backend\Score\AbsenceRepository51;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use InfyOm\Generator\Controller\AppBaseController;
use Illuminate\Support\Facades\Auth;


class ScoreController extends AppBaseController
{

	/** @var  ScoreRepository */
	private $scoreRepository;

	function __construct(ScoreRepository $scoreRepo)
	{
        //$this->middleware('auth');
		$this->scoreRepository = $scoreRepo;
	}

	/**
	 * Display a listing of the Score.
	 *
	 * @return Response
	 */
	public function index()
	{

		$scores = $this->scoreRepository->paginate(10);
		return view('scores.index')
			->with('scores', $scores);
	}

	public function ranking(Request $request )
	{
		//test if score of course exist
        $config = array(
            "absencestotalconfig"=>array(

            ),
        );


		if ($request->has("fillter")){
            $fillterdata= json_decode($request["fillterdata"],true);

            $result =  $this->scoreRepository->getScores($fillterdata);
            $courseAnnuals = $result["courseAnnuals"];

            if(count($courseAnnuals)==0){
                Flash::error('No course, Please create course in group before view ranking');
            }
            $studentAnnuals = $result["studentAnnuals"];
            $scoresindex = $result["scoresindex"];
            $scores = $result["scores"];
            $scoresDataViews =  $result["scoresDataViews"];
			$absencesCounts = $result["absencesCounts"];
            $evalStatus = $result["evalStatus"];
			//dd($scoresDataViews);

			if($request->has("redirect")){
				return view('backend.score.classement', compact( "evalStatus","absencesCounts","courseAnnuals","studentAnnuals","scoresindex", "scores", "scoresDataViews"));
			}else{
				return view('backend.score.classement_table', compact( "evalStatus","absencesCounts","courseAnnuals","studentAnnuals","scoresindex", "scores", "scoresDataViews"));
			}


		}else{

		}
		//$result =  $this->scoreRepository->getScores($request);
		//$courseAnnuals = $result["courseAnnuals"];
		//$studentAnnuals = $result["studentAnnuals"];
		$studentAnnuals = collect([]);
		//$scoresindex = $result["scoresindex"];
		//$scores = $result["scores"];
		//$scoresDataViews =  $result["scoresDataViews"];


		return view('backend.score.classement', compact("studentAnnuals"));
	}

	public function indexReExam(Request $request )
	{

		//test if score of course exist

		// filler students: select only students that need to be take exam again.
		// filler courses: select only courses that student need to take exam.
        //  //TODO LATER sdfsdf
        // todonex
        // todonext
        //
		$config = array(
				"absencestotalconfig"=>array(
				),
		);
		if ($request->has("fillter")){
			$fillterdata= json_decode($request["fillterdata"],true);
			$result =  $this->scoreRepository->getReexam($fillterdata);
			$courseAnnuals = $result["courseAnnuals"];
			$studentAnnuals = $result["studentAnnuals"];
			$scoresindex = $result["scoresindex"];
			$scores = $result["scores"];
			$scoresDataViews =  $result["scoresDataViews"];
			$absencesCounts = $result["absencesCounts"];
            $evalStatus = $result["evalStatus"];

			return view('scores.reexam_table', compact("absencesCounts","courseAnnuals","studentAnnuals","scoresindex", "scores", "scoresDataViews", "evalStatus"));
		}else{

		}

		$result =  $this->scoreRepository->getReexam($request);
		$courseAnnuals = $result["courseAnnuals"];
		$studentAnnuals = $result["studentAnnuals"];
		$scoresindex = $result["scoresindex"];
		$scores = $result["scores"];
		$scoresDataViews =  $result["scoresDataViews"];

		return view('scores.reexam', compact("absencesCounts","courseAnnuals","studentAnnuals","scoresindex", "scores", "scoresDataViews"));
	}




	public function input( Request $request ){

		dd(Auth::id());
        
		if ($request->has("filter")){
			$fillterdata= json_decode($request["filter"],true);
			$results = $this->scoreRepository->getScoresbyCourse($fillterdata);
			if($request->has("redirect")){
				return view('backend.score.score_edit_by_course', $results);
			}else{
				return view('backend.score.score_edit_by_course_table', $results);
			}
		}
        $user_id = Auth::id();
        $studentAnnuals = collect([]);
		
		return view('backend.score.score_edit_by_course', compact("studentAnnuals","scoresindex", "scores", "user_id"));
	}

	public function gen( Request $request ){

		$scores = Score::all();
		foreach ($scores as $score ){
			$score->score10 = rand(0, 10);
			$score->score30 = rand(0, 30);
			$score->score60 = rand(0, 60);
			$score->save();
		}
		return "ok";
	}

	public  function  updateMany(Request $request){

		if ($request->isMethod('patch')) {


                foreach($request["ids"] as $key=>$id ){
					$key = intval($key);
					$id = intval($id);
                    // find record for update
					$score = $this->scoreRepository->find($id);
                    if(empty($score))
                    {
                        Flash::error('Score not found');
                        return redirect(route('scores.index'));
                    }
                    $dataScore = array(
                        "id"=>$id,
                        "student_annual_id"=>$request['student_annual_ids'][$key],
                        "score10"=>$request['score10'][$key],
                        "score30"=>$request['score30'][$key],
                        "score60"=>$request['score60'][$key],

                    );
                    if( !empty($request['reexam'][$key]) ){
                        $dataScore["reexam"]=$request['reexam'][$key];
                    }
                    $score = $this->scoreRepository->update($dataScore, $id);
                    $this->createAbsence($request["abs"][$key],$request['student_annual_ids'][$key],json_decode($request["filter"],true));
                }

			Flash::success('Score was updated');

			return redirect(route('score.input')."?redirect=1&filter=".$request["filter"]);
		}
	}

    public function  createAbsence($number,$studentId,$param)
    {
        $absence_on = "2015/02/02 07:00";


        $degree_id= (int) $param["degree_id"];
        $grade_id= (int) $param["grade_id"];
        $department_id = (int) $param["department_id"];
        $course_annual_id = (int) $param["course_annual_id"];

        if($param !=null && array_key_exists("academic_year_id", $param)){
            $academic_year_id = $param["academic_year_id"];
        }else{
            $academic_year_id = 2016;
        }

        if($param !=null && array_key_exists("semester_id", $param)){
            $semester_id = $param["semester_id"];
        }else{
            $semester_id=1;
        }

        $oldAbsences = Absence::query()
            ->where("degree_id",$degree_id)
            ->where('grade_id', $grade_id)
            ->where('department_id',$department_id)
            ->where('academic_year_id',$academic_year_id)
            ->where('course_annual_id',$course_annual_id)
            ->where("student_annual_id",$studentId)->get();



        foreach($oldAbsences as $oldAbsence)
        {
            $oldAbsence->delete();
        }

        $oldAbsences = Absence::query()
            ->where('degree_id',$degree_id)
            ->where('grade_id', $grade_id)
            ->where('department_id',$department_id)
            ->where('academic_year_id',$academic_year_id)
            ->where('course_annual_id',$course_annual_id)
            ->where("student_annual_id",$studentId)->get();



        $absenc = array("degree_id"=>$degree_id,
            "semester_id"=>$semester_id,
            "grade_id"=>$grade_id,
            "department_id"=>$department_id,
            "academic_year_id"=>$academic_year_id,
            "course_annual_id"=>$course_annual_id,
            "student_annual_id"=>intval($studentId),
            "absence_on"=>"2015/02/02 07:00");



        for ($x = 1; $x <= intval($number); $x++) {
            Absence::create($absenc);
        }

        return 1;
    }


	/**
	 * Show the form for creating a new Score.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('scores.create');
	}

	/**
	 * Store a newly created Score in storage.
	 *
	 * @param CreateScoreRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateScoreRequest $request)
	{
		$input = $request->all();

		$score = $this->scoreRepository->create($input);

		Flash::success('Score saved successfully.');

		return redirect(route('scores.index'));
	}

	/**
	 * Display the specified Score.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{

		$score = $this->scoreRepository->find($id);

		if(empty($score))
		{
			Flash::error('Score not found');

			return redirect(route('scores.index'));
		}

		return view('scores.show')->with('score', $score);
	}

	/**
	 * Show the form for editing the specified Score.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		$score = $this->scoreRepository->find($id);

		if(empty($score))
		{
			Flash::error('Score not found');

			return redirect(route('scores.index'));
		}

		return view('scores.edit')->with('score', $score);
	}

	/**
	 * Update the specified Score in storage.
	 *
	 * @param  int              $id
	 * @param UpdateScoreRequest $request
	 *
	 * @return Response
	 */
	public function update($id, UpdateScoreRequest $request)
	{
		$score = $this->scoreRepository->find($id);

		if(empty($score))
		{
			Flash::error('Score not found');

			return redirect(route('scores.index'));
		}

		$score = $this->scoreRepository->updateRich($request->all(), $id);

		Flash::success('Score updated successfully.');

		return redirect(route('scores.index'));
	}

	/**
	 * Remove the specified Score from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$score = $this->scoreRepository->find($id);

		if(empty($score))
		{
			Flash::error('Score not found');

			return redirect(route('scores.index'));
		}

		$this->scoreRepository->delete($id);

		Flash::success('Score deleted successfully.');

		return redirect(route('scores.index'));
	}

	public function import(Request $request)
	{
		if ($request->isMethod('get')) {
			return view('scores.upload');
		}else if ($request->isMethod('post')) {
			$file = $request->file('file');
			if (($handle = fopen($file,'r')) !== FALSE)
			{
				if (($header = fgetcsv($handle, 1000, ',')) !==FALSE){
				}
				while (($data = fgetcsv($handle, 1000, ',')) !==FALSE)
				{
					$absence = array();

					foreach ($header as $oldkey => $value){

						if ($value=="degree_id"){
							$absence["degree_id"] = $data[$oldkey] ;
						}else if ($value=="grade_id"){
							$absence["grade_id"] = $data[$oldkey] ;
						}else if ($value=="department_id"){
							$absence["department_id"] = $data[$oldkey] ;
						}else if ($value=="academic_year_id"){
							$absence["academic_year_id"] = $data[$oldkey] ;
						}else if ($value=="course_annual_id"){
							$absence["course_annual_id"] = $data[$oldkey] ;
						}else if ($value=="student_annual_id"){
							$absence["student_annual_id"] = $data[$oldkey] ;
						}else if ($value=="semester_id"){
							$absence["semester_id"] = $data[$oldkey] ;
						}

						if ( $value=="absences"){
							$absence["absNumber"] = $data[$oldkey];
						}else{
							$data[$value] = $data[$oldkey];
						}


						$data['create_uid'] = Auth::id();
						unset($data[$oldkey]);
					}

					if (array_key_exists('absNumber', $absence)) {
						$absence["absence_on"] =  "2015/02/02 07:00";
						$absence["semester_id"] = "1";
						$xx = $absence["absNumber"];
						unset($absence["absNumber"]);
						for ($x = 1; $x <= $xx; $x++) {
							Absence::create($absence);
						}
					}
					$this->scoreRepository->create($data);
				}
				fclose($handle);
			}
			UserLog::log([
				'model' => 'Scores',
				'action'      => 'Import',
				'data'     => 'none', // if it is create action, store only the new id.
				'developer'   => Auth::id() == 1?true:false
			]);
			Flash::success('Courses Import successfully.');
			return redirect(route('scores.index')."/classement");
		}
	}
}

//test commit from windows 

