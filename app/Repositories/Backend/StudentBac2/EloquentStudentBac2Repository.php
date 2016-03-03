<?php

namespace App\Repositories\Backend\StudentBac2;


use App\Exceptions\GeneralException;
use App\Models\StudentBac2;
use Carbon\Carbon;

/**
 * Class EloquentStudentBac2Repository
 * @package App\Repositories\Backend\StudentBac2
 */
class EloquentStudentBac2Repository implements StudentBac2RepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(StudentBac2::find($id))) {
            return StudentBac2::find($id);
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
        if (StudentBac2::where('name_en', $input['name_en'])->first()) {
            throw new GeneralException(trans('exceptions.backend.configuration.studentBac2s.already_exists'));
        }


        $studentBac2 = new StudentBac2();

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
        $studentBac2->percentile = $input['v'];
        $studentBac2->grade = $input['grade'];
        $studentBac2->program = $input['program'];
        $studentBac2->desc = $input['desc'];
        $studentBac2->bac_year = $input['bac_year'];
        $studentBac2->status = $input['status'];
        $studentBac2->is_registered = $input['is_registered'];
        $studentBac2->created_at = Carbon::now();
        $studentBac2->create_uid = auth()->id();

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
        $studentBac2->percentile = $input['v'];
        $studentBac2->grade = $input['grade'];
        $studentBac2->program = $input['program'];
        $studentBac2->desc = $input['desc'];
        $studentBac2->bac_year = $input['bac_year'];
        $studentBac2->status = $input['status'];
        $studentBac2->is_registered = $input['is_registered'];
        $studentBac2->updated_at = Carbon::now();
        $studentBac2->write_uid = auth()->id();

        if ($studentBac2->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.studentBac2s.update_error'));
    }


}
