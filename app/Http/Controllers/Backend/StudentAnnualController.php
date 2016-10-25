<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Student\CreateStudentRequest;
use App\Http\Requests\Backend\Student\DeleteStudentRequest;
use App\Http\Requests\Backend\Student\EditStudentRequest;
use App\Http\Requests\Backend\Student\ImportStudentRequest;
use App\Http\Requests\Backend\Student\RequestImportStudentRequest;
use App\Http\Requests\Backend\Student\StoreStudentRequest;
use App\Http\Requests\Backend\Student\UpdateStudentRequest;
use App\Models\AcademicYear;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\HighSchool;
use App\Models\History;
use App\Models\Income;
use App\Models\Origin;
use App\Models\PayslipClient;
use App\Models\Promotion;
use App\Models\Redouble;
use App\Models\Scholarship;
use App\Models\Student;
use App\Models\StudentAnnual;
use App\Repositories\Backend\StudentAnnual\StudentAnnualRepositoryContract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;


class StudentAnnualController extends Controller
{
    /**
     * @var StudentAnnualRepositoryContract
     */
    protected $students;

    /**
     * @param StudentAnnualRepositoryContract $studentAnnualRepo
     */
    public function __construct(
        StudentAnnualRepositoryContract $studentAnnualRepo
    )
    {
        $this->students = $studentAnnualRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::where('parent_id',11)->orderBy('id','DESC')->lists('code','id'); // 11 is for all academic departments
        $degrees = Degree::lists('name_kh','id');
        $grades = Grade::lists('name_kh','id');
        $genders = Gender::lists('name_kh','id');
        $options = DepartmentOption::lists('code','id');
        $academicYears = AcademicYear::orderBy('id','desc')->lists('name_kh','id');
        $origins = Origin::lists('name_kh','id');

        return view('backend.studentAnnual.index',compact('departments','degrees','grades','genders','options','academicYears','origins'));
    }

    public function popup_index(){
        $scholarship_id = $_GET['scholarship_id'];

        $departments = Department::where('parent_id',11)->orderBy('id','DESC')->lists('code','id'); // 11 is for all academic departments
        $degrees = Degree::lists('name_kh','id');
        $grades = Grade::lists('name_kh','id');
        $genders = Gender::lists('name_kh','id');
        $options = DepartmentOption::lists('code','id');
        $academicYears = AcademicYear::orderBy('id','desc')->lists('name_kh','id');
        $origins = Origin::lists('name_kh','id');
        return view('backend.studentAnnual.popup_index',compact('scholarship_id','departments','degrees','grades','genders','options','academicYears','origins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateStudentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateStudentRequest $request)
    {

        $academic_years = AcademicYear::orderBy('id','desc')->lists('name_kh','id');
        $departments = Department::where('parent_id',11)->orderBy('id','DESC')->lists('code','id'); // 11 is for all academic departments
        $degrees = Degree::lists('name_kh','id');
        $grades = Grade::lists('name_kh','id');
        $scholarships = Scholarship::lists('code','id');
        $origins = Origin::lists('name_kh','id');
        
        $genders = Gender::lists('name_kh','id');
        
        $highSchools = HighSchool::lists('name_kh','id');
        $promotions = Promotion::orderBy('name','DESC')->lists('name','id');
        $histories = History::lists('name_en','id');
        $redoubles = Redouble::lists('name_en','id');
        $department_options = DepartmentOption::lists('code','id');
        return view('backend.studentAnnual.create',compact('departments','promotions','degrees','grades','genders','histories','scholarships','highSchools','origins','academic_years','redoubles','department_options'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreStudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        $this->students->create($request);
        return redirect()->route('admin.studentAnnuals.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $studentAnnual = $this->students->findOrThrowException($id);

        $student = Student::with(['studentAnnuals','studentAnnuals.scholarships','gender','origin','studentAnnuals.department',
            'studentAnnuals.grade','studentAnnuals.degree','studentAnnuals.department_option','studentAnnuals.academic_year'])
            ->find($studentAnnual->student_id);

        //dd($student);
        return view('backend.studentAnnual.popup_show',compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditStudentRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditStudentRequest $request, $id)
    {
        $studentAnnual = $this->students->findOrThrowException($id);

        //dd($studentAnnual);

        $academic_years = AcademicYear::orderBy('id','desc')->lists('name_kh','id');
        $departments = Department::where('parent_id',11)->orderBy('id','DESC')->lists('code','id'); // 11 is for all academic departments
        $degrees = Degree::lists('name_kh','id');
        $grades = Grade::lists('name_kh','id');
        $scholarships = Scholarship::lists('code','id');
        $origins = Origin::lists('name_kh','id');
        $genders = Gender::lists('name_kh','id');
        $highSchools = HighSchool::lists('name_kh','id');
        $promotions = Promotion::orderBy('name','DESC')->lists('name','id');
        $histories = History::lists('name_en','id');
        $redoubles = Redouble::lists('name_en','id');
        $department_options = DepartmentOption::lists('code','id');
        return view('backend.studentAnnual.edit',compact('studentAnnual','departments','promotions','degrees','grades','genders','histories','scholarships','highSchools','origins','academic_years','redoubles','department_options'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateStudentRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, $id)
    {
        $this->students->update($id, $request);
        return redirect()->route('admin.studentAnnuals.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteStudentRequest $request, $id)
    {
        $this->students->destroy($id);
        if($request->ajax()){
            return json_encode(array("success"=>true));
        } else {
            return redirect()->route('admin.studentAnnuals.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data(Request $request)
    {


        $scholarship_id = Input::get('scholarship_id');

        $studentAnnuals = StudentAnnual::select([
                'studentAnnuals.id','students.id_card','students.name_kh','students.dob as dob','students.name_latin', 'genders.code as gender', 'departmentOptions.code as option',
                DB::raw("CONCAT(degrees.code,grades.code,departments.code) as class")
            ])
            ->leftJoin('students','students.id','=','studentAnnuals.student_id')
            ->leftJoin('genders', 'students.gender_id', '=', 'genders.id')
            ->leftJoin('grades', 'studentAnnuals.grade_id', '=', 'grades.id')
            ->leftJoin('departmentOptions', 'studentAnnuals.department_option_id', '=', 'departmentOptions.id')
            ->leftJoin('departments', 'studentAnnuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees', 'studentAnnuals.degree_id', '=', 'degrees.id')
            ->leftJoin('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id');



        $datatables = app('datatables')->of($studentAnnuals)
            ->editColumn('dob', function ($studentAnnual){
                $date = Carbon::createFromFormat("Y-m-d h:i:s",$studentAnnual->dob);
                return $date->toFormattedDateString();
            })
            ->addColumn('export', function ($studentAnnual) use ($scholarship_id) {
                return  '<a href="'.route('admin.scholarship.holder').'?scholarship_id='.$scholarship_id.'&studentAnnual_id='.$studentAnnual->id.'" class="btn btn-xs btn-primary"><i class="fa fa-mail-forward" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.export').'"></i> </a>';
                //return  '<a href="'.route('admin.candidate.popup_create').'?exam_id='.$scholarship_id.'&studentBac2_id='.$studentBac2->id.'" class="btn btn-xs btn-primary"><i class="fa fa-mail-forward" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.export').'"></i> </a>';
            })
            ->addColumn('action', function ($studentAnnual) {
                $date = Carbon::createFromFormat("Y-m-d h:i:s",$studentAnnual->dob);

                $data = array();
                $object = new \stdClass();
                $object->id = $studentAnnual->id;
                $object->id_card = $studentAnnual->id_card;
                $object->name_kh = $studentAnnual->name_kh;
                $object->name_latin = $studentAnnual->name_latin;
                $object->dob = $date->toFormattedDateString();
                $object->gender = $studentAnnual->gender;
                $object->class = $studentAnnual->class;
                $object->option = $studentAnnual->option;

                $data[] = $object;

                return '<a href="' . route('admin.studentAnnuals.edit', $studentAnnual->id) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.edit') . '"></i></a>' .
                //' <button class="btn btn-xs btn-danger btn-delete" data-remote="' . route('admin.studentAnnuals.destroy', $studentAnnual->id) . '"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>' .
                ' <button class="btn btn-xs btn-info btn-show" data-remote="' . route('admin.studentAnnuals.show', $studentAnnual->id) . '"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.view') . '"></i></button>' .
                " <button class='btn btn-xs btn-export' style='display:none' data-remote='" .
                  json_encode($data)  .
                "'><i class='fa fa-external-link-square' data-toggle='tooltip' data-placement='top' title='" . 'export' . "'></i></button>" ;
            });

        // additional search
        if ($academic_year = $datatables->request->get('academic_year')) {
            $datatables->where('studentAnnuals.academic_year_id', '=', $academic_year);
        } else {
            $last_academic_year_id =AcademicYear::orderBy('id','desc')->first()->id;
            $datatables->where('studentAnnuals.academic_year_id', '=', $last_academic_year_id);
        }
        if ($degree = $datatables->request->get('degree')) {
            $datatables->where('studentAnnuals.degree_id', '=', $degree);
        }
        if ($grade = $datatables->request->get('grade')) {
            $datatables->where('studentAnnuals.grade_id', '=', $grade);
        }
        if ($department = $datatables->request->get('department')) {
            $datatables->where('studentAnnuals.department_id', '=', $department);
        }
        if ($gender = $datatables->request->get('gender')) {
            $datatables->where('students.gender_id', '=', $gender);
        }
        if ($option = $datatables->request->get('option')) {
            $datatables->where('studentAnnuals.department_option_id', '=', $option);
        }
        if ($origin = $datatables->request->get('origin')) {
            $datatables->where('students.origin_id', '=', $origin);
        }
        if ($scholarship = $datatables->request->get('scholarship')) {
            $datatables->where('scholarship_student_annual.scholarship_id', '=', $scholarship);
        }


        return $datatables->make(true);
    }

    public function request_import(RequestImportStudentRequest $request){

        return view('backend.studentAnnual.import');

    }

    public function import(ImportStudentRequest $request){

        $now = Carbon::now()->format('Y_m_d_H');

        // try to move uploaded file to a temporary location
        if($request->file('import')!= null){
            $import = $now. '.' .$request->file('import')->getClientOriginalExtension();

            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', $import
            );

            $storage_path = base_path() . '/public/assets/uploaded_file/temp/'.$import;

            // and then read that data and store to database
            //Excel::load($storage_path, function($reader) {
            //    dd($reader->first());
            //});


            DB::beginTransaction();

            try{
                Excel::filter('chunk')->load($storage_path)->chunk(1000, function($results){
                    //dd($results->first());
                    // Loop through all rows
                    $results->each(function($row) {
                        // Clone an object for running query in studentAnnual
                        $studentAnnual_data = $row->toArray();
                        $studentAnnual_data['create_uid'] = 1;
                        unset($studentAnnual_data['id_card']);
                        unset($studentAnnual_data['name_latin']);
                        unset($studentAnnual_data['name_kh']);
                        unset($studentAnnual_data['gender_id']);
                        unset($studentAnnual_data['dob']);
                        unset($studentAnnual_data['origin_id']);
                        unset($studentAnnual_data['redouble_id']);
                        unset($studentAnnual_data['radie']);
                        unset($studentAnnual_data['phone']);
                        unset($studentAnnual_data['parent_phone']);
                        unset($studentAnnual_data['observation']);

                        if($row->student_id == null){ // This student doesn't have any information before so add the general information first
                            // let's find its id first, sometime it is already in database
                            $student_data = $row->toArray();

                            $old_student = Student::where('id_card',$student_data['id_card'])->first();
                            if($old_student == null){ // That id card is not found, create new record
                                $student_data['create_uid'] = 1;
                                unset($student_data['student_id']);
                                unset($student_data['history_id']);
                                unset($student_data['degree_id']);
                                unset($student_data['grade_id']);
                                unset($student_data['department_id']);
                                unset($student_data['option_id']);
                                unset($student_data['group']);
                                unset($student_data['promotion_id']);
                                unset($student_data['academic_year_id']);

                                $student = Student::create($student_data);
                                if($student) {
                                    $studentAnnual_data['student_id'] = $student->id;
                                }
                            } else {// just get id
                                $studentAnnual_data['student_id'] = $old_student->id;
                            }
                        }

                        // Create client id for every student annual
                        $payslip_client = new PayslipClient();
                        $payslip_client->type = "Student";
                        $payslip_client->create_uid =auth()->id();
                        $payslip_client->save();

                        $studentAnnual_data['payslip_client_id'] = $payslip_client->id;

                        $studentAnnual = StudentAnnual::create($studentAnnual_data);
                        if($studentAnnual){
                            if(isset($studentAnnual_data['scholarship_id']) && $studentAnnual_data['scholarship_id'] != null){
                                $studentAnnual->scholarships()->attach($studentAnnual_data['scholarship_id']);
                            }

                            // Import payment

                            if(isset($studentAnnual_data['1st_no']) && $studentAnnual_data['1st_no'] != "0" && $studentAnnual_data['1st_no'] != "#N/A"){
                                $this->registerIncome($studentAnnual_data,$studentAnnual_data['1st_no'],$studentAnnual_data['1st']);
                            }
                            if(isset($studentAnnual_data['1st_no']) && $studentAnnual_data['2nd_no'] != "0" && $studentAnnual_data['2nd_no'] != "#N/A"){
                                $this->registerIncome($studentAnnual_data,$studentAnnual_data['2nd_no'],$studentAnnual_data['2nd']);
                            }
                            if(isset($studentAnnual_data['1st_no']) && $studentAnnual_data['3rd_no'] != "0" && $studentAnnual_data['3rd_no'] != "#N/A"){
                                $this->registerIncome($studentAnnual_data,$studentAnnual_data['3rd_no'],$studentAnnual_data['3rd']);
                            }
                            if(isset($studentAnnual_data['1st_no']) && $studentAnnual_data['sport_no'] != "0" && $studentAnnual_data['sport_no'] != "#N/A"){
                                $this->registerIncome($studentAnnual_data,$studentAnnual_data['sport_no'],$studentAnnual_data['sport']);
                            }
                        }


                        unset($student_data);
                        unset($studentAnnual_data);
                    });
                });

            } catch(Exception $e){
                DB::rollback();
            }
            DB::commit();

            /*UserLog
            UserLog::log([
                'model' => 'StudentBac2',
                'action'   => 'Import',
                'data'     => 'none', // if it is create action, store only the new id.
                'developer'   => Auth::id() == 1?true:false
            ]); */

            return redirect(route('admin.studentAnnuals.index'));
        }
    }

    public function registerIncome($studentAnnual_data, $number, $payment){
        $income = new Income();

        $income->created_at = Carbon::now();
        $income->create_uid = auth()->id();
        $income->number = $number;
        if($payment!= "120000"){ // We consider 120000 is for sport fee, it is not good but for now ,.... yes
            $income->amount_dollar = $payment;
        } else {
            $income->amount_riel = $payment;
        }


        $income->sequence = Income::where('payslip_client_id',$studentAnnual_data['payslip_client_id'])->count()+1; // Sequence of student payment
        $income->payslip_client_id = $studentAnnual_data['payslip_client_id'];
        $income->pay_date = Carbon::now();

        if($studentAnnual_data['degree_id']== config('access.degrees.degree_engineer') || $studentAnnual_data['degree_id']== config('access.degrees.degree_associate')){
            $income->income_type_id = config('access.income_type.income_type_student_day');
            $income->account_id = config('access.account.account_day_student');
        } else if($studentAnnual_data['degree_id']== config('access.degrees.degree_bachelor')){
            $income->income_type_id = config('access.income_type.income_type_student_night');
            $income->account_id=config('access.account.account_night_student');
        } else if($studentAnnual_data['degree_id']== config('access.degrees.degree_master')){
            $income->income_type_id = config('access.income_type.income_type_student_master');
            $income->account_id=config('access.account.account_master_student');
        }

        $income->save();
    }


    public function get_student_list_by_age($academic_year_id, $degree){

        $grades = [1,2,3,4,5];
        $scholarships = [2,3,4,5,6];
        $ages = array(
            ['min'=>1,'max'=>17,'name'=>'<17','data'=> array()],
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


        foreach($ages as &$age){
            $t_st = 0;
            $t_sf = 0;
            $t_pt = 0;
            $t_pf = 0;
            foreach($grades as $grade){
                $minDate = Carbon::today()->subYears($age['max']);
                $maxDate = Carbon::today()->subYears($age['min'])->subDay()->endOfDay();

                $total = DB::table('studentAnnuals')
                    ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                    ->where('studentAnnuals.degree_id','=',$degree)
                    ->where('studentAnnuals.grade_id','=',$grade)
                    ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                    ->whereBetween('students.dob',[$minDate,$maxDate])->count();


                $total_female = DB::table('studentAnnuals')
                    ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                    ->where('studentAnnuals.degree_id','=',$degree)
                    ->where('studentAnnuals.grade_id','=',$grade)
                    ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                    ->whereBetween('students.dob',[$minDate,$maxDate])
                    ->where('students.gender_id','=',2)->count(); // 2 is female

                                ;
                $scholarship_total =  DB::table('studentAnnuals')
                    ->leftJoin('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                    ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                    ->where('studentAnnuals.degree_id','=',$degree)
                    ->where('studentAnnuals.grade_id','=',$grade)
                    ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                    ->whereIn('scholarship_student_annual.scholarship_id',$scholarships)
                    ->whereBetween('students.dob',[$minDate,$maxDate])->count();

                $scholarship_female =  DB::table('studentAnnuals')
                    ->leftJoin('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                    ->leftJoin('students','studentAnnuals.student_id','=','students.id')
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
        }
        return $ages;
    }

    public function get_student_redouble($academic_year_id , $degree){

        $departments = Department::where('parent_id',11)->with(['department_options'])->get()->toArray();
        $grades = [1,2,3,4,5];
        $scholarships = [2,3,4,5,6];

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

            if($degree ==2){
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
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])
                        ->where('students.redouble_id','=',$degree==2?$grade+5:$grade)->count();

                    $total_female = DB::table('studentAnnuals')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])
                        ->where('students.gender_id','=',2)
                        ->where('students.redouble_id','=',$degree==2?$grade+5:$grade)->count(); // 2 is female

                    $scholarship_total =  DB::table('studentAnnuals')
                        ->leftJoin('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->whereIn('scholarship_student_annual.scholarship_id',$scholarships)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])
                        ->where('students.redouble_id','=',$degree==2?$grade+5:$grade)->count();

                    $scholarship_female =  DB::table('studentAnnuals')
                        ->leftJoin('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->whereIn('scholarship_student_annual.scholarship_id',$scholarships)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])
                        ->where('students.gender_id','=',2)
                        ->where('students.redouble_id','=',$degree==2?$grade+5:$grade)->count(); // 2 is female

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

    public function get_student_by_group($academic_year_id , $degree, $only_foreigner){
        $departments = Department::where('parent_id',11)->with(['department_options'])->get()->toArray();
        //dd($departments);
        $grades = [1,2,3,4,5];
        $scholarships = [2,3,4,5,6];

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

            if($degree ==2){
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
        switch ($id) {
            case 1:
                $data = $this->get_student_list_by_age($_GET['academic_year_id'],$_GET['degree_id']);
                $degree_name = Degree::find($_GET['degree_id'])->name_kh;
                $academic_year_name = AcademicYear::find($_GET['academic_year_id'])->name_kh;

                if($is_preview){
                    return view('backend.studentAnnual.reporting.template_report_student_by_age',compact('id','data','degree_name','academic_year_name'));
                } else{
                    return view('backend.studentAnnual.reporting.print_report_student_by_age',compact('id','data','degree_name','academic_year_name'));
                }

                break;
            case 2:
                $data = $this->get_student_redouble($_GET['academic_year_id'],$_GET['degree_id']);
                $degree_name = Degree::find($_GET['degree_id'])->name_kh;
                $academic_year_name = AcademicYear::find($_GET['academic_year_id'])->name_kh;

                if($is_preview){
                    return view('backend.studentAnnual.reporting.template_report_student_redouble',compact('id','data','degree_name','academic_year_name'));
                } else{
                    return view('backend.studentAnnual.reporting.print_report_student_redouble',compact('id','data','degree_name','academic_year_name'));
                }
                break;
            case 3:

                $data = $this->get_student_by_group($_GET['academic_year_id'],$_GET['degree_id'],$_GET['only_foreigner']);
                $degree_name = Degree::find($_GET['degree_id'])->name_kh;
                $academic_year_name = AcademicYear::find($_GET['academic_year_id'])->name_kh;
                $only_foreigner = $_GET['only_foreigner'];
                if($is_preview) {
                    return view('backend.studentAnnual.reporting.template_report_student_studying', compact('id', 'data', 'degree_name', 'academic_year_name','only_foreigner'));
                } else {
                    return view('backend.studentAnnual.reporting.print_report_student_studying',compact('id','data','degree_name','academic_year_name','only_foreigner'));
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
            default:
                return redirect(route('admin.studentAnnuals.index'));
        }

        return view($view,compact('id','degrees','academicYears','departments'));
    }

    public function request_export_fields(){
        // In case it is passed directly from current list
        $academic_year = isset($_GET['academic_year'])?$_GET['academic_year']:null;
        $degree = isset($_GET['degree'])?$_GET['degree']:null;
        $grade = isset($_GET['grade'])?$_GET['grade']:null;
        $department = isset($_GET['department'])?$_GET['department']:null;
        $gender = isset($_GET['gender'])?$_GET['gender']:null;
        $option = isset($_GET['option'])?$_GET['option']:null;
        $origin = isset($_GET['origin'])?$_GET['origin']:null;
        // else, from custom list

        $student_ids = isset($_GET['student_ids'])?$_GET['student_ids']:null;

        return view('backend.studentAnnual.popup_export',compact('academic_year','department','degree','grade','gender','option','origin','student_ids'));
    }

    public function request_export_list_custom(){
        return view('backend.studentAnnual.popup_export_custom');
    }

    public function export(){

        $studentAnnuals = StudentAnnual::select([
            'studentAnnuals.id',
            'students.id_card',
            'students.name_kh',
            'students.name_latin',
            'students.dob as dob',
            'genders.code as gender_id',
            'origins.name_kh as origin_id',
            'pob.name_kh as pob',
            'studentAnnuals.group',
            'studentAnnuals.promotion_id',
            'academicYears.name_latin as academic_year_id',
            'histories.name_kh as history_id',
            'departmentOptions.code as department_option_id',
            'students.radie',
            'students.observation',
            'students.mcs_no',
            'students.can_id',
            'highSchools.name_en as high_school_id',
            'students.photo',
            'students.phone',
            'students.email',
            'students.admission_date',
            'students.address',
            'students.address_current',
            'students.parent_name',
            'students.parent_occupation',
            'students.parent_address',
            'students.parent_phone',
            DB::raw("CONCAT(degrees.code,grades.code,departments.code) as class"),
            'departmentOptions.code as option'
        ])
            ->join('students','students.id','=','studentAnnuals.student_id')
            ->join('genders', 'students.gender_id', '=', 'genders.id')
            ->join('grades', 'studentAnnuals.grade_id', '=', 'grades.id')
            ->leftJoin('departmentOptions', 'studentAnnuals.department_option_id', '=', 'departmentOptions.id')
            ->join('departments', 'studentAnnuals.department_id', '=', 'departments.id')
            ->join('degrees', 'studentAnnuals.degree_id', '=', 'degrees.id')
            ->join('academicYears','studentAnnuals.academic_year_id', '=','academicYears.id')
            ->leftJoin('origins', 'students.origin_id', '=', 'origins.id')
            ->leftJoin('origins as pob', 'students.pob', '=', 'pob.id')
            ->leftJoin('histories', 'studentAnnuals.history_id', '=', 'histories.id')
            ->leftJoin('highSchools', 'students.high_school_id', '=', 'highSchools.id')
        ;

        $title = 'បញ្ជីនិស្សិតុ';

        if($ids = $_POST['student_ids']){
            $studentAnnuals = $studentAnnuals->whereIn('studentAnnuals.id',json_decode($ids));
        } else {
            // additional search
            if ($degree = $_POST['filter_degree']) {
                $studentAnnuals = $studentAnnuals->where('studentAnnuals.degree_id', $degree);

                $degree_obj = Degree::where('id',$degree)->first();
                $title .= "ថ្នាក់".$degree_obj->name_kh;
            }

            if ($academic_year = $_POST['filter_academic_year']) {
                $studentAnnuals = $studentAnnuals->where('studentAnnuals.academic_year_id', $academic_year);

                $academic_year_obj = AcademicYear::where('id',$academic_year)->first();
                $title .= " ឆ្នាំសិក្សា ".$academic_year_obj->name_kh;
            }

            if ($grade = $_POST['filter_grade']) {
                $studentAnnuals = $studentAnnuals->where('studentAnnuals.grade_id', $grade);

                $grade_obj = Grade::where('id',$grade)->first();
                $title .= " ".$grade_obj->name_kh;
            }
            if ($department = $_POST['filter_department']) {
                $studentAnnuals = $studentAnnuals->where('studentAnnuals.department_id', $department);

                $department_obj = Department::where('id',$department)->first();
                $title .= " ដេប៉ាតឺម៉ង់ ".$department_obj->name_kh;
            }
            if ($gender = $_POST['filter_gender']) {
                $studentAnnuals = $studentAnnuals->where('students.gender_id', $gender);

                $gender_obj = Gender::where('id',$gender)->first();
                $title .= " ភេទ".$gender_obj->name_kh;
            }
            if ($option = $_POST['filter_option']) {
                $studentAnnuals = $studentAnnuals->where('studentAnnuals.department_option_id', $option);

                $option_obj = DepartmentOption::where('id',$option)->first();
                $title .= " ជំនាញ ".$option_obj->name_kh;
            }
            if ($origin = $_POST['filter_origin']) {
                $studentAnnuals = $studentAnnuals->where('students.origin_id', $origin);
            }
        }
        //dd($studentAnnuals->toSql());
        $data = $studentAnnuals->get()->toArray();

        foreach ($data as &$value){
            $date = Carbon::createFromFormat('Y-m-d H:i:s',$value['dob'])->formatLocalized("%d/%b/%Y");
            $value['dob'] = $date;
            $value['name_latin'] = strtoupper($value['name_latin']);
        }


        $alpha = array();
        $letter = 'A';
        while ($letter !== 'AAA') {
            $alpha[] = $letter++;
        }
        // Selected fields to export

        $fields = array();

        if(isset($_POST['id'])){
            array_push($fields,$_POST['id']);
        }
        if(isset($_POST['id_card'])){
            array_push($fields,$_POST['id_card']);
        }
        if(isset($_POST['name_latin'])){
            array_push($fields,$_POST['name_latin']);
        }
        if(isset($_POST['name_kh'])){
            array_push($fields,$_POST['name_kh']);
        }
        if(isset($_POST['dob'])){
            array_push($fields,$_POST['dob']);
        }
        if(isset($_POST['gender_id'])){
            array_push($fields,$_POST['gender_id']);
        }
        if(isset($_POST['origin_id'])){
            array_push($fields,$_POST['origin_id']);
        }
        if(isset($_POST['pob'])){
            array_push($fields,$_POST['pob']);
        }
        if(isset($_POST['class'])){
            array_push($fields,$_POST['class']);
        }
        if(isset($_POST['group'])){
            array_push($fields,$_POST['group']);
        }
        if(isset($_POST['promotion_id'])){
            array_push($fields,$_POST['promotion_id']);
        }
        if(isset($_POST['academic_year_id'])){
            array_push($fields,$_POST['academic_year_id']);
        }
        if(isset($_POST['history_id'])){
            array_push($fields,$_POST['history_id']);
        }
        if(isset($_POST['department_option_id'])){
            array_push($fields,$_POST['department_option_id']);
        }
        if(isset($_POST['radie'])){
            array_push($fields,$_POST['radie']);
        }
        if(isset($_POST['observation'])){
            array_push($fields,$_POST['observation']);
        }
        if(isset($_POST['mcs_no'])){
            array_push($fields,$_POST['mcs_no']);
        }
        if(isset($_POST['can_id'])){
            array_push($fields,$_POST['can_id']);
        }
        if(isset($_POST['high_school_id'])){
            array_push($fields,$_POST['high_school_id']);
        }
        if(isset($_POST['photo'])){
            array_push($fields,$_POST['photo']);
        }
        if(isset($_POST['phone'])){
            array_push($fields,$_POST['phone']);
        }
        if(isset($_POST['email'])){
            array_push($fields,$_POST['phone']);
        }
        if(isset($_POST['admission_date'])){
            array_push($fields,$_POST['admission_date']);
        }
        if(isset($_POST['address'])){
            array_push($fields,$_POST['address']);
        }
        if(isset($_POST['address_current'])){
            array_push($fields,$_POST['address_current']);
        }
        if(isset($_POST['parent_name'])){
            array_push($fields,$_POST['parent_name']);
        }
        if(isset($_POST['parent_occupation'])){
            array_push($fields,$_POST['parent_occupation']);
        }
        if(isset($_POST['parent_address'])){
            array_push($fields,$_POST['parent_address']);
        }
        if(isset($_POST['parent_phone'])){
            array_push($fields,$_POST['parent_phone']);
        }

        Excel::create('បញ្ជីនិស្សិត', function($excel) use ($data, $title,$alpha,$fields) {

            // Set the title
            $excel->setTitle('បញ្ជីនិស្សិត');

            // Chain the setters
            $excel->setCreator('Department of Study & Student Affair')
                ->setCompany('Institute of Technology of Cambodia');

            $excel->sheet('New sheet', function($sheet) use ($data,$title,$alpha,$fields) {

                $number_column = count($fields);

                $sheet->setOrientation('landscape');
                // Set top, right, bottom, left
                $sheet->setPageMargin(array(
                    0.25, 0.30, 0.25, 0.30
                ));

                // Set all margins
                $sheet->setPageMargin(0.25);

                $sheet->row(1, array(
                    'ព្រះរាជាណាចក្រកម្ពុជា'
                ));
                $sheet->appendRow(array(
                    'ជាតិ សាសនា ព្រះមហាក្សត្រ'
                ));
                $sheet->appendRow(array(
                    'ក្រសួងអប់រំ យុវជន ​និងកីឡា'
                ));
                $sheet->appendRow(array(
                    'វិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា'
                ));
                $sheet->appendRow(array(
                    $title
                ));
                $header = array();
                foreach ($fields as $field){
                    array_push($header,trans('indexs.'.$field));
                }

                $sheet->rows(
                    array($header)
                );


                foreach ($data as $item) {

                    $row = array();
                    foreach($fields as $field){
                        $row[$field] = $item[$field];
                    }

                    $sheet->appendRow(
                        $row
                    );
                }

                $sheet->mergeCells('A1:'.$alpha[$number_column-1].'1');
                $sheet->mergeCells('A2:'.$alpha[$number_column-1].'2');
                $sheet->mergeCells('A3:'.$alpha[$number_column-1].'3');
                $sheet->mergeCells('A4:'.$alpha[$number_column-1].'4');
                $sheet->mergeCells('A5:'.$alpha[$number_column-1].'5');

                $sheet->cells('A1:'.$alpha[$number_column-1].'2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('middle');
                });

                $sheet->cells('A5:'.$alpha[$number_column-1].(6+count($data)), function($cells) {
                    $cells->setAlignment('left');
                    $cells->setValignment('middle');
                });

                $sheet->setBorder('A6:'.$alpha[$number_column-1].(6+count($data)), 'thin');

            });

        })->export('xls');

    }

    public function report($id){  // Export student report
        switch ($id) {
            case 1:
                $data = $this->get_student_list_by_age($_GET['academic_year_id'],$_GET['degree_id']);
                $degree_name = Degree::find($_GET['degree_id'])->name_kh;
                $academic_year_name = AcademicYear::find($_GET['academic_year_id'])->name_kh;
                Excel::create('ស្ថិតិនិស្សិត តាមអាយុ', function($excel) use ($data,$degree_name,$academic_year_name) {

                    // Set the title
                    $excel->setTitle('ស្ថិតិនិស្សិត តាមអាយុ');

                    // Chain the setters
                    $excel->setCreator('Department of Study & Student Affair')
                        ->setCompany('Institute of Technology of Cambodia');

                    $excel->sheet('New sheet', function($sheet) use ($data,$degree_name,$academic_year_name) {

                        $sheet->setOrientation('landscape');
                        // Set top, right, bottom, left
                        $sheet->setPageMargin(array(
                            0.25, 0.30, 0.25, 0.30
                        ));

                        // Set all margins
                        $sheet->setPageMargin(0.25);

                        $sheet->row(1, array(
                            'ព្រះរាជាណាចក្រកម្ពុជា'
                        ));
                        $sheet->appendRow(array(
                            'ជាតិ សាសនា ព្រះមហាក្សត្រ'
                        ));
                        $sheet->appendRow(array(
                            'ក្រសួងអប់រំ យុវជន ​និងកីឡា'
                        ));
                        $sheet->appendRow(array(
                            'វិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា'
                        ));
                        $sheet->appendRow(array(
                            'ស្ថិតិនិស្សិត តាមអាយុ ថ្នាក់'.$degree_name.'និងតាមឆ្នាំ ឆ្នាំសិក្សា'.$academic_year_name
                        ));

                        $sheet->rows(array(
                            array('អាយុ', 'ឆ្នាំទី១','','','','ឆ្នាំទី២','','','','ឆ្នាំទី៣','','','','ឆ្នាំទី៤','','','','ឆ្នាំទី៥','','','','សរុប','','',''),
                            array('','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ',''),
                            array('','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី'),

                        ));

                        foreach ($data as $item) {
                            $row = array($item['name']);
                            foreach($item['data'] as $grade){

                                array_push($row,$grade['st']);
                                array_push($row,$grade['sf']);
                                array_push($row,$grade['pt']);
                                array_push($row,$grade['pf']);
                            }

                            $sheet->appendRow(
                                $row
                            );
                        }

                        $sheet->rows(array(
                            array("",'សំគាល់ៈចំពោះគ្រឹះស្ថានឧត្តមសិក្សាណា ដែលបណ្តុះបណ្តាលលើសពី៤ ឬ៥ឆ្នាំ អាចបន្តទំព័របាន'),
                            array('','','','','','','','','','','','','','','','','ធ្វើនៅ.............ថ្ងៃទី.............ខែ............ឆ្នាំ២០១...... '),
                            array('','','','','','','','','','','','','','','','','សាកលវិទ្យាធិការ/នាយក')
                        ));

                        $sheet->mergeCells('A1:Y1');
                        $sheet->mergeCells('A2:Y2');
                        $sheet->mergeCells('A3:Y3');
                        $sheet->mergeCells('A4:Y4');
                        $sheet->mergeCells('A5:Y5');
                        $sheet->mergeCells('A6:A8');

                        $sheet->mergeCells('B6:E6');
                        $sheet->mergeCells('F6:I6');
                        $sheet->mergeCells('J6:M6');
                        $sheet->mergeCells('N6:Q6');
                        $sheet->mergeCells('R6:U6');
                        $sheet->mergeCells('V6:Y6');

                        $sheet->mergeCells('B7:C7');
                        $sheet->mergeCells('D7:E7');
                        $sheet->mergeCells('F7:G7');
                        $sheet->mergeCells('H7:I7');
                        $sheet->mergeCells('J7:K7');
                        $sheet->mergeCells('L7:M7');
                        $sheet->mergeCells('N7:O7');
                        $sheet->mergeCells('P7:Q7');
                        $sheet->mergeCells('R7:S7');
                        $sheet->mergeCells('T7:U7');
                        $sheet->mergeCells('V7:W7');
                        $sheet->mergeCells('X7:Y7');

                        $sheet->mergeCells('B22:Y22');
                        $sheet->mergeCells('Q23:Y23');
                        $sheet->mergeCells('Q24:Y24');

                        $sheet->cells('A1:X2', function($cells) {
                           $cells->setAlignment('center');
                            $cells->setValignment('middle');
                        });
                        $sheet->cells('A5:X21', function($cells) {
                            $cells->setAlignment('center');
                            $cells->setValignment('middle');
                        });
                        $sheet->cells('Q23:Q24', function($cells) {
                            $cells->setAlignment('center');
                            $cells->setValignment('middle');
                        });
                        $sheet->setBorder('A6:Y21', 'thin');
                    });

                })->export('xls');
                break;
            case 2:
                $data = $this->get_student_redouble($_GET['academic_year_id'],$_GET['degree_id']);

                $degree_name = Degree::find($_GET['degree_id'])->name_kh;
                $academic_year_name = AcademicYear::find($_GET['academic_year_id'])->name_kh;
                Excel::create('ស្ថិតិនិស្សិតត្រួតថ្នាក់ តាមដេប៉ាតឺម៉ង់និងជំនាញ ថ្នាក់'.$degree_name, function($excel) use ($data,$degree_name,$academic_year_name) {

                    // Set the title
                    $excel->setTitle('ស្ថិតិនិស្សិត តាមអាយុ');

                    // Chain the setters
                    $excel->setCreator('Department of Study & Student Affair')
                        ->setCompany('Institute of Technology of Cambodia');

                    $excel->sheet('New sheet', function($sheet) use ($data,$degree_name,$academic_year_name) {

                        $sheet->setOrientation('landscape');
                        // Set top, right, bottom, left
                        $sheet->setPageMargin(array(
                            0.25, 0.30, 0.25, 0.30
                        ));

                        // Set all margins
                        $sheet->setPageMargin(0.25);

                        $sheet->row(1, array(
                            'ព្រះរាជាណាចក្រកម្ពុជា'
                        ));
                        $sheet->appendRow(array(
                            'ជាតិ សាសនា ព្រះមហាក្សត្រ'
                        ));
                        $sheet->appendRow(array(
                            'ក្រសួងអប់រំ យុវជន ​និងកីឡា'
                        ));
                        $sheet->appendRow(array(
                            'វិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា'
                        ));
                        $sheet->appendRow(array(
                            'ស្ថិតិនិស្សិត តាមអាយុ ថ្នាក់'.$degree_name.'និងតាមឆ្នាំ ឆ្នាំសិក្សា'.$academic_year_name
                        ));

                        $sheet->rows(array(
                            array('ល.រ','មហាវិទ្យាល័យ','ឯកទេស / ជំនាញ','រយៈ', 'ឆ្នាំទី១','','','','ឆ្នាំទី២','','','','ឆ្នាំទី៣','','','','ឆ្នាំទី៤','','','','ឆ្នាំទី៥','','','','សរុប','','',''),
                            array('','','','ពេល','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ',''),
                            array('','','','បប','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី'),

                        ));

                        $key = 1;
                        $count = 1;
                        foreach ($data as $department) {
                            if($key <sizeof($data)) {
                                foreach ($department['department_options'] as $option) {
                                    $row = array($count, $department['name_kh']);
                                    array_push($row, $option['code']);
                                    array_push($row,'3');
                                    foreach ($option['data'] as $grade) {
                                        array_push($row, $grade['st']);
                                        array_push($row, $grade['sf']);
                                        array_push($row, $grade['pt']);
                                        array_push($row, $grade['pf']);
                                    }
                                    $sheet->appendRow(
                                        $row
                                    );
                                    $count++;
                                }
                            }
                            $key++;
                        }

                        $row = array('សរុប','','','');
                        foreach(end($data) as $total){
                            array_push($row, $total['st']);
                            array_push($row, $total['sf']);
                            array_push($row, $total['pt']);
                            array_push($row, $total['pf']);
                        }
                        $sheet->appendRow(
                            $row
                        );

                        $sheet->rows(array(
                            array("",'សំគាល់ៈចំពោះគ្រឹះស្ថានឧត្តមសិក្សាណា ដែលបណ្តុះបណ្តាលលើសពី៤ ឬ៥ឆ្នាំ អាចបន្តទំព័របាន'),
                            array('','','','','','','','','','','','','','','','','ធ្វើនៅ.............ថ្ងៃទី.............ខែ............ឆ្នាំ២០១...... '),
                            array('','','','','','','','','','','','','','','','','សាកលវិទ្យាធិការ/នាយក')
                        ));

                        $sheet->mergeCells('A1:AB1');
                        $sheet->mergeCells('A2:AB2');
                        $sheet->mergeCells('A3:AB3');
                        $sheet->mergeCells('A4:AB4');
                        $sheet->mergeCells('A5:AB5');
                        $sheet->mergeCells('A6:A8');
                        $sheet->mergeCells('B6:B8');
                        $sheet->mergeCells('C6:C8');

                        $sheet->mergeCells('E6:H6');
                        $sheet->mergeCells('I6:L6');
                        $sheet->mergeCells('M6:P6');
                        $sheet->mergeCells('Q6:T6');
                        $sheet->mergeCells('U6:X6');
                        $sheet->mergeCells('Y6:AB6');

                        $sheet->mergeCells('E7:F7');
                        $sheet->mergeCells('G7:H7');
                        $sheet->mergeCells('I7:J7');
                        $sheet->mergeCells('K7:L7');
                        $sheet->mergeCells('M7:N7');
                        $sheet->mergeCells('O7:P7');
                        $sheet->mergeCells('Q7:R7');
                        $sheet->mergeCells('S7:T7');
                        $sheet->mergeCells('U7:V7');
                        $sheet->mergeCells('W7:X7');
                        $sheet->mergeCells('Y7:Z7');
                        $sheet->mergeCells('AA7:AB7');

                        $sheet->mergeCells('A'.(8+$count).':C'.(8+$count));
                        $sheet->mergeCells('B'.(9+$count).':AB'.(9+$count));
                        $sheet->mergeCells('Q'.(10+$count).':AB'.(10+$count));
                        $sheet->mergeCells('Q'.(11+$count).':AB'.(11+$count));

                        $sheet->cells('A1:AB2', function($cells) {
                            $cells->setAlignment('center');
                            $cells->setValignment('middle');
                        });
                        $sheet->cells('A5:AB'.(8+$count), function($cells) {
                            $cells->setAlignment('center');
                            $cells->setValignment('middle');
                        });
                        $sheet->cells('Q'.(8+$count).':Q'.(9+$count), function($cells) {
                            $cells->setAlignment('center');
                            $cells->setValignment('middle');
                        });
                        $sheet->setBorder('A6:AB'.(8+$count), 'thin');
                    });

                })->export('xls');
                break;
            case 3:
                $data = $this->get_student_by_group($_GET['academic_year_id'],$_GET['degree_id'],$_GET['only_foreigner']);

                $degree_name = Degree::find($_GET['degree_id'])->name_kh;
                $academic_year_name = AcademicYear::find($_GET['academic_year_id'])->name_kh;
                Excel::create('ស្ថិតិនិស្សិត តាមដេប៉ាតឺម៉ង់និងជំនាញថ្នាក់'.$degree_name, function($excel) use ($data,$degree_name,$academic_year_name) {

                    // Set the title
                    $excel->setTitle('ស្ថិតិនិស្សិត តាមអាយុ');

                    // Chain the setters
                    $excel->setCreator('Department of Study & Student Affair')
                        ->setCompany('Institute of Technology of Cambodia');

                    $excel->sheet('New sheet', function($sheet) use ($data,$degree_name,$academic_year_name) {

                        $sheet->setOrientation('landscape');
                        // Set top, right, bottom, left
                        $sheet->setPageMargin(array(
                            0.25, 0.30, 0.25, 0.30
                        ));

                        // Set all margins
                        $sheet->setPageMargin(0.25);

                        $sheet->row(1, array(
                            'ព្រះរាជាណាចក្រកម្ពុជា'
                        ));
                        $sheet->appendRow(array(
                            'ជាតិ សាសនា ព្រះមហាក្សត្រ'
                        ));
                        $sheet->appendRow(array(
                            'ក្រសួងអប់រំ យុវជន ​និងកីឡា'
                        ));
                        $sheet->appendRow(array(
                            'វិទ្យាស្ថានបច្ចេកវិទ្យាកម្ពុជា'
                        ));
                        $sheet->appendRow(array(
                            'ស្ថិតិនិស្សិត តាមអាយុ ថ្នាក់'.$degree_name.'និងតាមឆ្នាំ ឆ្នាំសិក្សា'.$academic_year_name
                        ));

                        $sheet->rows(array(
                            array('ល.រ','មហាវិទ្យាល័យ','ឯកទេស / ជំនាញ','រយៈ', 'ឆ្នាំទី១','','','','ឆ្នាំទី២','','','','ឆ្នាំទី៣','','','','ឆ្នាំទី៤','','','','ឆ្នាំទី៥','','','','សរុប','','',''),
                            array('','','','ពេល','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ','','អាហា.','', 'បង់ថ្លៃ',''),
                            array('','','','បប','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី','សរុប','ស្រី'),

                        ));

                        $key = 1;
                        $count = 1;
                        foreach ($data as $department) {
                            if($key <sizeof($data)) {
                                foreach ($department['department_options'] as $option) {
                                    $row = array($count, $department['name_kh']);
                                    array_push($row, $option['code']);
                                    array_push($row,'3');
                                    foreach ($option['data'] as $grade) {
                                        array_push($row, $grade['st']);
                                        array_push($row, $grade['sf']);
                                        array_push($row, $grade['pt']);
                                        array_push($row, $grade['pf']);
                                    }
                                    $sheet->appendRow(
                                        $row
                                    );
                                    $count++;
                                }
                            }
                            $key++;
                        }

                        $row = array('សរុប','','','');
                        foreach(end($data) as $total){
                            array_push($row, $total['st']);
                            array_push($row, $total['sf']);
                            array_push($row, $total['pt']);
                            array_push($row, $total['pf']);
                        }
                        $sheet->appendRow(
                            $row
                        );

                        $sheet->rows(array(
                            array("",'សំគាល់ៈចំពោះគ្រឹះស្ថានឧត្តមសិក្សាណា ដែលបណ្តុះបណ្តាលលើសពី៤ ឬ៥ឆ្នាំ អាចបន្តទំព័របាន'),
                            array('','','','','','','','','','','','','','','','','ធ្វើនៅ.............ថ្ងៃទី.............ខែ............ឆ្នាំ២០១...... '),
                            array('','','','','','','','','','','','','','','','','សាកលវិទ្យាធិការ/នាយក')
                        ));

                        $sheet->mergeCells('A1:AB1');
                        $sheet->mergeCells('A2:AB2');
                        $sheet->mergeCells('A3:AB3');
                        $sheet->mergeCells('A4:AB4');
                        $sheet->mergeCells('A5:AB5');
                        $sheet->mergeCells('A6:A8');
                        $sheet->mergeCells('B6:B8');
                        $sheet->mergeCells('C6:C8');

                        $sheet->mergeCells('E6:H6');
                        $sheet->mergeCells('I6:L6');
                        $sheet->mergeCells('M6:P6');
                        $sheet->mergeCells('Q6:T6');
                        $sheet->mergeCells('U6:X6');
                        $sheet->mergeCells('Y6:AB6');

                        $sheet->mergeCells('E7:F7');
                        $sheet->mergeCells('G7:H7');
                        $sheet->mergeCells('I7:J7');
                        $sheet->mergeCells('K7:L7');
                        $sheet->mergeCells('M7:N7');
                        $sheet->mergeCells('O7:P7');
                        $sheet->mergeCells('Q7:R7');
                        $sheet->mergeCells('S7:T7');
                        $sheet->mergeCells('U7:V7');
                        $sheet->mergeCells('W7:X7');
                        $sheet->mergeCells('Y7:Z7');
                        $sheet->mergeCells('AA7:AB7');

                        $sheet->mergeCells('A'.(8+$count).':C'.(8+$count));
                        $sheet->mergeCells('B'.(9+$count).':AB'.(9+$count));
                        $sheet->mergeCells('Q'.(10+$count).':AB'.(10+$count));
                        $sheet->mergeCells('Q'.(11+$count).':AB'.(11+$count));

                        $sheet->cells('A1:AB2', function($cells) {
                            $cells->setAlignment('center');
                            $cells->setValignment('middle');
                        });
                        $sheet->cells('A5:AB'.(8+$count), function($cells) {
                            $cells->setAlignment('center');
                            $cells->setValignment('middle');
                        });
                        $sheet->cells('Q'.(8+$count).':Q'.(9+$count), function($cells) {
                            $cells->setAlignment('center');
                            $cells->setValignment('middle');
                        });
                        $sheet->setBorder('A6:AB'.(8+$count), 'thin');
                    });

                })->export('xls');
                break;
            default:
        }
    }

    public function generate_group(){

    }

    public function generate_id_card(Request $request, $exam_id){


        if($request->degree_id) {

            $last_academic_year = AcademicYear::orderBy('id','desc')->first();
            $studentAnnuals = StudentAnnual::leftJoin('students','studentAnnuals.student_id','=','students.id')
                ->where([
                    ['academic_year_id',$last_academic_year->id],
                    ['studentAnnuals.grade_id',1],
                    ['studentAnnuals.degree_id', $request->degree_id]

                ])
                ->orderBy('students.name_latin','ASC')->get();

            if($studentAnnuals) {
                $index =0;
                $check =0;
                foreach($studentAnnuals as $studentAnnual) {
                    $index++;

                    if($request->degree_id == 1) {
                        $idCard = 'e'.$last_academic_year->id.str_pad($index, 4, '0', STR_PAD_LEFT);
                    } else{
                        $idCard = 'eDUT'.$last_academic_year->id.str_pad($index, 4, '0', STR_PAD_LEFT);
                    }


                    $update = DB::table('students')
                        ->where('id', $studentAnnual->student_id)
                        ->update(['id_card' => $idCard]);

                    if( $update ) {
                        $check++;
                    }
//                $test[] = 'e'.$last_academic_year->id.str_pad($index, 4, '0', STR_PAD_LEFT);
                }
            }

            if($check == count($studentAnnuals)) {
                return Response::json(array('success'=>true, 'message'=>  'IDs Generated!!'));
            } else {
                return Response::json(array('success'=>false, 'message' => 'Generate Errors'));
            }
        } else {
            return Response::json(array('success'=>false, 'message' => 'Not Seleted Degree of Student'));
        }

    }

    public function print_id_card(){
        return view('backend.studentAnnual.print.id_card');
    }

}
