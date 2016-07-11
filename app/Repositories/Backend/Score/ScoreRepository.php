<?php namespace App\Repositories\Backend\Score;

use App\Models\CourseAnnual;
use App\Models\Score;
use App\Models\Degree;
use App\Models\Grade;
use App\Models\Department;
use App\Models\StudentAnnual;
use App\Models\Student;
use App\Models\Absence;
use Schema;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Debugbar;
use InfyOm\Generator\Common\BaseRepository;


class ScoreRepository extends BaseRepository
{

    /**
    * Configure the Model
    *
    **/
    public function model()
    {
      return 'App\Models\Score';
    }

	public function search($input)
    {
        $query = Score::query();

        $columns = Schema::getColumnListing('scores');
        $attributes = array();

        foreach($columns as $attribute)
        {
            if(isset($input[$attribute]) and !empty($input[$attribute]))
            {
                $query->where($attribute, $input[$attribute]);
                $attributes[$attribute] = $input[$attribute];
            }
            else
            {
                $attributes[$attribute] =  null;
            }
        }

        return [$query->get(), $attributes];
    }

    public function apiFindOrFail($id)
    {
        $model = $this->find($id);
        if(empty($model))
        {
            throw new HttpException(1001, "Score not found");
        }
        return $model;
    }

    public function apiDeleteOrFail($id)
    {
        $model = $this->find($id);

        if(empty($model))
        {
            throw new HttpException(1001, "Score not found");
        }

        return $model->delete();
    }
    public function getGroups(){

    }
    public function getReexam($param){
        $degree_id= (int) $param["degree_id"];
        $grade_id= (int) $param["grade_id"];
        $department_id = (int) $param["department_id"];

        if($param !=null && array_key_exists("academic_year_id", $param)){
            $academic_year_id = $param["academic_year_id"];
        }else{
            $academic_year_id = 2015;
        }
        if($param !=null && array_key_exists("semester_id", $param)){
            $semester_id = $param["semester_id"];
        }else{
            $semester_id=1;
        }

        $studentAnnuals = StudentAnnual::with("student")->with("evalStatus")
            ->where('degree_id',$degree_id)
            ->where('grade_id', $grade_id)
            ->where('department_id',$department_id)
            ->where('academic_year_id',$academic_year_id)
            ->whereHas('evalStatus', function ($q) {
                $q->where('name', "exam again");
            })
            ->get();




        //dd($studentAnnuals);

        // get course in groups
        $courseAnnuals = CourseAnnual::with("course")
            ->where('degree_id',$degree_id)
            ->where('grade_id', $grade_id)
            ->where('department_id',$department_id)
            ->where('academic_year_id',$academic_year_id)
            ->get();


        // fetch credit by course id
        // fetch total credits of all courses to calculate moyenen
        $totalcredit =0;
        $credit_by_id = array();
        foreach($courseAnnuals as $courseAnnual){
            $credit_by_id[$courseAnnual->id] = $courseAnnual->course->credit;
            $totalcredit += $courseAnnual->course->credit;
        }

        // score
        //
        $scorequeries = Score::query()
            ->where("degree_id", $degree_id)
            ->where("grade_id", $grade_id)
            ->where("department_id", $department_id)
            ->where('academic_year_id',$academic_year_id)
            ->get();

        $scores = array();
        $scoretmp = null;

        // fetch score by student annual id
        // init moyenne ranking and status
        $scoresDataViews = array();
        foreach($scorequeries as $scorequery){
            $scoresDataViews[$scorequery->student_annual_id]["moyenne"] = 0;
            $scoresDataViews[$scorequery->student_annual_id]["ranking"] = 0;
            $scoresDataViews[$scorequery->student_annual_id]["status"] = 0;
        }
        $i = 0;
        $student_ids = array();
        foreach($scorequeries as $scorequery){
            array_push($student_ids,$scorequery->student_annual_id);
            $scores[$scorequery->student_annual_id][$scorequery->course_annual_id] =$scorequery;
            //sum score in course

            $scoresDataViews[$scorequery->student_annual_id][$scorequery->course_annual_id]["scoreTotalinCourse"] = $scorequery["score10"] +
                $scorequery["score30"] + $scorequery["score60"];
            $credit = 1;
            $scoresDataViews[$scorequery->student_annual_id]["moyenne"] +=
                $scoresDataViews[$scorequery->student_annual_id][$scorequery->course_annual_id]["scoreTotalinCourse"] * $credit_by_id[$scorequery->course_annual_id];
        }


        $student_ids = array_unique($student_ids);


        $test = array();
        foreach ($scoresDataViews as $key=>$value) {
            $scoresDataViews[$key]["moyenne"] = $scoresDataViews[$key]["moyenne"] / $totalcredit;
            array_push($test, $scoresDataViews[$key]["moyenne"]);
        }


        Debugbar::info("test");
        rsort($test);
        $test = array_unique($test);
        $test = array_values($test);
        Debugbar::info($test);

        $scoresDataViews2 = array();

        foreach ($scoresDataViews as $key=>$value) {
            $scoresDataViews[$key]["ranking"] = array_search($scoresDataViews[$key]["moyenne"], $test) +1;
        }



        $absencesCounts = array();
        foreach ($studentAnnuals as $studentAnnual) {
            $absencesCounts["totalabs"][$studentAnnual->id] = 0;
            foreach($courseAnnuals as $courseAnnual){
                $absenceCount = Absence::query()
                    ->where('degree_id',$degree_id)
                    ->where('grade_id', $grade_id)
                    ->where('department_id',$department_id)
                    ->where('academic_year_id',$academic_year_id)
                    ->where('course_annual_id',$courseAnnual->id)
                    ->where("student_annual_id",$studentAnnual->id)->get();
                $absencesCounts[$studentAnnual->id][$courseAnnual->id] = count($absenceCount);
                $absencesCounts["totalabs"] [$studentAnnual->id]+= count($absenceCount);
            }
        }



        //auto evaluation.
        $evalRulse = array(
            array(
                "param"=>"average",
                "operator"=>"getter",
                "value"=>50,
                "result"=>1),

            array(
                "param"=>"scoreincoures",
                "operator"=>"smaller",
                "value"=>30,
                "result"=>4),

            array(
                "param"=>"average",
                "operator"=>"smaller",
                "value"=>50,
                "result"=>4),

        );

        if(array_key_exists("autoeval",$param)){
            foreach($studentAnnuals as $studentAnnual){
                foreach($evalRulse as $evalRule){
                    if($evalRule["param"] == "average" and $evalRule["operator"] == "getter"){
                        if ($scoresDataViews[$studentAnnual->id]["moyenne"] > $evalRule["value"]){
                            //check if rule already exist
                            $olsstatus = $studentAnnual->evalStatus()->get();
                            foreach($olsstatus as $olsstatu){
                                $studentAnnual->evalStatus()->detach($olsstatu);
                            }
                            $studentAnnual->evalStatus()->attach($evalRule["result"]);
                        }
                    }else if ($evalRule["param"] == "average" and $evalRule["operator"] == "smaller"){
                        if ($scoresDataViews[$studentAnnual->id]["moyenne"] < $evalRule["value"]){
                            //check if rule already exist
                            $olsstatus = $studentAnnual->evalStatus()->get();
                            foreach($olsstatus as $olsstatu){
                                $studentAnnual->evalStatus()->detach($olsstatu);
                            }
                            $studentAnnual->evalStatus()->attach($evalRule["result"]);
                        }
                    }
                }

            }
        }

        $evalStatus = array();
        foreach($studentAnnuals as $studentAnnual){
            $evalStatus[$studentAnnual->id] = $studentAnnual->evalStatus()->get()->first();
        }

        return array("evalStatus"=>$evalStatus, "absencesCounts"=>$absencesCounts,"studentAnnuals"=>$studentAnnuals,"courseAnnuals"=>$courseAnnuals, "scoresindex"=>$scores, "scores"=>$scorequeries ,"scoresDataViews"=>$scoresDataViews);
    }




    public function getScores($param){
        /*---------------------------------------
        validation parameter
        ----------------------------------------*/

        $degree_id= (int) $param["degree_id"];
        $grade_id= (int) $param["grade_id"];
        $department_id = (int) $param["department_id"];

        if($param !=null && array_key_exists("academic_year_id", $param)){
            $academic_year_id = $param["academic_year_id"];
        }else{
            $academic_year_id = 2016;
        }
        if($param !=null && array_key_exists("semester_id", $param)){
            $semester_id = (int) $param["semester_id"];
        }else{
            $semester_id=3;
        }


        // get student in group
        $studentAnnuals = StudentAnnual::
            where('degree_id',$degree_id)
            ->where('grade_id', $grade_id)
            ->where('department_id',$department_id)
            ->where('academic_year_id',$academic_year_id)
            ->join('students', 'studentAnnuals.student_id', '=', 'students.id')
            ->orderBy('students.name_latin', 'ASC')
            ->get();

        // get course in groups


        if($semester_id==1){
            $courseAnnuals = CourseAnnual::with("course")
                ->where('degree_id',$degree_id)
                ->where('grade_id', $grade_id)
                ->where('department_id',$department_id)
                ->where('academic_year_id',$academic_year_id)
                ->where('semester',$semester_id)->get();
            //dd($courseAnnuals);
        }else{
            $courseAnnuals = CourseAnnual::with("course")
                ->where('degree_id',$degree_id)
                ->where('grade_id', $grade_id)
                ->where('department_id',$department_id)
                ->where('academic_year_id',$academic_year_id)->get();
        }

        // fetch credit by course id
        // fetch total credits of all courses to calculate moyenen
        $totalcredit =0;
        $credit_by_id = array();
        $courseAnualIds = array();
        foreach($courseAnnuals as $courseAnnual){
            $credit_by_id[$courseAnnual->id] = $courseAnnual->course->credit;
            $totalcredit += $courseAnnual->course->credit;
            array_push($courseAnualIds, $courseAnnual->id);
        }

        // score
        //

        $scorequeries = Score::query()
            ->where("degree_id", $degree_id)
            ->where("grade_id", $grade_id)
            ->where("department_id", $department_id)
            ->where('academic_year_id',$academic_year_id)
            ->whereIn("course_annual_id",$courseAnualIds)
            ->get();
        //dd($scorequeries);
        $scores = array();
        $scoretmp = null;

        // fetch score by student annual id
        // init moyenne ranking and status
        $scoresDataViews = array();
        foreach($scorequeries as $scorequery){
            $scoresDataViews[$scorequery->student_annual_id]["moyenne"] = 0;
            $scoresDataViews[$scorequery->student_annual_id]["ranking"] = 0;
            $scoresDataViews[$scorequery->student_annual_id]["status"] = 0;
        }
        $i = 0;
        $student_ids = array();
        foreach($scorequeries as $scorequery){
            array_push($student_ids,$scorequery->student_annual_id);
            $scores[$scorequery->student_annual_id][$scorequery->course_annual_id] =$scorequery;
            //sum score in course

            $maxscore60 =0;
            if ($scorequery->reexam > $scorequery->score60){
                $maxscore60 = $scorequery->reexam;
            }else{
                $maxscore60 = $scorequery->score60;
            }
            $scoresDataViews[$scorequery->student_annual_id][$scorequery->course_annual_id]["scoreTotalinCourse"] = $scorequery["score10"] +
                $scorequery["score30"] + $maxscore60;


            $credit = 1;
            $scoresDataViews[$scorequery->student_annual_id]["moyenne"] +=
                $scoresDataViews[$scorequery->student_annual_id][$scorequery->course_annual_id]["scoreTotalinCourse"] * $credit_by_id[$scorequery->course_annual_id];
        }


        $student_ids = array_unique($student_ids);


        $test = array();
        foreach ($scoresDataViews as $key=>$value) {
            $scoresDataViews[$key]["moyenne"] = $scoresDataViews[$key]["moyenne"] / $totalcredit;
            array_push($test, $scoresDataViews[$key]["moyenne"]);
        }



        rsort($test);
        $test = array_unique($test);
        $test = array_values($test);

        $scoresDataViews2 = array();

        foreach ($scoresDataViews as $key=>$value) {
            $scoresDataViews[$key]["ranking"] = array_search($scoresDataViews[$key]["moyenne"], $test) +1;
        }
        //--------------------------------------------
        // Hightlight Score in Course
        // Under 30, under 50,
        //--------------------------------------------
        foreach($courseAnnuals as $courseAnnual){
            foreach($studentAnnuals as $studentAnnual){
                $scoresDataViews[$studentAnnual->id][$courseAnnual->id]["highlight"]="";
                if($scoresDataViews[$studentAnnual->id][$courseAnnual->id]["scoreTotalinCourse"] < 30){
                    $scoresDataViews[$studentAnnual->id][$courseAnnual->id]["highlight"]="red";
                }else if($scoresDataViews[$studentAnnual->id][$courseAnnual->id]["scoreTotalinCourse"] < 50){
                    $scoresDataViews[$studentAnnual->id][$courseAnnual->id]["highlight"]="yellow";
                }
            }
        }
        //--------------------------------------------
        // Hightlight Classement
        // Under 30, under 50,
        //--------------------------------------------

        $i =1;
        foreach($studentAnnuals as $studentAnnual){
            $scoresDataViews[$studentAnnual->id]["moyennehighlight"] = "";
            if($scoresDataViews[$studentAnnual->id]["moyenne"] < 30){
                $scoresDataViews[$studentAnnual->id]["moyennehighlight"]="red";
            }else if($scoresDataViews[$studentAnnual->id]["moyenne"] < 50){
                $scoresDataViews[$studentAnnual->id]["moyennehighlight"]="yellow";
            }
            $studentAnnual["no"]  = $i++;

        }



        //--------------------------------------------
        // Absence Count
        //--------------------------------------------
        $absencesCounts = array();
         foreach ($studentAnnuals as $studentAnnual) {
             $absencesCounts["totalabs"][$studentAnnual->id] = 0;
             foreach($courseAnnuals as $courseAnnual){
                 $absenceCount = Absence::query()
                    ->where('degree_id',$degree_id)
                    ->where('grade_id', $grade_id)
                    ->where('department_id',$department_id)
                    ->where('academic_year_id',$academic_year_id)
                    ->where('course_annual_id',$courseAnnual->id)
                    ->where("student_annual_id",$studentAnnual->id)->get();
                 $absencesCounts[$studentAnnual->id][$courseAnnual->id] = count($absenceCount);
                 $absencesCounts["totalabs"][$studentAnnual->id]+= count($absenceCount);
             }
        }



        //auto evaluation.
        $evalRulse = array(
        array(
            "param"=>"average",
            "operator"=>"getter",
            "value"=>50,
            "result"=>1),

        array(
            "param"=>"scoreincoures",
            "operator"=>"smaller",
            "value"=>30,
            "result"=>4),

        array(
        "param"=>"average",
        "operator"=>"smaller",
        "value"=>50,
        "result"=>4),

        );

        //--------------------------------------------
        // autoEvalation
        //
        //--------------------------------------------


        if(array_key_exists("autoeval",$param)){

            foreach($studentAnnuals as $studentAnnual){

                foreach($evalRulse as $evalRule){

                    if($evalRule["param"] == "average" and $evalRule["operator"] == "getter"){


                        if ($scoresDataViews[$studentAnnual->id]["moyenne"] > $evalRule["value"]){
                            //check if rule already exist
                            $olsstatus = $studentAnnual->evalStatus()->get();
                            foreach($olsstatus as $olsstatu){
                                $studentAnnual->evalStatus()->detach($olsstatu);
                            }

                            $studentAnnual->evalStatus()->attach($evalRule["result"]);
                        }
                    }else if ($evalRule["param"] == "average" and $evalRule["operator"] == "smaller"){
                        if ($scoresDataViews[$studentAnnual->id]["moyenne"] < $evalRule["value"]){
                            //check if rule already exist
                            $olsstatus = $studentAnnual->evalStatus()->get();
                            foreach($olsstatus as $olsstatu){
                                $studentAnnual->evalStatus()->detach($olsstatu);
                            }
                            $studentAnnual->evalStatus()->attach($evalRule["result"]);
                        }
                    }
                }

            }
        }

        $evalStatus = array();
        foreach($studentAnnuals as $studentAnnual){
            $tmp = $studentAnnual->evalStatus()->get()->first();
            $evalStatus[$studentAnnual->id] = $tmp;
        }


        /*---------------------------------------
        Fetch Result for View
        ----------------------------------------*/

        $results = array();

        return array( "evalStatus"=>$evalStatus, "absencesCounts"=>$absencesCounts,"studentAnnuals"=>$studentAnnuals,"courseAnnuals"=>$courseAnnuals, "scoresindex"=>$scores, "scores"=>$scorequeries ,"scoresDataViews"=>$scoresDataViews);
    }


    public function getScoresbyCourse($param){

        $degree_id= (int) $param["degree_id"];
        $grade_id= (int) $param["grade_id"];
        $department_id = (int) $param["department_id"];
        $course_annual_id = (int) $param["course_annual_id"];
        if($param !=null && array_key_exists("semester_id", $param)){
            $semester_id = $param["semester_id"];
        }else{
            $semester_id=1;
        }
        if($param !=null && array_key_exists("academic_year_id", $param)){
            $academic_year_id = $param["academic_year_id"];
        }else{
            $academic_year_id = 2016;
        }

        $absence_on = "2015/02/02 07:00";




        $studentAnnuals = StudentAnnual::where('degree_id',$degree_id)
            ->where('grade_id', $grade_id)
            ->where('department_id',$department_id)
            ->where('academic_year_id',$academic_year_id)
            ->join('students', 'studentAnnuals.student_id', '=', 'students.id')
            ->orderBy('students.name_latin', 'ASC')
            ->get();


        //dd($studentAnnuals->toArray());
        //dd($studentAnnuals[0]["student"]["name_latin"]);
        $course = 1;

        $scorequeries = Score::query()
            ->where("degree_id", $degree_id)
            ->where("grade_id", $grade_id)
            ->where("department_id", $department_id)
            ->where('academic_year_id',$academic_year_id)
            ->where('course_annual_id',$course_annual_id)
            ->get();



        if( count($scorequeries) == 0  ){
            foreach($studentAnnuals as $studentAnnual){
                $scoretmp = array("degree_id"=>$degree_id, "grade_id"=> $grade_id, "department_id"=>$department_id, "academic_year_id"=>$academic_year_id,
                    "course_annual_id"=>$course_annual_id, "student_annual_id"=>$studentAnnual->id, "score10"=>0,"score30"=>0,"score60"=>0,"reexam"=>0,"create_uid"=>1);
                Score::create($scoretmp);

            }
            $scorequeries = Score::query()
                ->where("degree_id", $degree_id)
                ->where("grade_id", $grade_id)
                ->where("department_id", $department_id)
                ->where('academic_year_id',$academic_year_id)
                ->where('course_annual_id',$course_annual_id)
                ->get();
        }


        $scores = array();
        foreach($scorequeries as $scorequery){
            $scores[$scorequery->student_annual_id] = $scorequery;
        }

        // get student eval status

        //-----------------------
        // Get student Absence
        //-----------------------

        $absencesCounts = array();
        foreach ($studentAnnuals as $studentAnnual) {
            $absenceCount = Absence::query()
                ->where('degree_id',$degree_id)
                ->where('grade_id', $grade_id)
                ->where('department_id',$department_id)
                ->where('academic_year_id',$academic_year_id)
                ->where('course_annual_id',$course_annual_id)
                ->where("student_annual_id",$studentAnnual->id)->get();
            $absencesCounts[$studentAnnual->id]=count($absenceCount);
        }



        //-----------------------
        // Total Score sum 10+30+60
        //-----------------------\
        $total = array();

        foreach($scorequeries as $scorequery){
            $max =0;
            if ($scorequery->reexam > $scorequery->score60){
                $max = $scorequery->reexam;
            }else{
                $max = $scorequery->score60;
            }


            $total[$scorequery->student_annual_id] = $max+ $scorequery->score10 + $scorequery->score30;
        }

        $studentAnnualsFetchResult = array();
        $no = 1;
        foreach ($studentAnnuals as $studentAnnual) {
            $fetchResult = array();
            $fetchResult["no"] = $no++;
            $fetchResult["id"] = $studentAnnual->id;
            $fetchResult["id_card"] = $studentAnnual->student->id_card;
            $fetchResult["name"] = $studentAnnual->student->name_latin;
            array_push($studentAnnualsFetchResult, $fetchResult);
        }







        return compact("studentAnnuals","scores","absencesCounts","total","studentAnnualsFetchResult" );
    }


}
