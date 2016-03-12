<?php

namespace App\Repositories\Backend\Promotion;


use App\Exceptions\GeneralException;
use App\Models\Promotion;
use Carbon\Carbon;

/**
 * Class EloquentPromotionRepository
 * @package App\Repositories\Backend\Promotion
 */
class EloquentPromotionRepository implements PromotionRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Promotion::find($id))) {
            return Promotion::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.promotions.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getPromotionsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Promotion::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllPromotions($order_by = 'sort', $sort = 'asc')
    {
        return Promotion::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (Promotion::where('name', $input['name'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.promotions.already_exists'));
        }


        $promotion = new Promotion();
        
        $promotion->name = $input['name'];
        $promotion->nb_desk = $input['nb_desk'];
        $promotion->nb_chair = $input['nb_chair'];
        $promotion->nb_chair_exam = $input['nb_chair_exam'];
        $promotion->description = $input['description'];
        $promotion->size = $input['size'];
        $promotion->active = isset($input['active'])?true:false;
        $promotion->promotion_type_id = $input['promotion_type_id'];
        $promotion->department_id = $input['department_id'];
        $promotion->building_id = $input['building_id'];
        $promotion->created_at = Carbon::now();
        $promotion->create_uid = auth()->id();

        if ($promotion->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.promotions.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $promotion = $this->findOrThrowException($id);

        $promotion->name = $input['name'];
        $promotion->nb_desk = $input['nb_desk'];
        $promotion->nb_chair = $input['nb_chair'];
        $promotion->nb_chair_exam = $input['nb_chair_exam'];
        $promotion->description = $input['description'];
        $promotion->size = $input['size'];
        $promotion->active = isset($input['active'])?true:false;
        $promotion->promotion_type_id = $input['promotion_type_id'];
        $promotion->department_id = $input['department_id'];
        $promotion->building_id = $input['building_id'];
        $promotion->updated_at = Carbon::now();
        $promotion->write_uid = auth()->id();

        if ($promotion->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.promotions.update_error'));
    }

    /**
     * @param  $id
     * @throws GeneralException
     * @return bool
     */
    public function destroy($id)
    {

        $model = $this->findOrThrowException($id);

        if ($model->delete()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
