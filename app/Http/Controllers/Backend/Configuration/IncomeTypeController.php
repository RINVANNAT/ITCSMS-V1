<?php namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\IncomeType\CreateIncomeTypeRequest;
use App\Http\Requests\Backend\Configuration\IncomeType\DeleteIncomeTypeRequest;
use App\Http\Requests\Backend\Configuration\IncomeType\EditIncomeTypeRequest;
use App\Http\Requests\Backend\Configuration\IncomeType\StoreIncomeTypeRequest;
use App\Http\Requests\Backend\Configuration\IncomeType\UpdateIncomeTypeRequest;
use App\Repositories\Backend\IncomeType\IncomeTypeRepositoryContract;
use Illuminate\Support\Facades\DB;

class IncomeTypeController extends Controller
{

    /**
     * @var IncomeTypeRepositoryContract
     */
    protected $incomeTypes;

    /**
     * @param IncomeTypeRepositoryContract $incomeTypeRepo
     */
    public function __construct(
        IncomeTypeRepositoryContract $incomeTypeRepo
    )
    {
        $this->incomeTypes = $incomeTypeRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.incomeType.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateIncomeTypeRequest $request)
    {
        return view('backend.configuration.incomeType.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIncomeTypeRequest $request)
    {
        $this->incomeTypes->create($request->all());
        return redirect()->route('admin.configuration.incomeTypes.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
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
    public function edit(EditIncomeTypeRequest $request, $id)
    {

        $incomeType = $this->incomeTypes->findOrThrowException($id);

        return view('backend.configuration.incomeType.edit',compact('incomeType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIncomeTypeRequest $request, $id)
    {
        $this->incomeTypes->update($id, $request->all());
        return redirect()->route('admin.configuration.incomeTypes.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteIncomeTypeRequest $request, $id)
    {
        $this->incomeTypes->destroy($id);
        return true;
    }

    public function data()
    {
        $incomeTypes = DB::table('incomeTypes')
            ->select(['id','name','active','description']);

        $datatables =  app('datatables')->of($incomeTypes);


        return $datatables
            ->editColumn('name', '{!! str_limit($name, 60) !!}')
            ->editColumn('active', '{!! $active==1?"<i class=\"glyphicon glyphicon-ok\"></i>":"<i class=\"glyphicon glyphicon-remove\"></i>" !!}')
            ->editColumn('description', '{!! str_limit($description, 200) !!}')
            ->addColumn('action', function ($incomeType) {
                return  '<a href="'.route('admin.configuration.incomeTypes.edit',$incomeType->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.incomeTypes.destroy', $incomeType->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
