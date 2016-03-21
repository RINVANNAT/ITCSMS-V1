<?php namespace App\Http\Controllers\Backend\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Accounting\Income\CreateIncomeRequest;
use App\Http\Requests\Backend\Accounting\Income\EditIncomeRequest;
use App\Http\Requests\Backend\Accounting\Income\StoreIncomeRequest;
use App\Http\Requests\Backend\Accounting\Income\UpdateIncomeRequest;
use App\Models\AcademicYear;
use App\Models\Account;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Exam;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Income;
use App\Models\IncomeType;
use App\Models\Outcome;
use App\Models\PayslipClient;
use App\Models\School;
use App\Models\SchoolFeeRate;
use App\Models\StudentAnnual;
use App\Repositories\Backend\Income\IncomeRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class IncomeController extends Controller
{
    /**
     * @var IncomeRepositoryContract
     */
    protected $incomes;

    /**
     * @param IncomeRepositoryContract $incomeRepo
     */
    public function __construct(
        IncomeRepositoryContract $incomeRepo
    )
    {
        $this->incomes = $incomeRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Account::lists('name','id');
        $incomeTypes = IncomeType::lists('name','id');
        return view('backend.accounting.income.index',compact('accounts','incomeTypes'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function student_payment()
    {
        $departments = Department::where('parent_id',11)->orderBy('id','DESC')->lists('code','id'); // 11 is for all academic departments
        $degrees = Degree::lists('name_kh','id');
        $grades = Grade::lists('name_kh','id');
        $genders = Gender::lists('name_kh','id');
        $options = DepartmentOption::lists('code','id');
        $academicYears = AcademicYear::orderBy('id','desc')->lists('name_kh','id');

        return view('backend.accounting.studentPayment.index',compact('departments','degrees','grades','genders','options','academicYears'));
    }

    public function candidate_payment()
    {
        $departments = Department::where('parent_id',11)->orderBy('id','DESC')->lists('code','id'); // 11 is for all academic departments
        $degrees = Degree::lists('name_kh','id');
        $genders = Gender::lists('name_kh','id');
        $academicYears = AcademicYear::orderBy('id','desc')->lists('name_kh','id');
        $exams = Exam::lists('name','id');

        return view('backend.accounting.studentPayment.index_candidate',compact('departments','degrees','genders','exams','academicYears'));
    }

    public function candidate_payment_data()
    {
        $candidates = DB::table('candidates')
            ->leftJoin('origins','candidates.province_id','=','origins.id')
            ->leftJoin('gdeGrades','candidates.bac_total_grade','=','gdeGrades.id')
            ->leftJoin('genders','candidates.gender_id','=','genders.id')
            ->leftJoin('grades', 'candidates.grade_id', '=', 'grades.id')
            ->leftJoin('departments', 'candidates.department_id', '=', 'departments.id')
            ->leftJoin('degrees', 'candidates.degree_id', '=', 'degrees.id')
            ->leftJoin('promotions', 'candidates.promotion_id', '=', 'promotions.id')
            ->leftJoin('academicYears', 'candidates.academic_year_id', '=', 'academicYears.id')
            ->select([
                'candidates.id','candidates.payslip_client_id','candidates.name_kh','candidates.name_latin','promotions.name as promotion_name',
                'origins.name_kh as province', 'dob','result',DB::raw("CONCAT(degrees.code,grades.code,departments.code) as class"),
                'academicYears.name_kh as academic_year_name_kh','candidates.grade_id', 'candidates.degree_id','degrees.name_kh as degree_name_kh',
                'genders.name_kh as gender_name_kh','gdeGrades.name_en as bac_total_grade'])
            ->where('candidates.result','Pass');

        if($exam_id = Input::get('exam_id')){
            $candidates = $candidates->where('exam_id',$exam_id);
        }
        if($academic_year_id = Input::get('academic_year_id')){
            $candidates = $candidates->where('academic_year_id',$academic_year_id);
        }
        if($degree_id = Input::get('degree_id')){
            $candidates = $candidates->where('degree_id',$degree_id);
        }
        if($department_id = Input::get('department_id')){
            $candidates = $candidates->where('department_id',$department_id);
        }
        if($gender_id = Input::get('gender_id')){
            $candidates = $candidates->where('gender_id',$gender_id);
        }

        $datatables =  app('datatables')->of($candidates);


        return $datatables
            ->editColumn('dob',function($candidate){
                $date = Carbon::createFromFormat('Y-m-d h:i:s',$candidate->dob);
                return $date->format('d/m/Y');
            })
            ->addColumn('details_url', function($candidate) {
                return route('admin.accounting.payslipHistory.data',$candidate->payslip_client_id==null?0:$candidate->payslip_client_id);
            })
            ->addColumn('count_income', 0)
            ->make(true);
    }

    public function student_payment_data(\Illuminate\Http\Request $request) // 0 mean, scholarship id is not applied
    {
        $total_topay = 0;
        $debt = "";
        $count_income = 0;

        $studentAnnuals = StudentAnnual::select([
            'studentAnnuals.id','students.id_card','students.name_kh','students.dob as dob','studentAnnuals.promotion_id','studentAnnuals.degree_id','studentAnnuals.grade_id','studentAnnuals.department_id',
            'students.name_latin', 'genders.code as gender', 'departmentOptions.code as option','payslip_client_id','degrees.name_kh as degree_name_kh','departments.name_kh as department_name_kh',
            'promotions.name as promotion_name', 'academicYears.name_kh as academic_year_name_kh',
            DB::raw("CONCAT(degrees.code,grades.code,departments.code) as class")
        ])
            ->leftJoin('students','students.id','=','studentAnnuals.student_id')
            ->leftJoin('genders', 'students.gender_id', '=', 'genders.id')
            ->leftJoin('grades', 'studentAnnuals.grade_id', '=', 'grades.id')
            ->leftJoin('departmentOptions', 'studentAnnuals.department_option_id', '=', 'departmentOptions.id')
            ->leftJoin('departments', 'studentAnnuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees', 'studentAnnuals.degree_id', '=', 'degrees.id')
            ->leftJoin('promotions', 'studentAnnuals.promotion_id', '=', 'promotions.id')
            ->leftJoin('academicYears', 'studentAnnuals.academic_year_id', '=', 'academicYears.id');


        $datatables = app('datatables')->of($studentAnnuals)
            ->editColumn('dob', function ($studentAnnual){
                $date = Carbon::createFromFormat("Y-m-d h:i:s",$studentAnnual->dob);
                return $date->format('d/m/Y');
            })
            ->addColumn('details_url', function($studentAnnual) {
                return route('admin.accounting.payslipHistory.data',$studentAnnual->payslip_client_id==null?0:$studentAnnual->payslip_client_id);
            })
            ->addColumn('to_pay', function ($studentAnnual) {
                global $total_topay;
                global $debt;
                global $count_income;

                $count_income = 0;
                $total_paid = 0;
                $currency = "";

                $topay = "";

                $scholarship_ids = DB::table('scholarship_student_annual')->where('student_annual_id',$studentAnnual->id)->lists('scholarship_id');
                $school_fee = SchoolFeeRate::leftJoin('department_school_fee_rate','schoolFeeRates.id','=','department_school_fee_rate.school_fee_rate_id')
                    ->leftJoin('grade_school_fee_rate','schoolFeeRates.id','=','grade_school_fee_rate.school_fee_rate_id')
                    ->where('promotion_id' ,$studentAnnual->promotion_id)
                    ->where('degree_id' ,$studentAnnual->degree_id)
                    ->where('grade_school_fee_rate.grade_id' ,$studentAnnual->grade_id)
                    ->where('department_school_fee_rate.department_id' ,$studentAnnual->department_id);
                if(sizeof($scholarship_ids)>0){ //This student have scholarship, so his payment might be changed
                    $scolarship_fee = clone $school_fee;
                    $scolarship_fee = $scolarship_fee
                        ->whereIn('scholarship_id' ,$scholarship_ids)
                        ->select(['to_pay','to_pay_currency'])
                        ->get();
                    if($scolarship_fee->count() > 0){
                        $currency = $scolarship_fee->first()->to_pay_currency;
                        $total_topay = floatval($scolarship_fee->first()->to_pay);
                        $topay = $scolarship_fee->first()->to_pay." ".$scolarship_fee->first()->to_pay_currency;
                    } else { // Scholarships student have, doesn't change school payment fee, so we need to check it again
                        $school_fee = $school_fee
                            ->select(['to_pay','to_pay_currency'])
                            ->get();
                        if($school_fee->count() == 0){
                            $total_topay = null;
                            $topay = "Not found";
                        }
                        $total_topay = floatval($school_fee->first()->to_pay);
                        $currency = $school_fee->first()->to_pay_currency;
                        $topay = $school_fee->first()->to_pay." ".$school_fee->first()->to_pay_currency;
                    }
                } else {
                    // If student doesn't have scholarship
                    $school_fee = $school_fee
                        ->select(['to_pay','to_pay_currency'])
                        ->get();
                    if($school_fee->count() == 0){
                        $total_topay = null;
                        $topay = "Not found";
                    }
                    $total_topay = floatval($school_fee->first()->to_pay);
                    $currency = $school_fee->first()->to_pay_currency;
                    $topay = $school_fee->first()->to_pay." ".$school_fee->first()->to_pay_currency;
                }


                // For Debt

                $paids = Income::select(['amount_dollar','amount_riel'])
                    ->where('payslip_client_id',$studentAnnual->payslip_client_id)->get();

                foreach($paids as $paid){
                    $count_income++;
                    if($paid->amount_dollar != ''){
                        $total_paid += floatval($paid->amount_dollar);
                    } else {
                        $total_paid += floatval($paid->amount_riel);
                    }
                }

                $debt = ($total_topay - $total_paid) . $currency;

                return $topay;

            })
            ->addColumn('debt', function ($studentAnnual)  {
                global $debt;

                return $debt;
            })
            ->addColumn('count_income', function ($studentAnnual)  {
                global $count_income;

                return $count_income;
            });

        // additional search
        if ($academic_year = $datatables->request->get('academic_year')) {
            $datatables->where('studentAnnuals.academic_year_id', '=', $academic_year);
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


        return $datatables->make(true);
    }

    public function payslip_history($payslip_client_id){
        $incomes = Income:: select([
            'id','amount_dollar','amount_riel','number','created_at',DB::raw("'income' as name")
        ])
            ->where('payslip_client_id',$payslip_client_id);

        $payslips = Outcome:: select([
            'id','amount_dollar','amount_riel','number','created_at',DB::raw("'outcome' as name")
        ])
            ->where('payslip_client_id',$payslip_client_id)
            ->unionAll($incomes);

        return $datatables = app('datatables')->of($payslips)
            ->addColumn('income', function ($payslip){
                if($payslip->name == "income"){
                    if($payslip->amount_dollar != ""){
                        return $payslip->amount_dollar . " $";
                    } else {
                        return $payslip->amount_riel . " ៛";
                    }
                } else {
                    return "";
                }

            })
            ->addColumn('outcome', function ($payslip){
                if($payslip->name == "outcome"){
                    if($payslip->amount_dollar != ""){
                        return $payslip->amount_dollar . " $";
                    } else {
                        return $payslip->amount_riel . " ៛";
                    }
                } else {
                    return "";
                }
            })
            ->addColumn('action', function ($payslip) {
                return  '<a href="'.route('admin.accounting.income.print',$payslip->id).'" target="_blank"><i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.print').'"></i> </a>';
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateIncomeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateIncomeRequest $request)
    {
        $accounts = Account::lists('name','id')->toArray();
        $incomeTypes = IncomeType::lists('name','id')->toArray();
        return view('backend.accounting.income.create',compact('accounts','incomeTypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreIncomeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIncomeRequest $request)
    {
        if(Input::get('type') == "Student" || Input::get('type') == "Candidate"){
            $this->incomes->create($request->all());
        } else {
            $this->incomes->createSimpleIncome($request->all());
        }

        if($request->ajax()){
            return json_encode(array("sucess"=>true));
        }
        return redirect()->route('admin.accounting.incomes.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
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
     * @param EditIncomeRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditIncomeRequest $request, $id)
    {
        $departments = Department::lists('name_kh','id');
        $schools = School::lists('name_kh','id')->toArray();
        $income = $this->incomes->findOrThrowException($id);
        $selected_departments = $income->departments->lists('id')->toArray();
        return view('backend.accounting.income.edit',compact('income','departments','schools','selected_departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateIncomeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIncomeRequest $request, $id)
    {
        $this->incomes->update($id, $request->all());
        return redirect()->route('admin.accounting.incomes.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->incomes->destroy($id);
        return redirect()->route('admin.accounting.academicYears.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
    }

    public function data()
    {
        $incomes = DB::table('incomes')
            ->leftJoin('accounts','incomes.account_id','=','accounts.id')
            ->leftJoin('incomeTypes','incomes.income_type_id','=','incomeTypes.id')
            ->leftJoin('employees','incomes.payslip_client_id','=','employees.payslip_client_id')
            ->leftJoin('studentAnnuals','incomes.payslip_client_id','=','studentAnnuals.payslip_client_id')
            ->leftJoin('customers','incomes.payslip_client_id','=','customers.payslip_client_id')
            ->leftJoin('students','studentAnnuals.student_id','=','students.id')
            ->select([
                'incomes.id','incomes.number','incomes.amount_dollar','incomes.amount_riel','incomes.payslip_client_id','accounts.name as account_name',
                'incomeTypes.name as income_type_name',
                DB::raw("CONCAT(customers.name,employees.name_kh,students.name_kh) as name")
            ]);

        $datatables =  app('datatables')->of($incomes)
            ->editColumn('number','{{str_pad($number, 5, "0", STR_PAD_LEFT)}}')
            ->editColumn('amount_dollar','{{$amount_dollar==""?0:$amount_dollar." $"}}')
            ->editColumn('amount_riel','{{$amount_riel==null?0:$amount_riel." ៛"}}')
            ->addColumn('action', function ($income) {
                //return  '<a href="'.route('admin.accounting.income.simple_print',$income->id).'" class="btn btn-xs btn-primary"><i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>';
                return  '<a href="#" class="btn btn-xs btn-primary"><i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>';
            });

        // additional search
        if ($account = $datatables->request->get('account')) {
            $datatables->where('incomes.account_id', '=', $account);
        }

        if ($income_type = $datatables->request->get('income_type')) {
            $datatables->where('incomes.income_type_id', '=', $income_type);
        }

        if ($date_range = $datatables->request->get('date_range')) {
            $date = explode(' - ',$date_range);

            $start_date = Carbon::createFromFormat('d/m/Y',$date[0])->startOfDay()->format('Y-m-d')." 00:00:00";
            $end_date = Carbon::createFromFormat('d/m/Y',$date[1])->endOfDay()->format('Y-m-d h:i:s');

            $datatables->where('incomes.pay_date', '<=', $end_date)->where('incomes.pay_date', '>=', $start_date);
        }

        return $datatables->make(true);
    }

    /**
     * @param $id
     * @return Print simple income
     */
    public function print_simple_income($id){

        return true;

        $income = Income::where('id',$id)->with([
            "payslipClient",
            "payslipClient.student",
            "payslipClient.student.student",
            "payslipClient.student.student.gender",
            "payslipClient.student.department",
            "payslipClient.student.grade",
            "payslipClient.student.promotion",
            "payslipClient.student.academic_year",
        ])->first();


        return view('backend.accounting.studentPayment.print.payslip_print',compact('income','count','debt'));
    }

    public function print_income($id){

        $income = Income::where('id',$id)->with([
            "payslipClient",
            "payslipClient.student",
            "payslipClient.student.student",
            "payslipClient.student.student.gender",
            "payslipClient.student.department",
            "payslipClient.student.grade",
            "payslipClient.student.promotion",
            "payslipClient.student.academic_year",
            "payslipClient.candidate.gender",
            "payslipClient.candidate.department",
            "payslipClient.candidate.grade",
            "payslipClient.candidate.promotion",
            "payslipClient.candidate.academic_year",
        ])->first();

        //$student = StudentAnnual::where('payslip_client_id')
        //dd($income->payslipClient->student->to_pay['total']);
        if($income->payslipClient->student!= null){
            $debt = ($income->payslipClient->student->to_pay['total'] - $income->payslipClient->student->paid).' '.$income->payslipClient->student->to_pay['currency'];
        } else {
            $debt = null;
        }


        //$count = Income::where('payslip_client_id',$income->payslipClient->id)->count()+1;
        return view('backend.accounting.studentPayment.print.payslip_print',compact('income','count','debt'));
    }

    public function print_student_payment($studentId){

        $student = StudentAnnual::where('id',$studentId)
            ->with(['payslipClient','payslipClient.incomes'])
            ->first();

        dd($student->paid);
        return view('backend.accounting.studentPayment.print.payslip_print',compact('income','count'));
    }

    public function export(){

        $incomes = DB::table('incomes')
            ->leftJoin('accounts','incomes.account_id','=','accounts.id')
            ->leftJoin('incomeTypes','incomes.income_type_id','=','incomeTypes.id')
            ->leftJoin('employees','incomes.payslip_client_id','=','employees.payslip_client_id')
            ->leftJoin('studentAnnuals','incomes.payslip_client_id','=','studentAnnuals.payslip_client_id')
            ->leftJoin('customers','incomes.payslip_client_id','=','customers.payslip_client_id')
            ->leftJoin('students','studentAnnuals.student_id','=','students.id');

        $title = 'ស្ថិតិចំនូល';
        // additional search
        // additional search
        if ($account = Input::get('account')) {
            $incomes = $incomes->where('outcomes.account_id', '=', $account);
        }

        if ($outcome_type = Input::get('outcome_type')) {
            $incomes = $incomes->where('outcomes.outcome_type_id', '=', $outcome_type);
        }

        if ($date_range = Input::get('date_range')) {
            $date = explode(' - ',$date_range);

            $start_date = Carbon::createFromFormat('d/m/Y',$date[0])->startOfDay()->format('Y-m-d')." 00:00:00";
            $end_date = Carbon::createFromFormat('d/m/Y',$date[1])->endOfDay()->format('Y-m-d h:i:s');

            $incomes = $incomes->where('outcomes.pay_date', '<=', $end_date)->where('outcomes.pay_date', '>=', $start_date);
        }

        $data = $incomes->get();


        Excel::create('ស្ថិតិចំនូល', function($excel) use ($data, $title) {


            // Set the title
            $excel->setTitle('ស្ថិតិចំនូល');

            // Chain the setters
            $excel->setCreator('Department of Finance')
                ->setCompany('Institute of Technology of Cambodia');

            $excel->sheet('New sheet', function($sheet) use ($data,$title) {

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

                $sheet->rows(array(
                    array('លរ','អត្តលេខ','ឈ្មោះខ្មែរ','ឈ្មោះឡាតាំង','ថ្ងៃខែឆ្នាំកំណើត','ភេទ','មកពី','ថ្នាក់','ជំនាញ')
                ));
                foreach ($data as $item) {

                    $sheet->appendRow(
                        $item
                    );
                }

                $sheet->mergeCells('A1:I1');
                $sheet->mergeCells('A2:I2');
                $sheet->mergeCells('A3:I3');
                $sheet->mergeCells('A4:I4');
                $sheet->mergeCells('A5:I5');

                $sheet->cells('A1:I2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('middle');
                });

                $sheet->cells('A5:I'.(6+count($data)), function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('middle');
                });

                $sheet->setBorder('A6:I'.(6+count($data)), 'thin');

            });

        })->export('xls');
    }

}
