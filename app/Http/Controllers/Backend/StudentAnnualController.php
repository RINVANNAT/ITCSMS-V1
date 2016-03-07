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

    public function data()
    {
        $studentAnnuals = DB::table('studentAnnuals')
            ->join('students', 'studentAnnuals.student_id', '=', 'students.id')
            ->join('grades', 'studentAnnuals.grade_id', '=', 'grades.id')
            ->join('departments', 'studentAnnuals.department_id', '=', 'departments.id')
            ->join('degrees', 'studentAnnuals.degree_id', '=', 'degrees.id')
            ->select(['studentAnnuals.id','students.id_card','students.name_kh','students.name_latin','grades.code as grade_code','departments.code as department_code','degrees.code as degree_code']);

        $datatables =  app('datatables')->of($studentAnnuals);
        $keyword = "%".strtolower($_GET["search"]["value"])."%";
        $datatables->orWhere(DB::raw("LOWER(CONCAT(degrees.code,grades.code,departments.code))"),  'like', $keyword);
        $datatables->orWhere(DB::raw("LOWER(students.name_kh)"),  'like', $keyword);
        $datatables->orWhere(DB::raw("LOWER(students.name_latin)"),  'like', $keyword);


        return $datatables
            ->editColumn('id_card', '{!! str_limit($id_card, 60) !!}')
            ->editColumn('name_kh', '{!! str_limit($name_kh, 60) !!}')
            ->editColumn('name_latin', '{!! str_limit($name_latin, 60) !!}')
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
            default:
                $view = 'backend.studentAnnual.reporting.reporting_student_by_age';
        }

        return view($view,compact('id','degrees','academicYears','departments'));
    }

    public function reporting_data(Request $request, $id){

        if($request->ajax()){
            $data = "";
            switch ($id) {
                case 1:
                    break;
                case 2:
                    break;
                case 3:
                    break;
                default:
            }
            return $data;
        }
    }

}
