<?php

namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\Account\StoreAccountRequest;
use App\Http\Requests\Backend\Configuration\Account\UpdateAccountRequest;
use App\Repositories\Backend\Account\AccountRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    /**
     * @var AccountRepositoryContract
     */
    protected $accounts;

    /**
     * @param AccountRepositoryContract $accountRepo
     */
    public function __construct(
        AccountRepositoryContract $accountRepo
    )
    {
        $this->accounts = $accountRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.account.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('backend.configuration.account.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAccountRequest $request)
    {
        $this->accounts->create($request->all());
        return redirect()->route('admin.configuration.accounts.index')->withFlashSuccess(trans('alerts.backend.general.created'));
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
        $account = $this->accounts->findOrThrowException($id);

        return view('backend.configuration.account.edit',compact('account'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccountRequest $request, $id)
    {
        $this->accounts->update($id, $request->all());
        return redirect()->route('admin.configuration.accounts.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->accounts->destroy($id);
        return redirect()->route('admin.configuration.accounts.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
    }

    public function data()
    {

        $accounts = DB::table('accounts')
            ->select(['id','name','description','active','amount_dollar','amount_riel','updated_at']);

        $datatables =  app('datatables')->of($accounts);


        return $datatables
            ->editColumn('name', '{!! str_limit($name, 60) !!}')
            ->editColumn('description', '{!! str_limit($description, 200) !!}')
            ->editColumn('active', '{!! $active==1?"<i class=\"glyphicon glyphicon-ok\"></i>":"<i class=\"glyphicon glyphicon-remove\"></i>" !!}')
            ->editColumn('amount_dollar', '{!! $amount_dollar==""? "0 $" : $amount_dollar. " $"  !!}')
            ->editColumn('amount_riel', '{!! $amount_riel==""? "0 ៛" : $amount_riel. " ៛" !!}')
            ->editColumn('updated_at', function ($account) {
                $date = Carbon::createFromFormat('Y-m-d H:i:s', $account->updated_at);
                return $date->diffForHumans();
            })
            ->addColumn('action', function ($account) {
                return  '<a href="'.route('admin.configuration.accounts.edit',$account->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.accounts.destroy', $account->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
