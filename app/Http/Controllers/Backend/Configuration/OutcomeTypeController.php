<?php namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\OutcomeType\DeleteOutcomeTypeRequest;
use App\Http\Requests\Backend\Configuration\OutcomeType\EditOutcomeTypeRequest;
use App\Http\Requests\Backend\Configuration\OutcomeType\StoreOutcomeTypeRequest;
use App\Http\Requests\Backend\Configuration\OutcomeType\UpdateOutcomeTypeRequest;
use App\Http\Requests\Backend\Configuration\OutcomeType\ImportOutcomeTypeRequest;
use App\Http\Requests\Backend\Configuration\OutcomeType\RequestImportOutcomeTypeRequest;
use App\Repositories\Backend\OutcomeType\OutcomeTypeRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
            ->addColumn('action', function ($outcomeType) {
                return  '<a href="'.route('admin.configuration.outcomeTypes.edit',$outcomeType->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.outcomeTypes.destroy', $outcomeType->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

    public function request_import(RequestImportOutcomeTypeRequest $request){

        return view('backend.configuration.outcomeType.import');

    }

    public function import(ImportOutcomeTypeRequest $request){
        $now = Carbon::now()->format('Y_m_d_H');

        // try to move uploaded file to a temporary location
        if($request->file('import')!= null){
            $import = $now. '.' .$request->file('import')->getClientOriginalExtension();

            $request->file('import')->move(
                base_path() . '/public/assets/uploaded_file/temp/', $import
            );

            $storage_path = base_path() . '/public/assets/uploaded_file/temp/'.$import;

            // and then read that data and store to database
            //Excel::load($storage_path, function($reader) {
            //    dd($reader->first());
            //});


            DB::beginTransaction();

            try{
                Excel::filter('chunk')->load($storage_path)->chunk(1000, function($results){
                    //dd($results->first());
                    // Loop through all rows
                    $results->each(function($row) {

                        $studentBac2 = $this->outcomeTypes->create($row->toArray());
                    });
                });

            } catch(Exception $e){
                DB::rollback();
            }
            DB::commit();

            return redirect(route('admin.configuration.outcomeTypes.index'));
        }
    }

}
