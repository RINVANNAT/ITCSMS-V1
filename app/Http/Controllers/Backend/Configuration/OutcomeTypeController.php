<?php namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\OutcomeType\DeleteOutcomeTypeRequest;
use App\Http\Requests\Backend\Configuration\OutcomeType\EditOutcomeTypeRequest;
use App\Http\Requests\Backend\Configuration\OutcomeType\StoreOutcomeTypeRequest;
use App\Http\Requests\Backend\Configuration\OutcomeType\UpdateOutcomeTypeRequest;
use App\Repositories\Backend\OutcomeType\OutcomeTypeRepositoryContract;
use Illuminate\Support\Facades\DB;

class OutcomeTypeController extends Controller
{

    /**
     * @var OutcomeTypeRepositoryContract
     */
    protected $outcomeTypes;

    /**
     * @param OutcomeTypeRepositoryContract $outcomeTypeRepo
     */
    public function __construct(
        OutcomeTypeRepositoryContract $outcomeTypeRepo
    )
    {
        $this->outcomeTypes = $outcomeTypeRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.outcomeType.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.configuration.outcomeType.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOutcomeTypeRequest $request)
    {
        $this->outcomeTypes->create($request->all());
        return redirect()->route('admin.configuration.outcomeTypes.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
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
    public function edit(EditOutcomeTypeRequest $request, $id)
    {

        $outcomeType = $this->outcomeTypes->findOrThrowException($id);

        return view('backend.configuration.outcomeType.edit',compact('outcomeType'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOutcomeTypeRequest $request, $id)
    {
        $this->outcomeTypes->update($id, $request->all());
        return redirect()->route('admin.configuration.outcomeTypes.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteOutcomeTypeRequest $request, $id)
    {
        $this->outcomeTypes->destroy($id);
        if($request->ajax()){
            return json_encode(["success"=>true]);
        } else {
            return redirect()->route('admin.configuration.outcomeTypes.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data()
    {
        $outcomeTypes = DB::table('outcomeTypes')
            ->select(['id','code','name','origin','description','active']);

        $datatables =  app('datatables')->of($outcomeTypes);


        return $datatables
            ->editColumn('code', '{!! str_limit($code, 60) !!}')
            ->editColumn('name', '{!! str_limit($name, 60) !!}')
            ->editColumn('origin', '{!! str_limit($origin, 60) !!}')
            ->editColumn('description', '{!! str_limit($description, 60) !!}')
            ->editColumn('active', '{!! str_limit($active, 60) !!}')
            ->addColumn('action', function ($outcomeType) {
                return  '<a href="'.route('admin.configuration.outcomeTypes.edit',$outcomeType->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.outcomeTypes.destroy', $outcomeType->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
