<?php namespace App\Http\Controllers\Backend\Accounting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Accounting\Customer\CreateCustomerRequest;
use App\Http\Requests\Backend\Accounting\Customer\DeleteCustomerRequest;
use App\Http\Requests\Backend\Accounting\Customer\EditCustomerRequest;
use App\Http\Requests\Backend\Accounting\Customer\StoreCustomerRequest;
use App\Http\Requests\Backend\Accounting\Customer\UpdateCustomerRequest;
use App\Repositories\Backend\Customer\CustomerRepositoryContract;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * @var CustomerRepositoryContract
     */
    protected $customers;

    /**
     * @param CustomerRepositoryContract $customerRepo
     */
    public function __construct(
        CustomerRepositoryContract $customerRepo
    )
    {
        $this->customers = $customerRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.accounting.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateCustomerRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateCustomerRequest $request )
    {
        return view('backend.accounting.customer.create',compact('departments','schools'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreCustomerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        $this->customers->create($request->all());
        return redirect()->route('admin.accounting.customers.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
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
     * @param EditCustomerRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditCustomerRequest $request, $id)
    {
        $customer = $this->customers->findOrThrowException($id);
        return view('backend.accounting.customer.edit',compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateCustomerRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, $id)
    {
        $this->customers->update($id, $request->all());
        return redirect()->route('admin.accounting.customers.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteCustomerRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteCustomerRequest $request, $id)
    {

        $this->customers->destroy($id);
        return redirect()->route('admin.accounting.academicYears.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
    }

    public function data()
    {

        $customers = DB::table('customers')
            ->select(['id','name','company','phone','email']);

        $datatables =  app('datatables')->of($customers);


        return $datatables
            ->editColumn('name', '{!! str_limit($name, 60) !!}')
            ->editColumn('company', '{!! str_limit($company, 60) !!}')
            ->editColumn('phone', '{!! str_limit($phone, 60) !!}')
            ->editColumn('email', '{!! str_limit($email, 60) !!}')
            ->addColumn('action', function ($customer) {
                return  '<a href="'.route('admin.accounting.customers.edit',$customer->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.accounting.customers.destroy', $customer->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
