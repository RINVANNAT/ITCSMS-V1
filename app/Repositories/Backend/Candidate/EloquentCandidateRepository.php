<?php

namespace App\Repositories\Backend\Candidate;


use App\Exceptions\GeneralException;
use App\Models\Candidate;
use Carbon\Carbon;

/**
 * Class EloquentCandidateRepository
 * @package App\Repositories\Backend\Candidate
 */
class EloquentCandidateRepository implements CandidateRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Candidate::find($id))) {
            return Candidate::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.candidates.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getCandidatesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Candidate::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllCandidates($order_by = 'sort', $sort = 'asc')
    {
        return Candidate::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        if (Candidate::where('name_en', $input['name_en'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.candidates.already_exists'));
        }



        $candidate = new Candidate();

        $candidate->name_latin = $input['name_latin'];
        $candidate->name_kh = $input['name_kh'];
        $candidate->register_id = $input['register_id'];
        $candidate->dob = $input['dob'];
        $candidate->mcs_no = $input['mcs_no'];
        $candidate->can_id = $input['can_id'];
        $candidate->phone = $input['phone'];
        $candidate->email = $input['email'];
        $candidate->address = $input['address'];
        $candidate->address_current = $input['address_current'];
        $candidate->is_paid = $input['is_paid'];
        $candidate->result = $input['result'];
        $candidate->register_from = $input['register_from'];
        $candidate->math_c = $input['math_c'];
        $candidate->math_w = $input['math_w'];
        $candidate->math_na = $input['math_na'];
        $candidate->phys_chem_c = $input['phys_chem_c'];
        $candidate->phys_chem_w = $input['phys_chem_w'];
        $candidate->phys_chem_na = $input['phys_chem_na'];
        $candidate->logic_c = $input['logic_c'];
        $candidate->logic_w = $input['logic_w'];
        $candidate->logic_na = $input['logic_na'];
        $candidate->total_s = $input['total_s'];
        $candidate->average = $input['average'];
        $candidate->bac_percentile = $input['bac_percentile'];
        $candidate->active = $input['active'];
        $candidate->highschool_id = $input['highschool_id'];
        $candidate->promotion_d = $input['promotion_d'];
        $candidate->bac_total_grade = $input['bac_total_grade'];
        $candidate->bac_math_grade = $input['bac_math_grade'];
        $candidate->bac_phys_grade = $input['bac_phys_grade'];
        $candidate->bac_chem_grade = $input['bac_chem_grade'];
        $candidate->bac_year = $input['bac_year'];
        $candidate->province_id = $input['province_id'];
        $candidate->pob = $input['pob'];
        $candidate->gender_id = $input['gender_id'];
        $candidate->academic_year_id = $input['academic_year_id'];
        $candidate->degree_id = $input['degree_id'];
        $candidate->exam_id = $input['exam_id'];
        $candidate->payslip_client_id = $input['payslip_client_id'];

        $candidate->created_at = Carbon::now();
        $candidate->create_uid = auth()->id();

        if ($candidate->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.candidates.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $candidate = $this->findOrThrowException($id);

        $candidate->name_en = $input['name_en'];
        $candidate->name_fr = $input['name_fr'];
        $candidate->name_kh = $input['name_kh'];
        $candidate->code = $input['code'];
        $candidate->school_id = $input['school_id'];
        $candidate->description = $input['description'];
        $candidate->updated_at = Carbon::now();
        $candidate->write_uid = auth()->id();

        if ($candidate->save()) {
            if(isset($input['departments'])){
                $departmentIds = $input['departments'];
                $candidate->departments()->sync($departmentIds);
            }
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.candidates.update_error'));
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
