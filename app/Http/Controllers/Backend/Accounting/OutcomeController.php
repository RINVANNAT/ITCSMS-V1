<?php namespace App\Http\Controllers\Backend\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Accounting\Outcome\CreateOutcomeRequest;
use App\Http\Requests\Backend\Accounting\Outcome\DeleteOutcomeRequest;
use App\Http\Requests\Backend\Accounting\Outcome\StoreOutcomeRequest;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\OutcomeType;
use App\Models\School;
use App\Repositories\Backend\Outcome\OutcomeRepositoryContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

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
        return view('backend.accounting.outcome.index');
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
     * @param  \Illuminate\Http\Request  $request
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
            ->leftJoin('employees','outcomes.payslip_client_id','=','employees.payslip_client_id')
            ->leftJoin('studentAnnuals','outcomes.payslip_client_id','=','studentAnnuals.payslip_client_id')
            ->leftJoin('customers','outcomes.payslip_client_id','=','customers.payslip_client_id')
            ->leftJoin('students','studentAnnuals.student_id','=','students.id')
            ->select([
                'outcomes.id as outcome_id','outcomes.number','outcomes.amount_dollar','outcomes.amount_riel','accounts.name as account_name','outcomes.payslip_client_id',
                DB::raw("CONCAT(customers.name,employees.name_kh,students.name_kh) as name")
            ]);

        //dd($outcomes);
        $datatables =  app('datatables')->of($outcomes);


        return $datatables
            ->editColumn('amount_dollar', '{!! $amount_dollar . " $" !!}')
            ->editColumn('amount_riel', '{!! $amount_riel==null? "0 ៛": $amount_riel. " ៛" !!}')
            ->addColumn('action', function ($outcome) {
                return  '<a href="'.route('admin.accounting.incomes.edit',$outcome->outcome_id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.accounting.incomes.destroy', $outcome->outcome_id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
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

}
