<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Student\ImportStudentRequest;
use App\Http\Requests\Backend\Student\RequestImportStudentRequest;
use App\Http\Requests\Backend\Student\StoreStudentRequest;
use App\Http\Requests\Backend\Student\UpdateStudentRequest;
use App\Models\AcademicYear;
use App\Models\Degree;
use App\Models\Department;
use App\Models\Student;
use App\Models\StudentAnnual;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class StudentAnnualController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.studentAnnual.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStudentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStudentRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function data($scholarship_id) // 0 mean, scholarship id is not applied
    {

        $keyword = "%".strtolower($_GET["search"]["value"])."%";

        $studentAnnuals = DB::table('studentAnnuals')
            ->select(['studentAnnuals.id','students.id_card','students.name_kh','students.dob','students.name_latin','grades.code as grade_code','departments.code as department_code','degrees.code as degree_code'])
            ->join('students', 'studentAnnuals.student_id', '=', 'students.id')
            ->join('grades', 'studentAnnuals.grade_id', '=', 'grades.id')
            ->join('departments', 'studentAnnuals.department_id', '=', 'departments.id')
            ->join('degrees', 'studentAnnuals.degree_id', '=', 'degrees.id');
        if($scholarship_id!= 0){
            //dd($scholarship_id);
            $studentAnnuals = $studentAnnuals
                ->join('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                ->where('scholarship_student_annual.scholarship_id','=',$scholarship_id);
        }

        $studentAnnuals = $studentAnnuals
            ->where( function ($query) use ($keyword) {

                $query
                    ->orWhere (DB::raw("LOWER(CONCAT(degrees.code,grades.code,departments.code))"),  'like', $keyword)
                    ->orWhere(DB::raw("LOWER(students.name_kh)"),  'like', $keyword)
                    ->orWhere(DB::raw("LOWER(students.name_latin)"),  'like', $keyword);
            });

        $datatables =  app('datatables')->of($studentAnnuals);

        return $datatables
            ->editColumn('id_card', '{!! str_limit($id_card, 60) !!}')
            ->editColumn('name_kh', '{!! str_limit($name_kh, 60) !!}')
            ->editColumn('name_latin', '{!! str_limit($name_latin, 60) !!}')
            ->editColumn('dob', '{!! str_limit($dob, 60) !!}')
            ->editColumn('class', '{!! $degree_code.$grade_code.$department_code !!}')
            ->addColumn('action', function ($studentAnnual) {
                return '<a href="#edit-'.$studentAnnual->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> '. trans('buttons.general.crud.edit').'</a>';
            })
            ->make(true);
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
                            $student_data = $row->toArray();
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
                        }

                        $studentAnnual = StudentAnnual::create($studentAnnual_data);
                        if($studentAnnual){
                            if(isset($studentAnnual_data['scholarship_id']) && $studentAnnual_data['scholarship_id'] != null){
                                $studentAnnual->scholarships()->attach($studentAnnual_data['scholarship_id']);
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
                $view = 'backend.studentAnnual.reporting.reporting_student_by_age';
        break;
            case 3:
                $view = 'backend.studentAnnual.reporting.reporting_student_studying';
        break;
            default:
                $view = 'backend.studentAnnual.reporting.reporting_student_by_age';
        }

        return view($view,compact('id','degrees','academicYears','departments'));
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

    public function get_student_by_group($academic_year_id , $degree){
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
        foreach($departments as &$department) {



            $empty_option = array(
                                'id'=>null,
                                'name_kh'=>$department['name_kh'],
                                'name_en'=>$department['name_en'],
                                'name_fr'=>$department['name_fr'],
                                'code'=>$department['code']
                            );

            if($degree ==2){
                $department['department_options'] = $empty_option;
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

                    $total = DB::table('studentAnnuals')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])->count();


                    $total_female = DB::table('studentAnnuals')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])
                        ->where('students.gender_id','=',2)->count(); // 2 is female

                    ;
                    $scholarship_total =  DB::table('studentAnnuals')
                        ->leftJoin('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
                        ->where('studentAnnuals.degree_id','=',$degree)
                        ->where('studentAnnuals.grade_id','=',$grade)
                        ->where('studentAnnuals.academic_year_id','=',$academic_year_id)
                        ->whereIn('scholarship_student_annual.scholarship_id',$scholarships)
                        ->where('studentAnnuals.department_id','=',$department['id'])
                        ->where('studentAnnuals.department_option_id','=',$option['id'])->count();

                    $scholarship_female =  DB::table('studentAnnuals')
                        ->leftJoin('scholarship_student_annual','studentAnnuals.id','=','scholarship_student_annual.student_annual_id')
                        ->leftJoin('students','studentAnnuals.student_id','=','students.id')
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
                return view('backend.studentAnnual.reporting.template_report_student_studying',compact('id','data','degree_name','academic_year_name'));
                break;
            case 3:

                $data = $this->get_student_by_group($_GET['academic_year_id'],$_GET['degree_id']);
                $degree_name = Degree::find($_GET['degree_id'])->name_kh;
                $academic_year_name = AcademicYear::find($_GET['academic_year_id'])->name_kh;
                if($is_preview) {
                    return view('backend.studentAnnual.reporting.template_report_student_studying', compact('id', 'data', 'degree_name', 'academic_year_name'));
                } else {
                    return view('backend.studentAnnual.reporting.print_report_student_studying',compact('id','data','degree_name','academic_year_name'));
                }
                break;
            default:
                $view = 'backend.studentAnnual.reporting.reporting_student_by_age';
        }
    }
    public function print_report($id){
        return $this->prepare_print_and_preview($id,false);
    }

    public function preview_report($id){
        return $this->prepare_print_and_preview($id,true);
    }

    public function export($id){
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
                break;
            case 3:
                break;
            default:
        }
    }

}
