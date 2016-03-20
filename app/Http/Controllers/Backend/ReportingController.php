<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Reporting\CreateReportingRequest;
use App\Http\Requests\Backend\Reporting\DeleteReportingRequest;
use App\Http\Requests\Backend\Reporting\EditReportingRequest;
use App\Http\Requests\Backend\Reporting\StoreReportingRequest;
use App\Http\Requests\Backend\Reporting\UpdateReportingRequest;
use App\Repositories\Backend\Reporting\ReportingRepositoryContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;

class ReportingController extends Controller
{
    /**
     * @var ReportingRepositoryContract
     */
    protected $reporting;

    /**
     * @param ReportingRepositoryContract $reportingRepo
     */
    public function __construct(
        ReportingRepositoryContract $reportingRepo
    )
    {
        $this->reporting = $reportingRepo;
    }

    /**
     * Display a listing of the reporting base on its type (engineer or DUT or final semester).
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('backend.reporting.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @param CreateReportingRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateReportingRequest $request)
    {
        return view('backend.reporting.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreReportingRequest  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReportingRequest $request)
    {
        $this->reporting->create($request);
        return redirect()->route('admin.reporting.index')->withFlashSuccess(trans('alerts.backend.generals.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reporting = $this->reporting->findOrThrowException($id);

        return view('backend.reporting.show',compact('reporting'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditReportingRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditReportingRequest $request, $id)
    {

        $reporting = $this->reporting->findOrThrowException($id);

        return view('backend.reporting.edit',compact('reporting'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateReportingRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id,UpdateReportingRequest $request)
    {
        $this->reporting->update($id, $request);
        return redirect()->route('admin.reporting.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    public function change_status($id)
    {
        if(access()->hasRole('Administrator')) {
            $reporting = $this->reporting->findOrThrowException($id);

            $reporting->status = $_POST['status'];
            $reporting->write_uid = auth()->id();
            $reporting->updated_at = Carbon::now();
            if($reporting->save()){
                return json_encode(array('success'=>true));
            }
        } else {
            throw new GeneralException(trans('exceptions.backend.general.no_permission'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteReportingRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteReportingRequest $request, $id)
    {
        $this->reporting->destroy($id);
        if($request->ajax()){
            return json_encode(array("success"=>true));
        } else {
            return redirect()->route('admin.reporting.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data()
    {
        $reporting = DB::table('reporting')
            ->select(['id','title','description','created_at','status']);

        if(!access()->hasRole('Administrator')){ //admin, see all message
            $reporting = $reporting->where('create_uid',auth()->id());
        }

        $datatables =  app('datatables')->of($reporting);


        return $datatables
            ->editColumn('status',function($reporting){
                if($reporting->status == "Pending"){
                    return '<span class="label label-warning">'.$reporting->status.'</span>';
                } else if($reporting->status == "Done"){
                    return '<span class="label label-success">'.$reporting->status.'</span>';
                } else if($reporting->status == "Rejected"){
                    return '<span class="label label-danger">'.$reporting->status.'</span>';
                } else { // Progress
                    return '<span class="label label-info">'.$reporting->status.'</span>';
                }
            })
            ->editColumn('created_at',function($reporting){
                $date = Carbon::createFromFormat('Y-m-d h:i:s',$reporting->created_at);
                return $date->diffForHumans();
            })
            ->addColumn('action', function ($reporting) {
                $action = "";
                if (access()->allow('edit-reporting') || $reporting->status != "Pending") { // If the reporting is already in progress, you can not edit anymore.
                    $action .=' <a href="'.route('admin.reporting.edit',$reporting->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>';
                }
                if (access()->allow('delete-reporting')) {
                    $action .= ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.reporting.destroy', $reporting->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
                }

                $action .= ' <a href="'.route('admin.reporting.show',$reporting->id).'" class="btn btn-xs btn-info"><i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.view').'"></i> </a>';
                return $action;
            })
            ->make(true);
    }

}
