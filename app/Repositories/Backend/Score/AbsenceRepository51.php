<?php namespace App\Repositories\Backend\Score;

use App\Models\Absence;
use App\Models\AcademicYear;
use App\Models\StudentAnnual;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Debugbar;

use InfyOm\Generator\Common\BaseRepository;

class AbsenceRepository51 extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Absence::class;
    }



    /*---------------------------------------
    Old
    ----------------------------------------*/
//{
//
//    /**
//    * Configure the Model
//    *
//    **/
//    public function model()
//    {
//      return 'App\Models\Absence';
//    }
//
	public function search($input)
    {
        $query = Absence::query();

        $columns = Schema::getColumnListing('absences');
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
            $academic_year_id = 2015;
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
            $this->delete($oldAbsence->id);
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
            "student_annual_id"=>$studentId,
            "absence_on"=>"2015/02/02 07:00");

        for ($x = 1; $x <= $number; $x++) {
            $this->create($absenc);
        }

        return 1;
    }
    public function getAbsenceByCourse($param)
    {

        $degree_id= (int) $param["degree_id"];
        $grade_id= (int) $param["grade_id"];
        $department_id = (int) $param["department_id"];
        $course_annual_id = (int) $param["course_annual_id"];

        if($param !=null && array_key_exists("academic_year_id", $param)){
            $academic_year_id = $param["academic_year_id"];
        }else{
            $last_academic_year = AcademicYear::orderBy('id','desc')->first();
            $academic_year_id = $last_academic_year->id;
        }

        if($param !=null && array_key_exists("semester_id", $param)){
            $semester_id = $param["semester_id"];
        }else{
            $semester_id=1;
        }


                $absence_on = "2015/02/02 07:00";

        $studentAnnualsquery = StudentAnnual::with("student");

        $studentAnnualsquery->where('degree_id',$degree_id);
        $studentAnnualsquery->where('grade_id', $grade_id);
        $studentAnnualsquery->where('department_id',$department_id);
        $studentAnnualsquery->where('academic_year_id',$academic_year_id)
//        $studentAnnualsquery->with(array('student'=>function($query){
//            $query->orderBy('name_latin', 'ASC');
//        }));
        ->join('students', 'studentAnnuals.student_id', '=', 'students.id')
        ->orderBy('students.name_latin', 'ASC');


        $studentAnnuals = $studentAnnualsquery->get();

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


        return array("studentAnnuales"=>$studentAnnuals,"absencesCounts"=>$absencesCounts );


    }

    public function apiFindOrFail($id)
    {
        $model = $this->find($id);

        if(empty($model))
        {
            throw new HttpException(1001, "Absence not found");
        }

        return $model;
    }

    public function apiDeleteOrFail($id)
    {
        $model = $this->find($id);

        if(empty($model))
        {
            throw new HttpException(1001, "Absence not found");
        }

        return $model->delete();
    }
}
