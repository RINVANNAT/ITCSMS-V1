<?php

namespace App\Http\Controllers\Backend\StudentTrait;
use App\Models\AcademicYear;
use App\Models\Degree;
use App\Models\Department;
use App\Models\Scholarship;
use App\Models\StudentAnnual;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Created by PhpStorm.
 * User: thavorac
 * Date: 7/30/17
 * Time: 9:48 PM
 * Description: preview and print report + statistic
 */
trait ReportingTrait
{
    public function get_student_list_by_age($academic_year_id, $degree, $date,$scholarships, $semester_id){

        $grades = [1,2,3,4,5];
        $ages = array(
            ['min'=>0,'max'=>16,'name'=>'<16','data'=> array()],
            ['min'=>16,'max'=>17,'name'=>'16','data'=> array()],
            ['min'=>17,'max'=>18,'name'=>'17','data'=> array()],
            ['min'=>18,'max'=>19,'name'=>'18','data'=> array()],
            ['min'=>19,'max'=>20,'name'=>'19','data'=> array()],
            ['min'=>20,'max'=>21,'name'=>'20','data'=> array()],
            ['min'=>21,'max'=>22,'name'=>'21','data'=> array()],
            ['min'=>22,'max'=>23,'name'=>'22','data'=> array()],
            ['min'=>23,'max'=>24,'name'=>'23','data'=> array()],
            ['min'=>24,'max'=>25,'name'=>'24','data'=> array()],
            ['min'=>25,'max'=>26,'name'=>'25','data'=> array()],
            ['min'=>26,'max'=>31,'name'=>'26-30','data'=> array()],
            ['min'=>31,'max'=>40,'name'=>'31-39','data'=> array()],
            ['min'=>40,'max'=>100,'name'=>'>39','data'=> array()]
        );

        $total_scholarship = 0;
        $total_paid = 0;

        foreach($ages as &$age){
            $t_st = 0;
            $t_sf = 0;
            $t_pt = 0;
            $t_pf = 0;

            foreach($grades as $grade){
                $minDate = Carbon::createFromFormat("d/m/Y",$date)->subYears($age['max'])->startOfDay();
                $maxDate = Carbon::createFromFormat("d/m/Y",$date)->subYears($age['min'])->subDay()->endOfDay();

                $total = DB::table('studentAnnuals')
                    ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                    ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                    ->whereNull('group_student_annuals.department_id')
                    ->where('group_student_annuals.semester_id','=',$semester_id)
                    ->where('studentAnnuals.degree_id','=',$degree)
                    ->where('studentAnnuals.grade_id','=',$grade)
                    ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                    ->whereBetween('students.dob',[$minDate,$maxDate])->count();


                $total_female = DB::table('studentAnnuals')
                    ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                    ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                    ->whereNull('group_student_annuals.department_id')
                    ->where('group_student_annuals.semester_id','=',$semester_id)
                    ->where('studentAnnuals.degree_id','=',$degree)
                    ->where('studentAnnuals.grade_id','=',$grade)
                    ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                    ->whereBetween('students.dob',[$minDate,$maxDate])
                    ->where('students.gender_id','=',2)->count(); // 2 is female

                ;
                $scholarship_total =  DB::table('studentAnnuals')
                    ->join('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                    ->join('students','studentAnnuals.student_id','=','students.id')
                    ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                    ->whereNull('group_student_annuals.department_id')
                    ->where('group_student_annuals.semester_id','=',$semester_id)
                    ->where('studentAnnuals.degree_id','=',$degree)
                    ->where('studentAnnuals.grade_id','=',$grade)
                    ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                    ->whereIn('scholarship_student_annual.scholarship_id',$scholarships)
                    ->whereBetween('students.dob',[$minDate,$maxDate])
                    ->count();

                $scholarship_female =  DB::table('studentAnnuals')
                    ->join('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                    ->join('students','studentAnnuals.student_id','=','students.id')
                    ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                    ->whereNull('group_student_annuals.department_id')
                    ->where('group_student_annuals.semester_id','=',$semester_id)
                    ->where('studentAnnuals.degree_id','=',$degree)
                    ->where('studentAnnuals.grade_id','=',$grade)
                    ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                    ->whereIn('scholarship_student_annual.scholarship_id',$scholarships)
                    ->whereBetween('students.dob',[$minDate,$maxDate])
                    ->where('students.gender_id','=',2)->count(); // 2 is female

                $array = array(
                    'st' => $scholarship_total,
                    'sf' => $scholarship_female,
                    'pt' => $total-$scholarship_total,
                    'pf' => $total_female-$scholarship_female
                );

                $t_st += $array['st'];
                $t_sf += $array['sf'];
                $t_pt += $array['pt'];
                $t_pf += $array['pf'];

                array_push($age['data'],$array);

                // unset unnecessary variables

                unset($query);
                unset($minDate);
                unset($maxDate);
                unset($total);
                unset($total_female);
                unset($scholarship_total);
                unset($scholarship_female);
            }

            array_push($age['data'],array('st'=>$t_st,'sf'=>$t_sf,'pt'=>$t_pt,'pf'=>$t_pf));
            $total_scholarship = $total_scholarship+ $t_st;
            $total_paid = $total_paid + $t_pt;
        }

        //dd("total:".$total_scholarship." | scholarship:".$total_paid." | all: ".($total_paid+$total_scholarship));
        return $ages;
    }

    public function get_student_redouble($academic_year_id , $degree, $scholarships, $semester_id){
        $departments = Department::where('parent_id',11)->with(['department_options'])->get()->toArray();
        $grades = [1,2,3,4,5];
        $array_total = array(
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0)
        );
        foreach($departments as &$department) {
            $empty_option = array(
                'id'=>null,
                'name_kh'=>$department['name_kh'],
                'name_en'=>$department['name_en'],
                'name_fr'=>$department['name_fr'],
                'code'=>$department['code']
            );
            if($department['department_options'] == null || count($department['department_options']) == 0){
                $department['department_options'] = [$empty_option];
            } else {
                array_unshift($department['department_options'], $empty_option);
            }
            foreach($department['department_options'] as &$option){

                $records = array();
                $t_st = 0;
                $t_sf = 0;
                $t_pt = 0;
                $t_pf = 0;

                foreach($grades as $grade){

                    $total = DB::table('studentAnnuals')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->leftJoin('redouble_student','students.id','=','redouble_student.student_id')
                        ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                        ->whereNull('group_student_annuals.department_id')
                        ->where('group_student_annuals.semester_id','=',$semester_id)
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->where('redouble_student.academic_year_id','=',$academic_year_id)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])
                        ->where('redouble_student.redouble_id','=',$degree==2?$grade+5:$grade)->count();

                    $total_female = DB::table('studentAnnuals')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->leftJoin('redouble_student','students.id','=','redouble_student.student_id')
                        ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                        ->whereNull('group_student_annuals.department_id')
                        ->where('group_student_annuals.semester_id','=',$semester_id)
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->where('redouble_student.academic_year_id','=',$academic_year_id)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])
                        ->where('students.gender_id','=',2)
                        ->where('redouble_student.redouble_id','=',$degree==2?$grade+5:$grade)->count(); // 2 is female

                    $scholarship_total =  DB::table('studentAnnuals')
                        ->leftJoin('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->leftJoin('redouble_student','students.id','=','redouble_student.student_id')
                        ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                        ->whereNull('group_student_annuals.department_id')
                        ->where('group_student_annuals.semester_id','=',$semester_id)
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->where('redouble_student.academic_year_id','=',$academic_year_id)
                        ->whereIn('scholarship_student_annual.scholarship_id',$scholarships)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])
                        ->where('redouble_student.redouble_id','=',$degree==2?$grade+5:$grade)->count();

                    $scholarship_female =  DB::table('studentAnnuals')
                        ->leftJoin('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->leftJoin('redouble_student','students.id','=','redouble_student.student_id')
                        ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                        ->whereNull('group_student_annuals.department_id')
                        ->where('group_student_annuals.semester_id','=',$semester_id)
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->where('redouble_student.academic_year_id','=',$academic_year_id)
                        ->whereIn('scholarship_student_annual.scholarship_id',$scholarships)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])
                        ->where('students.gender_id','=',2)
                        ->where('redouble_student.redouble_id','=',$degree==2?$grade+5:$grade)->count(); // 2 is female

                    $array = array(
                        'st' => $scholarship_total,
                        'sf' => $scholarship_female,
                        'pt' => $total-$scholarship_total,
                        'pf' => $total_female-$scholarship_female
                    );

                    $t_st += $array['st'];
                    $t_sf += $array['sf'];
                    $t_pt += $array['pt'];
                    $t_pf += $array['pf'];

                    array_push($records,$array);

                    // unset unnecessary variables

                    unset($query);
                    unset($minDate);
                    unset($maxDate);
                    unset($total);
                    unset($total_female);
                    unset($scholarship_total);
                    unset($scholarship_female);

                    $array_total[$grade-1]['st'] += $array['st'];
                    $array_total[$grade-1]['sf'] += $array['sf'];
                    $array_total[$grade-1]['pt'] += $array['pt'];
                    $array_total[$grade-1]['pf'] += $array['pf'];
                }

                array_push($records,array('st'=>$t_st,'sf'=>$t_sf,'pt'=>$t_pt,'pf'=>$t_pf));
                $array_total[5]['st'] += $t_st;
                $array_total[5]['sf'] += $t_sf;
                $array_total[5]['pt'] += $t_pt;
                $array_total[5]['pf'] += $t_pf;

                $option['data'] = $records;
            }


        }
        array_push($departments,$array_total);
        return $departments;
    }

    public function get_student_radie($academic_year_id , $degree, $scholarships, $semester_id){
        $departments = Department::where('parent_id',11)->with(['department_options'])->get()->toArray();
        $grades = [1,2,3,4,5];
        $array_total = array(
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0)
        );
        foreach($departments as &$department) {
            $empty_option = array(
                'id'=>null,
                'name_kh'=>$department['name_kh'],
                'name_en'=>$department['name_en'],
                'name_fr'=>$department['name_fr'],
                'code'=>$department['code']
            );
            if($department['department_options'] == null || count($department['department_options']) == 0){
                $department['department_options'] = [$empty_option];
            } else {
                array_unshift($department['department_options'], $empty_option);
            }
            foreach($department['department_options'] as &$option){

                $records = array();
                $t_st = 0;
                $t_sf = 0;
                $t_pt = 0;
                $t_pf = 0;

                foreach($grades as $grade){

                    $total = DB::table('studentAnnuals')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                        ->whereNull('group_student_annuals.department_id')
                        ->where('students.radie','=',true)
                        ->where('group_student_annuals.semester_id','=',$semester_id)
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])->count();

                    $total_female = DB::table('studentAnnuals')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                        ->whereNull('group_student_annuals.department_id')
                        ->where('students.radie','=',true)
                        ->where('group_student_annuals.semester_id','=',$semester_id)
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])
                        ->where('students.gender_id','=',2)->count(); // 2 is female

                    $scholarship_total =  DB::table('studentAnnuals')
                        ->leftJoin('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                        ->whereNull('group_student_annuals.department_id')
                        ->where('students.radie','=',true)
                        ->where('group_student_annuals.semester_id','=',$semester_id)
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->whereIn('scholarship_student_annual.scholarship_id',$scholarships)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])->count();

                    $scholarship_female =  DB::table('studentAnnuals')
                        ->leftJoin('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                        ->whereNull('group_student_annuals.department_id')
                        ->where('students.radie','=',true)
                        ->where('group_student_annuals.semester_id','=',$semester_id)
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->whereIn('scholarship_student_annual.scholarship_id',$scholarships)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])
                        ->where('students.gender_id','=',2)->count(); // 2 is female

                    $array = array(
                        'st' => $scholarship_total,
                        'sf' => $scholarship_female,
                        'pt' => $total-$scholarship_total,
                        'pf' => $total_female-$scholarship_female
                    );

                    $t_st += $array['st'];
                    $t_sf += $array['sf'];
                    $t_pt += $array['pt'];
                    $t_pf += $array['pf'];

                    array_push($records,$array);

                    // unset unnecessary variables

                    unset($query);
                    unset($minDate);
                    unset($maxDate);
                    unset($total);
                    unset($total_female);
                    unset($scholarship_total);
                    unset($scholarship_female);

                    $array_total[$grade-1]['st'] += $array['st'];
                    $array_total[$grade-1]['sf'] += $array['sf'];
                    $array_total[$grade-1]['pt'] += $array['pt'];
                    $array_total[$grade-1]['pf'] += $array['pf'];
                }

                array_push($records,array('st'=>$t_st,'sf'=>$t_sf,'pt'=>$t_pt,'pf'=>$t_pf));
                $array_total[5]['st'] += $t_st;
                $array_total[5]['sf'] += $t_sf;
                $array_total[5]['pt'] += $t_pt;
                $array_total[5]['pf'] += $t_pf;

                $option['data'] = $records;
            }


        }
        array_push($departments,$array_total);
        return $departments;
    }

    public function get_student_by_group($academic_year_id , $degree, $only_foreigner,$scholarships,$semester_id){
        $departments = Department::where('parent_id',11)->with(['department_options'])->get()->toArray();
        $grades = [1,2,3,4,5];

        $array_total = array(
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0),
            array('st'=>0,'sf'=>0,'pt'=>0,'pf'=>0)
        );

        $locals = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'];
        foreach($departments as &$department) {

            $empty_option = array(
                'id'=>null,
                'name_kh'=>$department['name_kh'],
                'name_en'=>$department['name_en'],
                'name_fr'=>$department['name_fr'],
                'code'=>$department['code']
            );

            if($department['department_options'] == null || count($department['department_options']) == 0){
                $department['department_options'] = [$empty_option];
            } else {
                array_unshift($department['department_options'], $empty_option);
            }

            //dd($department);
            foreach($department['department_options'] as &$option){
                $records = array();
                $t_st = 0;
                $t_sf = 0;
                $t_pt = 0;
                $t_pf = 0;

                foreach($grades as $grade){

                    $total_query = DB::table('studentAnnuals')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                        ->whereNull('group_student_annuals.department_id')
                        ->where('group_student_annuals.semester_id','=',$semester_id)
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id']);

                    if($only_foreigner == "true"){
                        $total = $total_query->whereNotIn('students.origin_id',$locals)->count();
                    } else {
                        $total = $total_query->count();
                    }

                    $total_female_query = DB::table('studentAnnuals')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                        ->whereNull('group_student_annuals.department_id')
                        ->where('group_student_annuals.semester_id','=',$semester_id)
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])
                        ->where('students.gender_id','=',2); // 2 is female

                    if($only_foreigner == "true"){
                        $total_female = $total_female_query->whereNotIn('students.origin_id',$locals)->count();
                    } else {
                        $total_female = $total_female_query->count();
                    }

                    $scholarship_total_query =  DB::table('studentAnnuals')
                        ->leftJoin('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                        ->whereNull('group_student_annuals.department_id')
                        ->where('group_student_annuals.semester_id','=',$semester_id)
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->whereIn('scholarship_student_annual.scholarship_id',$scholarships)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id']);

                    if($only_foreigner == "true"){
                        $scholarship_total = $scholarship_total_query->whereNotIn('students.origin_id',$locals)->count();
                    } else {
                        $scholarship_total = $scholarship_total_query->count();
                    }

                    $scholarship_female_query =  DB::table('studentAnnuals')
                        ->leftJoin('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->leftJoin('group_student_annuals','studentAnnuals.id','=','group_student_annuals.student_annual_id')
                        ->whereNull('group_student_annuals.department_id')
                        ->where('group_student_annuals.semester_id','=',$semester_id)
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->whereIn('scholarship_student_annual.scholarship_id',$scholarships)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])
                        ->where('students.gender_id','=',2); // 2 is female

                    if($only_foreigner == "true"){
                        $scholarship_female = $scholarship_female_query->whereNotIn('students.origin_id',$locals)->count();
                    } else {
                        $scholarship_female = $scholarship_female_query->count();
                    }

                    $array = array(
                        'st' => $scholarship_total,
                        'sf' => $scholarship_female,
                        'pt' => $total-$scholarship_total,
                        'pf' => $total_female-$scholarship_female
                    );

                    $t_st += $array['st'];
                    $t_sf += $array['sf'];
                    $t_pt += $array['pt'];
                    $t_pf += $array['pf'];

                    array_push($records,$array);

                    // unset unnecessary variables

                    unset($query);
                    unset($minDate);
                    unset($maxDate);
                    unset($total);
                    unset($total_female);
                    unset($scholarship_total);
                    unset($scholarship_female);

                    $array_total[$grade-1]['st'] += $array['st'];
                    $array_total[$grade-1]['sf'] += $array['sf'];
                    $array_total[$grade-1]['pt'] += $array['pt'];
                    $array_total[$grade-1]['pf'] += $array['pf'];
                }

                array_push($records,array('st'=>$t_st,'sf'=>$t_sf,'pt'=>$t_pt,'pf'=>$t_pf));
                $array_total[5]['st'] += $t_st;
                $array_total[5]['sf'] += $t_sf;
                $array_total[5]['pt'] += $t_pt;
                $array_total[5]['pf'] += $t_pf;

                $option['data'] = $records;
            }


        }
        array_push($departments,$array_total);
        return $departments;
    }

    public function print_report($id){
        return $this->prepare_print_and_preview($id,false);
    }

    public function preview_report($id){
        return $this->prepare_print_and_preview($id,true);
    }

    public function prepare_print_and_preview($id, $is_preview){

        $data = json_decode($_GET['data']);

        $params = [
            'scholarships' => []
        ];

        foreach($data as $key => $param){
            if($param->name != "scholarships[]") {
                $params[$param->name] = $param->value;
            } else {
                array_push($params['scholarships'],$param->value);
            }
        }

        switch ($id) {
            case 1:
                if(isset($params['scholarships'])){
                    $scholarships = $params['scholarships'];
                } else {
                    $scholarships = [];
                }

                $semester_id = $params['semester_id'];
                $data = $this->get_student_list_by_age($params['academic_year_id'],$params['degree_id'],$params['date'],$scholarships,$semester_id);
                $degree_name = Degree::find($params['degree_id'])->name_kh;
                $academic_year_name = AcademicYear::find($params['academic_year_id'])->name_kh;
                $date = $params['date'];

                if($is_preview){
                    return view('backend.studentAnnual.reporting.template_report_student_by_age',compact('id','data','degree_name','academic_year_name','date','semester_id'));
                } else{
                    return view('backend.studentAnnual.reporting.print_report_student_by_age',compact('id','data','degree_name','academic_year_name','date','semester_id'));
                }

                break;
            case 2:
                if(isset($params['scholarships'])){
                    $scholarships = $params['scholarships'];
                } else {
                    $scholarships = [];
                }

                $semester_id = $params['semester_id'];
                $data = $this->get_student_redouble($params['academic_year_id'],$params['degree_id'],$scholarships,$semester_id);
                $degree_name = Degree::find($params['degree_id'])->name_kh;
                $academic_year_name = AcademicYear::find($params['academic_year_id'])->name_kh;

                if($is_preview){
                    return view('backend.studentAnnual.reporting.template_report_student_redouble',compact('id','data','degree_name','academic_year_name','semester_id'));
                } else{
                    return view('backend.studentAnnual.reporting.print_report_student_redouble',compact('id','data','degree_name','academic_year_name','semester_id'));
                }
                break;
            case 3:

                if(isset($params['only_foreigner'])){
                    $only_foreigner = "true";
                } else {
                    $only_foreigner = "false";
                }

                if(isset($params['scholarships'])){
                    $scholarships = $params['scholarships'];
                } else {
                    $scholarships = [];
                }

                $semester_id = $params['semester_id'];
                $data = $this->get_student_by_group($params['academic_year_id'],$params['degree_id'],$only_foreigner,$scholarships,$semester_id);
                $degree_name = Degree::find($params['degree_id'])->name_kh;
                $academic_year_name = AcademicYear::find($params['academic_year_id'])->name_kh;

                if($is_preview) {
                    return view('backend.studentAnnual.reporting.template_report_student_studying', compact('id', 'data', 'degree_name', 'academic_year_name','only_foreigner','semester_id'));
                } else {
                    return view('backend.studentAnnual.reporting.print_report_student_studying',compact('id','data','degree_name','academic_year_name','only_foreigner','semester_id'));
                }
                break;
            case 4:
                if(isset($params['scholarships'])){
                    $scholarships = $params['scholarships'];
                } else {
                    $scholarships = [];
                }

                $semester_id = $params['semester_id'];
                $data = $this->get_student_radie($params['academic_year_id'],$params['degree_id'],$scholarships,$semester_id);
                $degree_name = Degree::find($params['degree_id'])->name_kh;
                $academic_year_name = AcademicYear::find($params['academic_year_id'])->name_kh;

                if($is_preview){
                    return view('backend.studentAnnual.reporting.template_report_student_radie',compact('id','data','degree_name','academic_year_name','semester_id'));
                } else{
                    return view('backend.studentAnnual.reporting.print_report_student_radie',compact('id','data','degree_name','academic_year_name','semester_id'));
                }
                break;
            default:
                $view = 'backend.studentAnnual.reporting.reporting_student_by_age';
        }
    }

    public function reporting($id){
        $departments = Department::lists('name_kh','id')->toArray();
        $degrees = Degree::lists('name_kh','id')->toArray();
        $academicYears = AcademicYear::orderBy('id','desc')->lists('name_kh','id')->toArray();
        $scholarships = Scholarship::select('code','id')->get();

        $view = "";
        switch ($id) {
            case 1:
                $view = 'backend.studentAnnual.reporting.reporting_student_by_age';
                break;
            case 2:
                $view = 'backend.studentAnnual.reporting.reporting_student_redouble';
                break;
            case 3:
                $view = 'backend.studentAnnual.reporting.reporting_student_studying';
                break;
            case 4:
                $view = 'backend.studentAnnual.reporting.reporting_student_radie';
                break;
            default:
                return redirect(route('admin.studentAnnuals.index'));
        }

        return view($view,compact('id','degrees','academicYears','departments','scholarships'));
    }
}