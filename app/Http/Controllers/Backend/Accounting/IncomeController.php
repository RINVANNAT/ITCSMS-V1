<?php namespace App\Http\Controllers\Backend\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Department;
use App\Models\School;
use App\Repositories\Backend\Income\IncomeRepositoryContract;
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
