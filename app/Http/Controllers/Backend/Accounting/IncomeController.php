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
use App\Models\OutcomeType;
use App\Models\PayslipClient;
use App\Models\School;
use App\Models\SchoolFeeRate;
use App\Models\Student;
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
        $outcomeTypes = OutcomeType::lists('name','id');
        $accounts = Account::lists('name','id');

        $number = 1;
        $last_income = Income::orderBy('number','DESC')->first();
        if($last_income != null){
            $number = $last_income->number + 1;
        }

        $number = str_pad($number, 5, "0", STR_PAD_LEFT);

        return view('backend.accounting.studentPayment.index',compact('departments','degrees','grades','genders','options','academicYears','outcomeTypes','accounts','number'));
    }

    public function candidate_payment()
    {
        $departments = Department::where('parent_id',11)->orderBy('id','DESC')->lists('code','id'); // 11 is for all academic departments
        $degrees = Degree::lists('name_kh','id');
        $genders = Gender::lists('name_kh','id');
        $academicYears = AcademicYear::orderBy('id','desc')->lists('name_kh','id');
        $exams = Exam::orderBy('id','desc')->lists('name','id');
        //$exams = Exam::lists('name','id');

        $number = 1;
        $last_income = Income::orderBy('number','DESC')->first();
        if($last_income != null){
            $number = $last_income->number + 1;
        }

        $number = str_pad($number, 5, "0", STR_PAD_LEFT);

        return view('backend.accounting.studentPayment.index_candidate',compact('departments','degrees','genders','exams','academicYears','number'));
    }

    public function candidate_payment_data()
    {
        $now = Carbon::now();
        $exam = Exam::where('id',Input::get('exam_id'))->first();




        $candidates = DB::table('candidates')
            ->leftJoin('origins','candidates.province_id','=','origins.id')
            ->leftJoin('gdeGrades','candidates.bac_total_grade','=','gdeGrades.id')
            ->leftJoin('genders','candidates.gender_id','=','genders.id')
            ->leftJoin('grades', 'candidates.grade_id', '=', 'grades.id')
            ->leftJoin('departments', 'candidates.department_id', '=', 'departments.id')
            ->leftJoin('degrees', 'candidates.degree_id', '=', 'degrees.id')
            ->leftJoin('promotions', 'candidates.promotion_id', '=', 'promotions.id')
            ->leftJoin('academicYears', 'candidates.academic_year_id', '=', 'academicYears.id')
            ->where([
                ['candidates.result',"Pass"],
                ['candidates.exam_id', $exam->id]
            ])
            ->orWhere('candidates.result',"Reserve")
            ->select([
                'candidates.id','candidates.payslip_client_id','candidates.name_kh','candidates.name_latin','promotions.name as promotion_name',
                'origins.name_kh as province', 'dob','result',DB::raw("CONCAT(degrees.code,grades.code,departments.code) as class"),
                'academicYears.name_kh as academic_year_name_kh','candidates.grade_id', 'candidates.degree_id','degrees.name_kh as degree_name_kh',
                'candidates.result',
                'genders.name_kh as gender_name_kh','gdeGrades.name_en as bac_total_grade']);

        /*if($now>=$exam->success_registration_start && $now<=$exam->success_registration_stop){
            $candidates = $candidates->where('candidates.result',"Pass");
            if($now>=$exam->reserve_registration_start && $now<=$exam->reserve_registration_stop){
                $candidates = $candidates->orWhere('candidates.result',"Reserve");
            }
        } else if($now>=$exam->reserve_registration_start && $now<=$exam->reserve_registration_stop){
            $candidates = $candidates->where('candidates.result',"Reserve");
        }*/

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
            ->editColumn('result',function($candidate){
                $result = "";
                if($candidate->result == "Pass"){
                    $result = "<span class='label label-success'>".$candidate->result."</span>";
                } else {
                    $result = "<span class='label label-info'>".$candidate->result."</span>";
                }
                return $result;
            })
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

                // We provide additional to pay field which attach directly to each student to avoid some data problem
                // If data is correctly input, we will no need to use this field
                if($studentAnnual->to_pay != null) {
                    $total_topay = $studentAnnual->to_pay;
                    $currency = $studentAnnual->to_pay_currency;
                    $topay = $total_topay." ".$currency;

                } else {
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
                        if($school_fee->count() == 0){ // Lack of data, request finance officer to get information on school fee rate
                            $total_topay = null;
                            $topay = "Not found";
                        } else {
                            $total_topay = floatval($school_fee->first()->to_pay);
                            $currency = $school_fee->first()->to_pay_currency;
                            $topay = $school_fee->first()->to_pay." ".$school_fee->first()->to_pay_currency;
                        }
                    }
                }

                // For Debt

                $paids = Income::select(['amount_dollar','amount_riel'])
                    ->where('payslip_client_id',$studentAnnual->payslip_client_id)
                    ->where('is_refund',false)->get();

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


        return $datatables->make(true);
    }

    public function request_register_income($id){
        return view('backend.accounting.studentPayment.popup_payment',compact('scholarship_id','departments','degrees','grades','genders','options','academicYears','origins'));
    }

    public function payslip_history($payslip_client_id){
        $incomes = Income:: select([
            'id','amount_dollar','amount_riel','number','created_at',DB::raw("'income' as name"),'is_refund'
        ])
            ->where('payslip_client_id',$payslip_client_id);

        $payslips = Outcome:: select([
            'id','amount_dollar','amount_riel','number','created_at',DB::raw("'outcome' as name"), DB::raw("'false' as is_refund"),
        ])
            ->where('payslip_client_id',$payslip_client_id)
            ->unionAll($incomes);

        return $datatables = app('datatables')->of($payslips)
            ->editColumn('number',function($payslip){
                return str_pad($payslip->number, 5, "0", STR_PAD_LEFT);
            })
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
                if($payslip->name == "income"){
                    $action = '<a href="'.route('admin.accounting.income.print',$payslip->id).'" target="_blank"><i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.print').'"></i> </a>';
                    if($payslip->is_refund != true){
                        $action = $action.' <a href="#" class="btn-refund" data-remote="'.route('admin.accounting.income.refund', $payslip->id) .'"> <i class="fa fa-reply" data-toggle="tooltip" data-placement="top" title="' . "Refund" . '"></i></a>';
                    }
                    return  $action;
                } else {
                    return  '<a href="'.route('admin.accounting.outcome.simple_print',$payslip->id).'" target="_blank"><i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.print').'"></i> </a>';
                }

            })
            ->setRowClass(function ($payslip) {
                //print_r($payslip->is_refund);
                if($payslip->is_refund == true){
                    return "refund_".$payslip->is_refund;
                }
            })
            ->make(true);
    }

    public function refund($id,UpdateIncomeRequest $request){
        $this->incomes->refund($id);
        if($request->ajax()){
            return json_encode(array('success'=>true));
        }
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
        $number = 1;
        $last_income = Income::orderBy('number','DESC')->first();
        if($last_income != null){
            $number = $last_income->number + 1;
        }

        $number = str_pad($number, 5, "0", STR_PAD_LEFT);
        return view('backend.accounting.income.create',compact('accounts','incomeTypes','number'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreIncomeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIncomeRequest $request)
    {
        $url = null;
        if(Input::get('type') == "Student" || Input::get('type') == "Candidate"){
            $url = $this->incomes->create($request->all());
            if($request->ajax()){
                return json_encode(array(
                    "sucess"=>true,
                    'payslip_client_id'=>$url,
                    'candidate_id'=>$request->get('candidate_id'),
                    'detail_url'=>$url
                    )
                );
            }
        } else {
            $this->incomes->createSimpleIncome($request->all());
            if($request->ajax()){
                return json_encode(array("sucess"=>true,'payslip_client_id'=>$url));
            }
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
                'incomeTypes.name as income_type_name', 'incomes.description',
                DB::raw("CONCAT(customers.name,employees.name_kh,students.name_kh) as name")
            ]);

        $datatables =  app('datatables')->of($incomes)
            ->filterColumn('name', 'whereRaw', "CONCAT(customers.name,employees.name_kh,students.name_kh) like ? ", ["%$1%"])
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


        $count_income = 0;
        $total_paid = 0;
        $currency = "";

        $topay = "";

        // We provide additional to pay field which attach directly to each student to avoid some data problem
        // If data is correctly input, we will no need to use this field
        if($income->payslipClient->student->to_pay != null) {
            $total_topay = $income->payslipClient->student->to_pay;
            $currency = $income->payslipClient->student->to_pay_currency;
            $topay = $total_topay." ".$currency;

        } else {
            $scholarship_ids = DB::table('scholarship_student_annual')->where('student_annual_id', $income->payslipClient->student->id)->lists('scholarship_id');
            $school_fee = SchoolFeeRate::leftJoin('department_school_fee_rate', 'schoolFeeRates.id', '=', 'department_school_fee_rate.school_fee_rate_id')
                ->leftJoin('grade_school_fee_rate', 'schoolFeeRates.id', '=', 'grade_school_fee_rate.school_fee_rate_id')
                ->where('promotion_id', $income->payslipClient->student->promotion_id)
                ->where('degree_id', $income->payslipClient->student->degree_id)
                ->where('grade_school_fee_rate.grade_id', $income->payslipClient->student->grade_id)
                ->where('department_school_fee_rate.department_id', $income->payslipClient->student->department_id);
            if (sizeof($scholarship_ids) > 0) { //This student have scholarship, so his payment might be changed
                $scolarship_fee = clone $school_fee;
                $scolarship_fee = $scolarship_fee
                    ->whereIn('scholarship_id', $scholarship_ids)
                    ->select(['to_pay', 'to_pay_currency'])
                    ->get();
                if ($scolarship_fee->count() > 0) {
                    $currency = $scolarship_fee->first()->to_pay_currency;
                    $total_topay = floatval($scolarship_fee->first()->to_pay);
                    $topay = $scolarship_fee->first()->to_pay . " " . $scolarship_fee->first()->to_pay_currency;
                } else { // Scholarships student have, doesn't change school payment fee, so we need to check it again
                    $school_fee = $school_fee
                        ->select(['to_pay', 'to_pay_currency'])
                        ->get();
                    if ($school_fee->count() == 0) {
                        $total_topay = null;
                        $topay = "Not found";
                    }
                    $total_topay = floatval($school_fee->first()->to_pay);
                    $currency = $school_fee->first()->to_pay_currency;
                    $topay = $school_fee->first()->to_pay . " " . $school_fee->first()->to_pay_currency;
                }
            } else {
                // If student doesn't have scholarship
                $school_fee = $school_fee
                    ->select(['to_pay', 'to_pay_currency'])
                    ->get();
                if ($school_fee->count() == 0) { // Lack of data, request finance officer to get information on school fee rate
                    $total_topay = null;
                    $topay = "Not found";
                } else {
                    $total_topay = floatval($school_fee->first()->to_pay);
                    $currency = $school_fee->first()->to_pay_currency;
                    $topay = $school_fee->first()->to_pay . " " . $school_fee->first()->to_pay_currency;
                }
            }

            //$student = StudentAnnual::where('payslip_client_id')
            //dd($income->payslipClient->student->to_pay['total']);
            if ($income->payslipClient->student != null) {
                $debt = ($total_topay - $income->payslipClient->student->paid) . ' ' . $currency;
            } else {
                $debt = null;
            }

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
            ->leftJoin('students','studentAnnuals.student_id','=','students.id')
            ->select([
               'incomes.number','incomes.amount_dollar','incomes.amount_riel','accounts.name as account_name','incomeTypes.name as income_type_name',
                DB::raw("CONCAT(customers.name,employees.name_kh,students.name_kh) as name"),'incomes.pay_date'
            ]);

        $title = 'ស្ថិតិចំនូល';
        // additional search
        // additional search
        if ($account = Input::get('account')) {
            $incomes = $incomes->where('outcomes.account_id', '=', $account);
            $account_obj = Account::where('id',$account)->first();
            $title.=" ក្នុងគណនី ".$account_obj->name;
        }

        if ($income_type = Input::get('income_type')) {
            $incomes = $incomes->where('incomes.income_type_id', '=', $income_type);

            $incomeType_obj = IncomeType::where('id',$income_type)->first();
            $title.=" ប្រភេទចំនូល ".$incomeType_obj->name;
        }

        if ($date_range = Input::get('date_range')) {
            $date = explode(' - ',$date_range);

            $start_date = Carbon::createFromFormat('d/m/Y',$date[0]);
            $end_date = Carbon::createFromFormat('d/m/Y',$date[1]);

            $incomes = $incomes->where('outcomes.pay_date', '<=', $end_date->endOfDay()->format('Y-m-d h:i:s'))->where('outcomes.pay_date', '>=', $start_date->startOfDay()->format('Y-m-d')." 00:00:00");

            $title.=" សំរាប់ពីថ្ងៃទី ".$date[0]. " ដល់ថ្ងៃទី ".$date[1];
        }

        $data = $incomes->get();

        Excel::create('ស្ថិតិចំនូល', function($excel) use ($data, $title) {


            // Set the title
            $excel->setTitle($title);

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
                    array('លេខ','ចំនួនទឹកប្រាក់ ដុល្លា','ចំនួនទឹកប្រាក់រៀលរ','ចុះក្នុងគណនី','ប្រភេទចំនូល','អ្នកបង់ប្រាក់','ចុះថ្ងៃទី')
                ));
                foreach ($data as $item) {

                    $array = array(
                        str_pad($item->number, 4, '0', STR_PAD_LEFT),
                        $item->amount_dollar == null?"0 $":$item->amount_dollar." $",
                        $item->amount_riel == null?"0 ៛":$item->amount_riel. " ៛",
                        $item->account_name,
                        $item->income_type_name,
                        $item->name,
                        Carbon::createFromFormat('Y-m-d h:i:s',$item->pay_date)->format('d/m/Y')
                    );
                    $sheet->appendRow(
                        $array
                    );
                }

                $sheet->mergeCells('A1:G1');
                $sheet->mergeCells('A2:G2');
                $sheet->mergeCells('A3:G3');
                $sheet->mergeCells('A4:G4');
                $sheet->mergeCells('A5:G5');

                $sheet->cells('A1:G2', function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('middle');
                });

                $sheet->cells('A5:G'.(6+count($data)), function($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('middle');
                });

                $sheet->setBorder('A6:G'.(6+count($data)), 'thin');

            });

        })->export('xls');
    }

    public function request_import(CreateIncomeRequest $request){

        return view('backend.accounting.income.import');

    }

    public function import_done(CreateIncomeRequest $request){

        return view('backend.accounting.income.import_done');

    }

    public function import(CreateIncomeRequest $request){

        $errors = array();

        $now = Carbon::now()->format('Y_m_d_H');

        // try to move uploaded file to a temporary location
        if($request->file('import')!= null){
            $import = $now. '.' .$request->file('import')->getClientOriginalExtension();

            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', $import
            );

            $storage_path = base_path() . '/public/assets/uploaded_file/temp/'.$import;

            DB::beginTransaction();

            try{
                Excel::filter('chunk')->load($storage_path)->chunk(1000, function($results) use ($errors){

                    // Loop through all rows
                    $results->each(function($row) use($errors) {
                        $income_data = $row->toArray();

                        $student = Student::where('id_card',$income_data['id_card'])
                            ->where('studentAnnuals.academic_year_id',$income_data['academic_year_id'])
                            ->join('studentAnnuals','students.id','=','studentAnnuals.student_id')->first();

                        if($student != null){ // Student with given id is found
                            $this->registerIncome($student,$income_data['pay_1_no'],$income_data['pay_1']);
                            $this->registerIncome($student,$income_data['pay_2_no'],$income_data['pay_2']);
                            $this->registerIncome($student,$income_data['pay_3_no'],$income_data['pay_3']);
                            $this->registerIncome($student,$income_data['sport_no'],$income_data['sport']);
                        } else {
                            array_push($errors,$income_data);
                        }
                    });
                });

            } catch(Exception $e){
                DB::rollback();
            }
            DB::commit();

            /*UserLog
            UserLog::log([
                'model' => 'StudentBac2',
                'action'   => 'Import', // Import, Create, Delete, Update
                'data'     => 'none', // if it is create action, store only the new id.
                'developer'   => Auth::id() == 1?true:false
            ]); */

            return redirect(route('admin.accounting.income.import_done'));
        }
    }

    public function registerIncome($studentAnnual_data, $number, $payment){

        //dd($studentAnnual_data);

        $number = str_replace(' ', '', $number);
        $payment = str_replace(' ', '', $payment);

        if(is_numeric($number) && is_numeric($payment)){  // both value can be converted to integer, so it is valid
            // Convert both of them to integer
            $number = intval($number);
            $payment = intval($payment);

            $income = new Income();

            $income->created_at = Carbon::now();
            $income->create_uid = auth()->id();
            $income->number = $number;

            if($payment < 10000){ // We consider 10000 is maximum to $ and it is sport fee, it is not good but for now ,.... yes
                $income->amount_dollar = $payment;
            } else {
                $income->amount_riel = $payment;
            }

            $income->sequence = Income::where('payslip_client_id',$studentAnnual_data->payslip_client_id)->count()+1; // Sequence of student payment
            $income->pay_date = Carbon::now();

            //dd($studentAnnual_data->degree_id);
            if($studentAnnual_data->degree_id== config('access.degrees.degree_engineer') || $studentAnnual_data->degree_id== config('access.degrees.degree_associate')){
                $income->income_type_id = config('access.income_type.income_type_student_day');
                $income->account_id = config('access.account.account_day_student');
            } else if($studentAnnual_data->degree_id== config('access.degrees.degree_bachelor')){
                $income->income_type_id = config('access.income_type.income_type_student_night');
                $income->account_id=config('access.account.account_night_student');
            } else if($studentAnnual_data->degree_id== config('access.degrees.degree_master')){
                $income->income_type_id = config('access.income_type.income_type_student_master');
                $income->account_id=config('access.account.account_master_student');
            }

            if($studentAnnual_data->payslip_client_id == "" || $studentAnnual_data->payslip_client_id == null){
                // Create client id for every student annual
                $payslip_client = new PayslipClient();
                $payslip_client->type = "Student";
                $payslip_client->create_uid =auth()->id();
                $payslip_client->save();

                $income->payslip_client_id = $payslip_client->id;
            } else {
                $income->payslip_client_id = $studentAnnual_data->payslip_client_id;
            }

            $income->save();

            return true;
        }

        return false;  // Everything is fail, return fail

    }

}
