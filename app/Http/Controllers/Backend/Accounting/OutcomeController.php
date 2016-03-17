<?php namespace App\Http\Controllers\Backend\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\OutcomeType;
use App\Models\School;
use App\Repositories\Backend\Outcome\OutcomeRepositoryContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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
    public function create()
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
    public function store(Request $request)
    {
        $this->outcomes->create($request->all());
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
    public function destroy($id)
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
                'outcomes.id as outcome_id','outcomes.number','outcomes.amount_dollar','outcomes.amount_riel','accounts.name','outcomes.payslip_client_id',
                DB::raw("CONCAT(customers.name,employees.name_kh,students.name_kh) as name")
            ]);

        $datatables =  app('datatables')->of($outcomes);


        return $datatables
            ->addColumn('action', function ($outcome) {
                return  '<a href="'.route('admin.accounting.incomes.edit',$outcome->outcome_id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.accounting.incomes.destroy', $outcome->outcome_id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

    public function client_search(Request $request){
        $input = $request->all();
        $employees = DB::table('employees')
            ->where('name_kh','LIKE','%'.$input['q']."%")
            ->select([
                'name_kh as name',
                DB::raw("'employee' as group")
            ]);
        $customers = DB::table('customers')
            ->where('name','LIKE','%'.$input['q']."%")
            ->unionAll($employees)
            ->select([
                'name',
                DB::raw("'employee' as group")
            ])->get();

        //$result = array()
        dd($customers);
    }

}
