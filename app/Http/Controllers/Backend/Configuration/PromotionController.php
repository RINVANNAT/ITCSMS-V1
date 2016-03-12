<?php

namespace App\Http\Controllers\Backend\Configuration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Configuration\Promotion\CreatePromotionRequest;
use App\Http\Requests\Backend\Configuration\Promotion\DeletePromotionRequest;
use App\Http\Requests\Backend\Configuration\Promotion\EditPromotionRequest;
use App\Http\Requests\Backend\Configuration\Promotion\StorePromotionRequest;
use App\Http\Requests\Backend\Configuration\Promotion\UpdatePromotionRequest;
use App\Models\Building;
use App\Models\Department;
use App\Models\Promotion;
use App\Models\PromotionType;
use App\Models\School;
use App\Repositories\Backend\Promotion\PromotionRepositoryContract;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    /**
     * @var PromotionRepositoryContract
     */
    protected $promotions;

    /**
     * @param PromotionRepositoryContract $promotionRepo
     */
    public function __construct(
        PromotionRepositoryContract $promotionRepo
    )
    {
        $this->promotions = $promotionRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('backend.configuration.promotion.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreatePromotionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreatePromotionRequest $request)
    {
        return view('backend.configuration.promotion.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StorePromotionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePromotionRequest $request)
    {
        $this->promotions->create($request->all());
        return redirect()->route('admin.configuration.promotions.index')->withFlashSuccess(trans('alerts.backend.roles.created'));
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
     * @param EditPromotionRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EditPromotionRequest $request, $id)
    {

        $promotion = $this->promotions->findOrThrowException($id);
        return view('backend.configuration.promotion.edit',compact('promotion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdatePromotionRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePromotionRequest $request, $id)
    {
        $this->promotions->update($id, $request->all());
        return redirect()->route('admin.configuration.promotions.index')->withFlashSuccess(trans('alerts.backend.generals.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeletePromotionRequest $request, $id)
    {
        if($request->ajax()){
            $this->promotions->destroy($id);
        } else {
            return redirect()->route('admin.configuration.promotions.index')->withFlashSuccess(trans('alerts.backend.generals.deleted'));
        }
    }

    public function data()
    {

        $promotions = DB::table('promotions')
            ->select(['id','name','active','observation']);

        $datatables =  app('datatables')->of($promotions);


        return $datatables
            ->addColumn('action', function ($promotion) {
                return  '<a href="'.route('admin.configuration.promotions.edit',$promotion->id).'" class="btn btn-xs btn-primary"><i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="" data-original-title="'.trans('buttons.general.crud.edit').'"></i> </a>'.
                ' <button class="btn btn-xs btn-danger btn-delete" data-remote="'.route('admin.configuration.promotions.destroy', $promotion->id) .'"><i class="fa fa-times" data-toggle="tooltip" data-placement="top" title="' . trans('buttons.general.crud.delete') . '"></i></button>';
            })
            ->make(true);
    }

}
