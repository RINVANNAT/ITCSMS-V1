<?php

namespace App\Repositories\Backend\Income;


use App\Exceptions\GeneralException;
use App\Models\Candidate;
use App\Models\Income;
use App\Models\Outcome;
use App\Models\PayslipClient;
use App\Models\StudentAnnual;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentIncomeRepository
 * @package App\Repositories\Backend\Income
 */
class EloquentIncomeRepository implements IncomeRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Income::find($id))) {
            return Income::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.incomes.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getIncomesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Income::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllIncomes($order_by = 'sort', $sort = 'asc')
    {
        return Income::orderBy($order_by, $sort)
            ->get();
    }

    private function createStudentPayment(){

    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input)
    {
        $client_id = null;

        $income = new Income();

        $income->created_at = Carbon::now();
        $income->create_uid = auth()->id();
        $income->number = Income::count() + Outcome::count() + 1;
        $income->amount_dollar = $input['amount_dollar']==""?null:$input['amount_dollar'];
        $income->amount_riel = $input['amount_riel']==""?null:$input['amount_riel'];

        $query_ok = true;
        DB::beginTransaction();
        if($input['payslip_client_id']== ""){ //This is new client, generate a payslip client id for him/her
            $payslip_client = new PayslipClient();
            $payslip_client->type = "Student";
            $payslip_client->create_uid =auth()->id();
            if($payslip_client->save()){ // New client id is created, so link to user
                $client_id = $payslip_client->id();
                if($input['candidate_id']!=""){
                    $candidate = Candidate::find($input['candidate_id']);
                    $candidate->write_uid = auth()->id();
                    $candidate->payslip_client_id = $client_id;
                    if(!$candidate->save()){
                        $query_ok = false;
                    }
                }
                if($input['student_annual_id']!=""){
                    $student = StudentAnnual::find($input['student_annual_id']);
                    $student->write_uid = auth()->id();
                    $student->payslip_client_id = $client_id;
                    if(!$student->save()){
                        $query_ok = false;
                    }
                }
            } else {
                $query_ok = false;
            }
        } else { //Student have made some payment before
            $client_id = $input['payslip_client_id'];
        }

        if($query_ok){
            $income->payslip_client_id = $client_id;
            $income->pay_date = Carbon::now();
            if($input['degree_id']== config('access.degrees.degree_engineer') || $input['degree_id']== config('access.degrees.degree_associate')){
                $income->income_type_id = config('access.income_type.income_type_student_day');
                $income->account_id = config('access.account.account_day_student');
            } else if($input['degree_id']== config('access.degrees.degree_bachelor')){
                $income->income_type_id = config('access.income_type.income_type_student_night');
                $income->account_id=config('access.account.account_night_student');
            } else if($input['degree_id']== config('access.degrees.degree_master')){
                $income->income_type_id = config('access.income_type.income_type_student_master');
                $income->account_id=config('access.account.account_master_student');
            }


            if($income->save()){
                $query_ok = true;
            } else {
                $query_ok = false;
            }
        }

        if($query_ok){
            DB::commit();
            return true;
        } else {
            DB::rollback();
            throw new GeneralException(trans('exceptions.backend.configuration.incomes.create_error'));
        }

    }

    /**
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $income = $this->findOrThrowException($id);

        $income->name = $input['name'];
        $income->description = $input['description'];
        $income->active = isset($input['active'])?true:false;
        $income->updated_at = Carbon::now();
        $income->write_uid = auth()->id();

        if ($income->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.incomes.update_error'));
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
