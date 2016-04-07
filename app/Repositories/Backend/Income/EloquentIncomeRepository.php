<?php

namespace App\Repositories\Backend\Income;


use App\Exceptions\GeneralException;
use App\Models\Candidate;
use App\Models\Employee;
use App\Models\Income;
use App\Models\IncomeType;
use App\Models\Outcome;
use App\Models\PayslipClient;
use App\Models\SchoolFeeRate;
use App\Models\StudentAnnual;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use SwaggerFixures\Customer;

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

    public function createSimpleIncome($input){

        $query_ok = true;
        $client_id = null;
        DB::beginTransaction();

        if($input['payslip_client_id'] == ""){ // This is new client or employee/student who haven't any records
            // Create a payslipClient record first
            $payslip_client = new PayslipClient();
            $payslip_client->type = $input['client_type'];
            $payslip_client->create_uid =auth()->id();
            $payslip_client->created_at = Carbon::now();

            if($payslip_client->save()){

                if ($input['client_type'] == "Staff"){// Update employee with this new payslip_client
                    $employee = Employee::find($input['client_id']);
                    $employee->write_uid = auth()->id();
                    $employee->payslip_client_id = $payslip_client->id;
                    if(!$employee->save()){
                        $query_ok = false;
                    }
                } else if ($input['client_type']=="Student") { // Update student with this new payslip_client
                    $student = StudentAnnual::find($input['client_id']);
                    $student->write_uid = auth()->id();
                    $student->payslip_client_id = $payslip_client->id;
                    if(!$student->save()){
                        $query_ok = false;
                    }
                } else { // This is other
                    $customer = Customer::find($input['client_id']);
                    $customer->write_uid = auth()->id();
                    $customer->payslip_client_id = $payslip_client->id;
                    if(!$customer->save()){
                        $query_ok = false;
                    }
                }

                $client_id = $payslip_client->id;

            }else {
                $query_ok = false;
            }
        } else {
            $client_id = $input['payslip_client_id'];
        }

        $income = new Income();

        if(isset($input['amount_dollar'])){
            $income->amount_dollar = $input['amount_dollar']==''?null:$input['amount_dollar'];
        }
        if(isset($input['amount_riel'])){
            $income->amount_riel = $input['amount_riel']==''?null:$input['amount_riel'];
        }

        $income->amount_kh = $input['amount_kh'];

        $last_income = Income::orderBy('number','desc')->first();

        if($last_income != null) {
            $next_number = (int)substr($last_income->number, 5)+1;
        } else {
            $next_number = 1;
        }

        $income->number = $next_number;
        $income->pay_date = Carbon::now();
        $income->create_uid =auth()->id();
        $income->created_at = Carbon::now();
        $income->pay_date = Carbon::now();
        $income->payslip_client_id = $client_id;
        $income->account_id = $input['account_id'];
        $income->income_type_id = $input['income_type_id'];

        if($income->save()){
        } else {
            $query_ok = false;
        }

        if($query_ok){
            DB::commit();
            return true;
        } else {
            DB::rollback();
            throw new GeneralException(trans('exceptions.backend.general.create_error'));
        }
    }
    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create($input) // This is for student
    {
        $client_id = null;

        $income = new Income();

        $income->created_at = Carbon::now();
        $income->create_uid = auth()->id();
        $income->number = Income::count() + 1;
        if(isset($input['amount_dollar'])){
            $income->amount_dollar = $input['amount_dollar']==""?null:$input['amount_dollar'];
        }
        if(isset($input['amount_riel'])){
            $income->amount_riel = $input['amount_riel']==""?null:$input['amount_riel'];
        }


        $query_ok = true;
        DB::beginTransaction();
        if($input['payslip_client_id']== ""){ //This is new client, generate a payslip client id for him/her
            $payslip_client = new PayslipClient();
            $payslip_client->type = Input::get('type');
            $payslip_client->create_uid =auth()->id();
            if($payslip_client->save()){ // New client id is created, so link to user
                $client_id = $payslip_client->id;
                if($input['candidate_id']!=""){
                    $candidate = Candidate::find($input['candidate_id']);
                    $candidate->write_uid = auth()->id();
                    $candidate->payslip_client_id = $client_id;
                    if(!$candidate->save()){
                        $query_ok = false;
                    }
                }
                if($input['student_annual_id']!=""){ // For student
                    $student = StudentAnnual::find($input['student_annual_id']);
                    $student->write_uid = auth()->id();
                    $student->payslip_client_id = $client_id;
                    if(!$student->save()){
                        $query_ok = false;
                    }
                } else if($input['candidate_id']!= ""){ // For candidate
                    $candidate = Candidate::find($input['candidate_id']);
                    $candidate->write_uid = auth()->id();
                    $candidate->payslip_client_id = $client_id;
                    $candidate->is_paid = true;
                    if(!$candidate->save()){
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
            $income->sequence = Income::where('payslip_client_id',$client_id)->count()+1; // Sequence of student payment
            $income->payslip_client_id = $client_id;
            $income->pay_date = Carbon::now();
            $income->description = $input['description'];
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

        if($query_ok && Input::get('type') == "student"){  // This operation is no need for candidate
            // now check if student has made the full payment

            $total_topay = null;
            $currency = null;

            $studentAnnual = StudentAnnual::where('payslip_client_id',$income->payslip_client_id)->first();
            //dd($studentAnnual);
            $scholarship_ids = DB::table('scholarship_student_annual')->where('student_annual_id',$studentAnnual->id)->lists('scholarship_id');
            $school_fee = SchoolFeeRate::leftJoin('department_school_fee_rate','schoolFeeRates.id','=','department_school_fee_rate.school_fee_rate_id')
                ->leftJoin('grade_school_fee_rate','schoolFeeRates.id','=','grade_school_fee_rate.school_fee_rate_id')
                ->where('promotion_id' ,$studentAnnual->promotion_id)
                ->where('degree_id' ,$studentAnnual->degree_id)
                ->where('grade_school_fee_rate.grade_id' ,$studentAnnual->grade_id)
                ->where('department_school_fee_rate.department_id' ,$studentAnnual->department_id);
            if(sizeof($scholarship_ids)>0){ //This student have scholarship, so his payment might be changed
                $scolarship_fee = clone $school_fee;
                $scolarship_fee = $scolarship_fee
                    ->whereIn('scholarship_id' ,$scholarship_ids)
                    ->select(['to_pay','to_pay_currency'])
                    ->get();
                if($scolarship_fee->count() > 0){
                    $currency = $scolarship_fee->first()->to_pay_currency;
                    $total_topay = floatval($scolarship_fee->first()->to_pay);
                } else { // Scholarships student have, doesn't change school payment fee, so we need to check it again
                    $school_fee = $school_fee
                        ->select(['to_pay','to_pay_currency'])
                        ->get();
                    if($school_fee->count() == 0){
                        $total_topay = null;
                        $topay = "Not found";
                    }
                    $total_topay = floatval($school_fee->first()->to_pay);
                    $currency = $school_fee->first()->to_pay_currency;
                }
            } else {
                // This student doesn't have scholarship
                $school_fee = $school_fee
                    ->select(['to_pay','to_pay_currency'])
                    ->get();
                if($school_fee->count() == 0){
                    $total_topay = null;
                    $topay = "Not found"; // This mean, scholarship fee isn't update to the latest version, ask finance staff to update it !!
                }
                $total_topay = floatval($school_fee->first()->to_pay);
                $currency = $school_fee->first()->to_pay_currency;
            }

            $paids = Income::select(['amount_dollar','amount_riel'])
                ->where('payslip_client_id',$studentAnnual->payslip_client_id)->get();

            $total_paid = 0;
            foreach($paids as $paid){
                if($paid->amount_dollar != ''){
                    $total_paid += floatval($paid->amount_dollar);
                } else {
                    $total_paid += floatval($paid->amount_riel);
                }
            }

            if($total_paid >= $total_topay){
                // This student have made full payment, so let update paid field in table student for later quick query
                $studentAnnual->is_paid = true;
                if($studentAnnual->save()){
                    $query_ok = true;
                } else {
                    $query_ok = false;
                }
            }


        }

        if($query_ok){
            DB::commit();
            return route('admin.accounting.payslipHistory.data',$client_id);
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
