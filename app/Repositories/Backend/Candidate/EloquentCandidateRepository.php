<?php

namespace App\Repositories\Backend\Candidate;


use App\Exceptions\GeneralException;
use App\Models\Candidate;
use App\Models\UserLog;
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

        $result = array();

        if (Candidate::where('register_id', $input['register_id'])->where('exam_id',$input['exam_id'])->first()) {
            //throw new GeneralException(trans('exceptions.backend.access.roles.already_exists'));
            $result["status"] = false;
            $result["register_id"] = array("This register ID is already exist");

            return $result;
        }

//        $candidate = new Candidate();
//
//        $candidate->name_latin = $input['name_latin'];
//        $candidate->name_kh = $input['name_kh'];
//        $candidate->register_id = $input['register_id'];
//        $candidate->dob = $input['dob'];
//
//        //dd(isset($input['mcs_no']) && $input['mcs_no']);
//
//        $candidate->mcs_no = isset($input['mcs_no']) && $input['mcs_no'] != "" ?$input['mcs_no']:null;
//        $candidate->can_id = isset($input['can_id']) && $input['can_id'] != "" ?$input['can_id']:null;
//        $candidate->phone = isset($input['phone']) && $input['phone'] != "" ?$input['phone']:null;
//        $candidate->email = isset($input['email']) && $input['email'] != "" ?$input['email']:null;
//        $candidate->address = isset($input['address'])?$input['address']:null;
//        $candidate->address_current = isset($input['address_current'])?$input['address_current']:null;
//        $candidate->is_paid = isset($input['is_paid'])?true:false;
//        $candidate->register_from = isset($input['register_from'])?$input['register_from']:"ITC";
//        $candidate->studentBac2_id = isset($input['studentBac2_id']) && $input['studentBac2_id'] != ""?$input['studentBac2_id']:null;
//        if(isset($input['math_c'])){
//            $candidate->math_c = $input['math_c']!=""?$input['math_c']:null;
//        }
//        if(isset($input['math_w'])) {
//            $candidate->math_w = $input['math_w']!="" ? $input['math_w'] : null;
//        }
//        if(isset($input['math_na'])) {
//            $candidate->math_na = $input['math_na']!= "" ? $input['math_na'] : null;
//        }
//        if(isset($input['phys_chem_c'])){
//            $candidate->phys_chem_c = $input['phys_chem_c']!=""?$input['phys_chem_c']:null;
//        }
//        if(isset($input['phys_chem_w'])) {
//            $candidate->phys_chem_w = $input['phys_chem_w']!="" ? $input['phys_chem_w'] : null;
//        }
//        if(isset($input['phys_chem_na'])) {
//            $candidate->phys_chem_na = $input['phys_chem_na']!="" ? $input['phys_chem_na'] : null;
//        }
//        if(isset($input['logic_c'])) {
//            $candidate->logic_c = $input['logic_c']!="" ? $input['logic_c'] : null;
//        }
//        if(isset($input['logic_w'])) {
//            $candidate->logic_w = $input['logic_w']!="" ? $input['logic_w'] : null;
//        }
//        if(isset($input['logic_na'])) {
//            $candidate->logic_na = $input['logic_na']!="" ? $input['logic_na'] : null;
//        }
//        if(isset($input['total_s'])) {
//            $candidate->total_s = $input['total_s']!= "" ? $input['total_s'] : null;
//        }
//        if(isset($input['average'])) {
//            $candidate->average = $input['average']!= "" ? $input['average'] : null;
//        }
//        $candidate->bac_percentile = isset($input['bac_percentile'])?$input['bac_percentile']:null;
//        $candidate->active = isset($input['active'])?true:false;
//        $candidate->highschool_id = isset($input['highschool_id'])?$input['highschool_id']:null;
//        $candidate->promotion_id = isset($input['promotion_id'])?$input['promotion_id']:null;
//        $candidate->bac_total_grade = isset($input['bac_total_grade'])&&$input['bac_total_grade']!=""?$input['bac_total_grade']:null;
//        $candidate->bac_math_grade = isset($input['bac_math_grade'])&&$input['bac_math_grade']!=""?$input['bac_math_grade']:null;
//        $candidate->bac_phys_grade = isset($input['bac_phys_grade'])&&$input['bac_phys_grade']!=""?$input['bac_phys_grade']:null;
//        $candidate->bac_chem_grade = isset($input['bac_chem_grade'])&&$input['bac_chem_grade']!=""?$input['bac_chem_grade']:null;
//        $candidate->bac_year = isset($input['bac_year'])?$input['bac_year']:null;
//        $candidate->province_id = isset($input['province_id'])?$input['province_id']:null;
//        $candidate->pob = isset($input['pob'])?$input['pob']:null;
//        $candidate->gender_id = isset($input['gender_id'])?$input['gender_id']:null;
//        $candidate->academic_year_id = isset($input['academic_year_id'])?$input['academic_year_id']:null;
//        $candidate->degree_id = isset($input['degree_id'])?$input['degree_id']:null;
//        $candidate->exam_id = isset($input['exam_id'])?$input['exam_id']:null;
//        $candidate->payslip_client_id = isset($input['payslip_client_id'])?$input['payslip_client_id']:null;
//
//        $candidate->created_at = Carbon::now();
//        $candidate->create_uid = auth()->id();

        foreach($input as &$element){
            if($element=="")$element=null;
        }
        $input['created_at'] = Carbon::now();
        $input['create_uid'] = auth()->id();
        $candidate = Candidate::create($input);
        if ($candidate != null) {
            if(isset($input['departments'])){
                $departmentIds = $input['departments'];
                $candidate->departments()->sync($departmentIds);
            }
            $result["status"] = true;
            $result["messages"] = "Your information is successfully saved";

            UserLog::log([
                'model' => 'Candidate',
                'action'=> 'Create',
                'data'  => $candidate->id, // Store only id because we didn't really delete the record
            ]);
        } else {
            $result["status"] = false;
            $result["messages"] = "Something went wrong!";
        }

        return $result;
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
        $old_record = json_encode($candidate);
        foreach($input as &$element){
            if($element=="")$element=null;
        }

        $input['updated_at'] = Carbon::now();
        $input['write_uid'] = auth()->id();

        $candidate->fill($input);

        if ($candidate->save()) {
            if(isset($input['departments'])){
                $departmentIds = $input['departments'];
                $candidate->departments()->sync($departmentIds);
            }
            $result["status"] = true;
            $result["messages"] = "Your information is successfully saved";

            UserLog::log([
                'model' => 'Candidate',
                'action'=> 'Update',
                'data'  => $old_record, // Store only id because we didn't really delete the record
            ]);
        } else {
            $result["status"] = false;
            $result["messages"] = "Something went wrong!";
        }

        return $result;
    }

    /**
     * @param  $id
     * @throws GeneralException
     * @return bool
     */
    public function destroy($id)
    {

        $model = $this->findOrThrowException($id);
        $model->active = false; // Instead of real delete, just change active to false.
        $model->updated_at = Carbon::now();
        $model->write_uid = auth()->id();


        if ($model->save()) {
            UserLog::log([
                'model' => 'Candidate',
                'action'=> 'Delete',
                'data'  => $id, // Store only id because we didn't really delete the record
            ]);
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
