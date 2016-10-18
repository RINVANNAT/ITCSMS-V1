<?php

namespace App\Repositories\Backend\Candidate;


use App\Exceptions\GeneralException;
use App\Models\Candidate;
use App\Models\UserLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        //dd($input['choice_department']);
        $result = array();

        if (Candidate::where('register_id', $input['register_id'])->where('exam_id',$input['exam_id'])->first()) {
            //throw new GeneralException(trans('exceptions.backend.access.roles.already_exists'));
            $result["status"] = false;
            $result["register_id"] = array("This register ID is already exist");

            return $result;
        }

        foreach($input as &$element){
            if($element=="")$element=null;
        }
        $input['created_at'] = Carbon::now();
        $input['create_uid'] = auth()->id();
        $candidate = Candidate::create($input);


        if ($candidate != null) {
            if(isset($input['choice_department'])){
                //$departmentIds = $input['departments'];
                //$candidate->departments()->sync($departmentIds);
                foreach($input['choice_department'] as $department_id => $choice_department){
                    DB::table('candidate_department')->insert([
                        ['candidate_id' => $candidate->id, 'department_id' => $department_id, 'rank' => $choice_department]
                    ]);
                }
            }
            $result["candidate_id"] = $candidate->id;
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
            if(isset($input['choice_department'])){
                foreach($input['choice_department'] as $department_id => $choice_department){
                    DB::table('candidate_department')
                        ->where('candidate_id',$candidate->id)
                        ->where('department_id',$department_id)
                        ->update(['rank' => $choice_department]);
                }

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
        $old_record = json_encode($model);

        if ($model->delete()) {
            UserLog::log([
                'model' => 'Candidate',
                'action'=> 'Delete-Full',
                'data'  => $old_record, // Candidate need to completely remove, else problem will occur when registering new candidate (unique register_id +exam_id)
            ]);
            return true;
        }

        throw new GeneralException(trans('exceptions.backend.general.delete_error'));
    }
}
