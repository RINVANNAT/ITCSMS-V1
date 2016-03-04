<?php

namespace App\Repositories\Backend\HighSchool;


use App\Exceptions\GeneralException;
use App\Models\HighSchool;
use Carbon\Carbon;

/**
 * Class EloquentHighSchoolRepository
 * @package App\Repositories\Backend\HighSchool
 */
class EloquentHighSchoolRepository implements HighSchoolRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(HighSchool::find($id))) {
            return HighSchool::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.highSchools.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getHighSchoolsPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return HighSchool::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllHighSchools($order_by = 'sort', $sort = 'asc')
    {
        return HighSchool::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        // For now we allow the same high school, that is from ministry.
        //if (HighSchool::where('name_en', $input['name_en'])->first()) {
        //    throw new GeneralException(trans('exceptions.backend.configuration.highSchools.already_exists'));
        //}

        $highSchool = new HighSchool();

        $highSchool->id = $input['id'];
        $highSchool->name_en = $input['name_en'];
        $highSchool->province_id = $input['province_id'];
        $highSchool->d_id = $input['d_id'];
        $highSchool->c_id = $input['c_id'];
        $highSchool->v_id = $input['v_id'];
        $highSchool->s_id = $input['s_id'];
        $highSchool->s_id = $input['ps_id'];
        $highSchool->name_kh = $input['name_kh'];
        $highSchool->prefix_id = $input['prefix_id'];
        $highSchool->valid = isset($input['valid'])?1:0;
        $highSchool->is_no_school = isset($input['is_no_school'])?1:0;
        $highSchool->locp_code = $input['locp_code'];
        $highSchool->locc_code = $input['locc_code'];
        $highSchool->locd_code = $input['locd_code'];
        $highSchool->locv_code = $input['locv_code'];

        if ($highSchool->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.highSchools.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $highSchool = $this->findOrThrowException($id);

        $highSchool->name_en = $input['name_en'];
        $highSchool->province_id = $input['province_id'];
        $highSchool->d_id = $input['d_id'];
        $highSchool->c_id = $input['c_id'];
        $highSchool->v_id = $input['v_id'];
        $highSchool->s_id = $input['s_id'];
        $highSchool->s_id = $input['ps_id'];
        $highSchool->name_kh = $input['name_kh'];
        $highSchool->prefix_id = $input['prefix_id'];
        $highSchool->valid = isset($input['valid'])?1:0;
        $highSchool->is_no_school = isset($input['is_no_school'])?1:0;
        $highSchool->locp_code = $input['locp_code'];
        $highSchool->locc_code = $input['locc_code'];
        $highSchool->locd_code = $input['locd_code'];
        $highSchool->locv_code = $input['locv_code'];

        if ($highSchool->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.highSchools.update_error'));
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
