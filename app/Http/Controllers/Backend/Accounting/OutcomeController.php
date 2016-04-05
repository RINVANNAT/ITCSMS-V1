<?php namespace App\Http\Controllers\Backend\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Accounting\Outcome\CreateOutcomeRequest;
use App\Http\Requests\Backend\Accounting\Outcome\DeleteOutcomeRequest;
use App\Http\Requests\Backend\Accounting\Outcome\StoreOutcomeRequest;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Outcome;
use App\Models\OutcomeType;
use App\Models\School;
use App\Repositories\Backend\Outcome\OutcomeRepositoryContract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;

class OutcomeController extends Controller
{
    /**
     * @var OutcomeRepositoryContract
     */
    protected $outcomes;

    /**
     * @param OutcomeRepositoryContract $outcomeRepo
     */
    public function __construct(
        OutcomeRepositoryContract $outcomeRepo
    )
    {
        $this->outcomes = $outcomeRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Account::lists('name','id');
        $outcomeTypes = OutcomeType::lists('name','id');
        return view('backend.accounting.outcome.index',compact('accounts','outcomeTypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateOutcomeRequest $request)
    {
        $outcomeTypes = OutcomeType::get()->lists('codeName','id');
        $accounts = Account::lists('name','id');
        return view('backend.accounting.outcome.create',compact('outcomeTypes','accounts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreOutcomeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOutcomeRequest $request)
    {
        $this->outcomes->create($request);
        return redirect()->route('admin.accounting.outcomes.index')->withFlashSuccess(trans('alerts.backend.roles.created'));
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
        $outcome = $this->outcomes->findOrThrowException($id);
        $selected_departments = $outcome->departments->lists('id')->toArray();
        return view('backend.accounting.outcome.edit',compact('outcome','departments','schools','selected_departments'));
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
        $this->outcomes->update($id, $request->all());
        return redirect()->route('admin.accounting.outcomes.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteOutcomeRequest $request, $id)
    {
        $this->outcomes->destroy($id);
        return redirect()->route('admin.accounting.academicYears.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
    }

    public function data()
    {
        //$student = Student::join('studentAnnuals', 'studentAnnuals.student_id', '=', 'students.id')
        //	->select(['students.id_card','students.name_kh','students.name_latin','studentAnnuals.grade_id']);

        //$studentAnnuals = StudentAnnual::with(['student','grade'])->select(['students.id_card','students.name_kh','students.name_latin','grades.name_kh']);

        $outcomes = DB::table('outcomes')
            ->leftJoin('accounts','outcomes.account_id','=','accounts.id')
            ->leftJoin('outcomeTypes','outcomes.outcome_type_id','=','outcomeTypes.id')
            ->leftJoin('employees','outcomes.payslip_client_id','=','employees.payslip_client_id')
            ->leftJoin('studentAnnuals','outcomes.payslip_client_id','=','studentAnnuals.payslip_client_id')
            ->leftJoin('customers','outcomes.payslip_client_id','=','customers.payslip_client_id')
            ->leftJoin('students','studentAnnuals.student_id','=','students.id')
            ->select([
                'outcomes.id as outcome_id','outcomes.number','outcomes.amount_dollar','outcomes.amount_riel','accounts.name as account_name',
                'outcomes.payslip_client_id','outcomeTypes.name as outcome_type_name',
                DB::raw("CONCAT(customers.name,employees.name_kh,students.name_kh) as name")
            ]);

        //dd($outcomes);
        $datatables =  app('datatables')->of($outcomes)
            ->editColumn('number','{{str_pad($number, 4, "0", STR_PAD_LEFT)}}')
            ->editColumn('amount_dollar', '{!! $amount_dollar==null? "0 $" :$amount_dollar. " $" !!}')
            ->editColumn('amount_riel', '{!! $amount_riel==null? "0 ៛": $amount_riel. " ៛" !!}')
            ->addColumn('action', function ($outcome) {
                //return  '<a href="'.route('admin.accounting.incomes.edit',$outcome->outcome_id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                //' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.accounting.incomes.destroy', $outcome->outcome_id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
                return  '<a href="'.route('admin.accounting.outcome.simple_print',$outcome->outcome_id).'" class="btn btn-xs btn-primary"><i class="fa fa-print" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>';
            });


        // additional search
        if ($account = $datatables->request->get('account')) {
            $datatables->where('outcomes.account_id', '=', $account);
        }

        if ($outcome_type = $datatables->request->get('outcome_type')) {
            $datatables->where('outcomes.outcome_type_id', '=', $outcome_type);
        }

        if ($date_range = $datatables->request->get('date_range')) {
            $date = explode(' - ',$date_range);

            $start_date = Carbon::createFromFormat('d/m/Y',$date[0])->startOfDay()->format('Y-m-d')." 00:00:00";
            $end_date = Carbon::createFromFormat('d/m/Y',$date[1])->endOfDay()->format('Y-m-d h:i:s');

            $datatables->where('outcomes.pay_date', '<=', $end_date)->where('outcomes.pay_date', '>=', $start_date);
        }

        $data =  $datatables->with(['total_dollar' => 1000,'total_riel'=>1000])->make(true);
        //$data->data->total_dollar = 1000;
        //$data->data->total_riel = 1000;
        return $data;
    }

    public function client_search(Request $request){
        if($request->ajax()) {

            $page = Input::get('page');
            $resultCount = 25;
            $offset = ($page - 1) * $resultCount;
            $employees = DB::table('employees')
                ->where('employees.name_kh', 'LIKE', '%' . Input::get("term") . "%")
                ->leftJoin('departments','departments.id','=','employees.department_id')
                ->select([
                    'employees.id as id',
                    'departments.name_kh as department',
                    'payslip_client_id',
                    'employees.name_kh as name',
                    DB::raw("'Staff' as group")
                ]);
            $customers = DB::table('customers')
                ->where('name', 'LIKE', '%' . Input::get("term") . "%")
                ->select([
                    'id',
                    'company as department',
                    'payslip_client_id',
                    'name',
                    DB::raw("'Other' as group")
                ]);

            $client = $customers
                ->unionAll($employees)
                ->orderBy('name')
                ->skip($offset)
                ->take($resultCount)
                ->get();

            $count = Count($customers->unionAll($employees)->get());
            $endCount = $offset + $resultCount;
            $morePages = $count > $endCount;

            $results = array(
                'results' => $client,
                'pagination' => array(
                    "more" => $morePages
                )
            );
            return response()->json($results);
        }
    }

    /**
     * @param $id
     * @return Print simple outcome
     */
    public function print_simple_outcome($id){


        $outcome = Outcome::where('id',$id)->with([
            "payslipClient",
            "payslipClient.employee",
            "payslipClient.employee.department",
            "payslipClient.customer",
            "payslipClient.student",
            "payslipClient.student.student",
            "payslipClient.student.student.gender",
            "payslipClient.student.department",
            "payslipClient.student.grade",
            "payslipClient.student.promotion",
            "payslipClient.student.academic_year",
            "outcomeType"
        ])->first();

        return view('backend.accounting.outcome.print.simple_print',compact('outcome'));
    }

    public function export(){

        $outcomes = DB::table('outcomes')
            ->leftJoin('accounts','outcomes.account_id','=','accounts.id')
            ->leftJoin('outcomeTypes','outcomes.outcome_type_id','=','outcomeTypes.id')
            ->leftJoin('employees','outcomes.payslip_client_id','=','employees.payslip_client_id')
            ->leftJoin('studentAnnuals','outcomes.payslip_client_id','=','studentAnnuals.payslip_client_id')
            ->leftJoin('customers','outcomes.payslip_client_id','=','customers.payslip_client_id')
            ->leftJoin('students','studentAnnuals.student_id','=','students.id')
            ->select([
                'outcomes.number','outcomes.amount_dollar','outcomes.amount_riel','accounts.name as account_name','outcomeTypes.name as outcome_type_name',
                DB::raw("CONCAT(customers.name,employees.name_kh,students.name_kh) as name"),'outcomes.pay_date'
            ]);

        $title = 'ស្ថិតិចំណាយ';
        // additional search
        // additional search
        if ($account = Input::get('account')) {
            $outcomes = $outcomes->where('outcomes.account_id', '=', $account);
        }

        if ($outcome_type = Input::get('outcome_type')) {
            $outcomes = $outcomes->where('outcomes.outcome_type_id', '=', $outcome_type);
        }

        if ($date_range = Input::get('date_range')) {
            $date = explode(' - ',$date_range);

            $start_date = Carbon::createFromFormat('d/m/Y',$date[0])->startOfDay()->format('Y-m-d')." 00:00:00";
            $end_date = Carbon::createFromFormat('d/m/Y',$date[1])->endOfDay()->format('Y-m-d h:i:s');

            $outcomes = $outcomes->where('outcomes.pay_date', '<=', $end_date)->where('outcomes.pay_date', '>=', $start_date);
        }

        $data = $outcomes->get();


        Excel::create('ស្ថិតិចំណាយ', function($excel) use ($data, $title) {


            // Set the title
            $excel->setTitle('ស្ថិតិចំណាយ');

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
                        $item->outcome_type_name,
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

}
