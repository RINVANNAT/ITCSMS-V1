<?php namespace App\Http\Controllers\Backend\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\AcademicYear;
use App\Models\Degree;
use App\Models\Department;
use App\Models\DepartmentOption;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Income;
use App\Models\Outcome;
use App\Models\School;
use App\Models\SchoolFeeRate;
use App\Models\StudentAnnual;
use App\Repositories\Backend\Income\IncomeRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
        return view('backend.accounting.income.index');
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

    public function student_payment_data(Request $request) // 0 mean, scholarship id is not applied
    {
        $school_fee = SchoolFeeRate::select(['to_pay','to_pay_currency'])
            ->where('degree_id' ,1)
            ->whereIn('scholarship_id' ,[])
            ->get();

        dd($school_fee);

        $studentAnnuals = StudentAnnual::select([
            'studentAnnuals.id','students.id_card','students.name_kh','students.dob as dob','studentAnnuals.promotion_id','studentAnnuals.degree_id','studentAnnuals.grade_id','studentAnnuals.department_id',
            'students.name_latin', 'genders.code as gender', 'departmentOptions.code as option','payslip_client_id',
            DB::raw("CONCAT(degrees.code,grades.code,departments.code) as class")
        ])
            ->leftJoin('students','students.id','=','studentAnnuals.student_id')
            ->leftJoin('genders', 'students.gender_id', '=', 'genders.id')
            ->leftJoin('grades', 'studentAnnuals.grade_id', '=', 'grades.id')
            ->leftJoin('departmentOptions', 'studentAnnuals.department_option_id', '=', 'departmentOptions.id')
            ->leftJoin('departments', 'studentAnnuals.department_id', '=', 'departments.id')
            ->leftJoin('degrees', 'studentAnnuals.degree_id', '=', 'degrees.id');


        $datatables = app('datatables')->of($studentAnnuals)
            ->editColumn('dob', function ($studentAnnual){
                $date = Carbon::createFromFormat("Y-m-d h:i:s",$studentAnnual->dob);
                return $date->format('d/m/Y');
            })
            ->addColumn('details_url', function($studentAnnual) {
                return route('admin.accounting.payslipHistory.data',$studentAnnual->payslip_client_id==null?0:$studentAnnual->payslip_client_id);
            })
            ->addColumn('to_pay', function ($studentAnnual){
                $school_fee = SchoolFeeRate::select(['to_pay','to_pay_currency'])
                    ->where('promotion_id' ,$studentAnnual->promotion_id)
                    ->where('degree_id' ,$studentAnnual->degree_id)
                    ->whereIn('scholarship_id' ,[])
                    ->get();
                if($school_fee->count() > 1){
                    $school_fee = SchoolFeeRate::select(['to_pay','to_pay_currency'])
                        ->where('promotion_id' ,$studentAnnual->promotion_id)
                        ->where('degree_id' ,$studentAnnual->degree_id)
                        ->where('grade_id' ,$studentAnnual->grade_id)
                        ->get();
                    if($school_fee->count() > 1){
                        $school_fee = SchoolFeeRate::select(['to_pay','to_pay_currency'])
                            ->where('promotion_id' ,$studentAnnual->promotion_id)
                            ->where('degree_id' ,$studentAnnual->degree_id)
                            ->where('grade_id' ,$studentAnnual->grade_id)
                            ->where('department_id' ,$studentAnnual->department_id)
                            ->get();
                    }
                }
                return $school_fee->first()->to_pay." ".$school_fee->first()->to_pay_currency;
            })
            ->addColumn('debt', function ($studentAnnual){
                return "0$";
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
                return  '<a href="'.route('admin.accounting.customers.edit',$payslip->id).'"><i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>';
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::lists('name_kh','id')->toArray();
        $schools = School::lists('name_kh','id')->toArray();
        return view('backend.accounting.income.create',compact('departments','schools'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->incomes->create($request->all());
        return redirect()->route('admin.accounting.incomes.index')->withFlashSuccess(trans('alerts.backend.roles.created'));
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
        $departments = Department::lists('name_kh','id');
        $schools = School::lists('name_kh','id')->toArray();
        $income = $this->incomes->findOrThrowException($id);
        $selected_departments = $income->departments->lists('id')->toArray();
        return view('backend.accounting.income.edit',compact('income','departments','schools','selected_departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        //$student = Student::join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
        //	->select(['students.id_card','students.name_kh','students.name_latin','studentAnnuals.grade_id']);

        //$studentAnnuals = StudentAnnual::with(['student','grade'])->select(['students.id_card','students.name_kh','students.name_latin','grades.name_kh']);

        $incomes = DB::table('incomes')
            ->select(['id','number','amount_dollar','amount_riel','account_id','payslip_client_id']);

        $datatables =  app('datatables')->of($incomes);


        return $datatables
            ->editColumn('number', '{!! $number !!}')
            ->editColumn('amount_dollar', '{!! $amount_dollar !!}')
            ->editColumn('amount_riel', '{!! $amount_riel !!}')
            ->editColumn('account_id', '{!! $account_id !!}')
            ->editColumn('payslip_client_id', '{!! $payslip_client_id !!}')
            ->addColumn('action', function ($income) {
                return  '<a href="'.route('admin.accounting.incomes.edit',$income->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.accounting.incomes.destroy', $income->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
