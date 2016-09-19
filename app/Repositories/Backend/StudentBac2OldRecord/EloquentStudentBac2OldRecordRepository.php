<?php

namespace App\Repositories\Backend\StudentBac2OldRecord;


use App\Exceptions\GeneralException;
use App\Models\StudentBac2;
use App\Models\StudentBac2OldRecord;
use Carbon\Carbon;

/**
 * Class EloquentStudentBac2OldRecordRepository
 * @package App\Repositories\Backend\StudentBac2OldRecord
 */
class EloquentStudentBac2OldRecordRepository implements StudentBac2OldRecordRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(StudentBac2OldRecord::find($id))) {
            return StudentBac2OldRecord::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.studentBac2s.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getStudentBac2sPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return StudentBac2::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllStudentBac2s($order_by = 'sort', $sort = 'asc')
    {
        return StudentBac2::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {


        $studentBac2 = new StudentBac2OldRecord();

        if(isset($input['can_id'])){
            $studentBac2->can_id = $input['can_id'];
        }

        $studentBac2->mcs_no = $input['mcs_no'];
        $studentBac2->province_id = $input['province_id'];
        $studentBac2->name_kh = $input['name_kh'];
        $studentBac2->dob = $input['dob'];
        $studentBac2->gender_id = $input['gender_id'];
        $studentBac2->father_name = $input['father_name'];
        $studentBac2->mother_name = $input['mother_name'];
        $studentBac2->pob = $input['pob'];
        $studentBac2->highschool_id = $input['highschool_id'];
        $studentBac2->room = $input['room'];
        $studentBac2->seat = $input['seat'];
        $studentBac2->bac_math_grade = $input['bac_math_grade'];
        $studentBac2->bac_chem_grade = $input['bac_chem_grade'];
        $studentBac2->bac_phys_grade = $input['bac_phys_grade'];
        $studentBac2->percentile = $input['percentile'];
        $studentBac2->grade = $input['grade'];
        $studentBac2->program = $input['program'];
        $studentBac2->bac_year = $input['bac_year'];
        $studentBac2->status = $input['status'];

        if (isset($input['desc'])){
            $studentBac2->desc = $input['desc'];
        }

        $studentBac2->is_registered = isset($input['is_registered'])?true:false;
        $studentBac2->active = isset($input['active'])?true:false;

        if ($studentBac2->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.configuration.studentBac2s.create_error'));
    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $studentBac2 = $this->findOrThrowException($id);

        $studentBac2->can_id = $input['can_id'];
        $studentBac2->mcs_no = $input['mcs_no'];
        $studentBac2->province_id = $input['province_id'];
        $studentBac2->name_kh = $input['name_kh'];
        $studentBac2->dob = $input['dob'];
        $studentBac2->gender_id = $input['gender_id'];
        $studentBac2->father_name = $input['father_name'];
        $studentBac2->mother_name = $input['mother_name'];
        $studentBac2->pob = $input['pob'];
        $studentBac2->highschool_id = $input['highschool_id'];
        $studentBac2->room = $input['room'];
        $studentBac2->seat = $input['seat'];
        $studentBac2->bac_math_grade = $input['bac_math_grade'];
        $studentBac2->bac_chem_grade = $input['bac_chem_grade'];
        $studentBac2->bac_phys_grade = $input['bac_phys_grade'];
        $studentBac2->percentile = $input['percentile'];
        $studentBac2->grade = $input['grade'];
        $studentBac2->program = $input['program'];
        $studentBac2->desc = $input['desc'];
        $studentBac2->bac_year = $input['bac_year'];
        $studentBac2->status = $input['status'];

        if (isset($input['desc'])){
            $studentBac2->desc = $input['desc'];
        }

        $studentBac2->is_registered = isset($input['is_registered'])?true:false;
        $studentBac2->active = isset($input['active'])?true:false;

        if ($studentBac2->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.studentBac2s.update_error'));
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
