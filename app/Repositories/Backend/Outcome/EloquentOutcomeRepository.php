<?php

namespace App\Repositories\Backend\Outcome;


use App\Exceptions\GeneralException;
use App\Http\Requests\Backend\Accounting\Outcome\CreateOutcomeRequest;
use App\Http\Requests\Backend\Accounting\Outcome\StoreOutcomeRequest;
use App\Models\Attachment;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Outcome;
use App\Models\PayslipClient;
use App\Models\StudentAnnual;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class EloquentOutcomeRepository
 * @package App\Repositories\Backend\Outcome
 */
class EloquentOutcomeRepository implements OutcomeRepositoryContract
{
    /**
     * @param  $id
     * @throws GeneralException
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|null|static
     */
    public function findOrThrowException($id)
    {
        if (! is_null(Outcome::find($id))) {
            return Outcome::find($id);
        }

        throw new GeneralException(trans('exceptions.backend.configuration.outcomes.not_found'));
    }

    /**
     * @param  $per_page
     * @param  string      $order_by
     * @param  string      $sort
     * @return mixed
     */
    public function getOutcomesPaginated($per_page, $order_by = 'sort', $sort = 'asc')
    {
        return Outcome::orderBy($order_by, $sort)
            ->paginate($per_page);
    }

    /**
     * @param  string  $order_by
     * @param  string  $sort
     * @return mixed
     */
    public function getAllOutcomes($order_by = 'sort', $sort = 'asc')
    {
        return Outcome::orderBy($order_by, $sort)
            ->get();
    }

    /**
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function create(StoreOutcomeRequest $request)
    {

        $input = $request->all();

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
        }

        $outcome = new Outcome();

        if(isset($input['amount_dollar'])){
            $outcome->amount_dollar = $input['amount_dollar']==''?null:$input['amount_dollar'];
        }
        if(isset($input['amount_riel'])){
            $outcome->amount_riel = $input['amount_riel']==''?null:$input['amount_riel'];
        }

        $outcome->amount_kh = $input['amount_kh'];

        $last_outcome = Outcome::orderBy('number','desc')->first();

        if($last_outcome != null) {
            $next_number = (int)substr($last_outcome->number, 5)+1;
        } else {
            $next_number = 1;
        }

        $outcome->number = $next_number;
        $outcome->pay_date = Carbon::now();
        $outcome->create_uid =auth()->id();
        $outcome->created_at = Carbon::now();
        $outcome->pay_date = Carbon::now();
        $outcome->payslip_client_id = $client_id;
        $outcome->account_id = $input['account_id'];
        $outcome->outcome_type_id = $input['outcome_type_id'];
        $outcome->attachment_name = $input['attachment_title']==""?null:$input['attachment_title'];

        if($outcome->save()){
            if($request->file('import')[0] != null){ // It can have multiple files
                $attachmentIds = array();
                foreach($request->file('import') as $file){
                    // Change file name
                    $imageName = "outcome_".$next_number . '.' .$file->getClientOriginalExtension();
                    // Move file
                    $request->file('photo')->move(
                        base_path() . '/public/img/attachments/', $imageName
                    );

                    // Save file location into table attachment
                    $attachment = new Attachment();
                    $attachment->location = base_path() . '/public/img/attachments/'. $imageName;
                    $attachment->create_uid =auth()->id();
                    $attachment->created_at = Carbon::now();

                    if($attachment->save()){
                        array_push($attachmentIds,$attachment->id);
                    } else {
                        $query_ok = false;
                    }
                }
                if($query_ok){
                    $outcome->attachments()->sync($attachmentIds);
                }

            }
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
     * @param  $id
     * @param  $input
     * @throws GeneralException
     * @return bool
     */
    public function update($id, $input)
    {
        $outcome = $this->findOrThrowException($id);

        $outcome->name = $input['name'];
        $outcome->description = $input['description'];
        $outcome->active = isset($input['active'])?true:false;
        $outcome->updated_at = Carbon::now();
        $outcome->write_uid = auth()->id();

        if ($outcome->save()) {
            return true;
        }

        throw new GeneralException(trans('exceptions.configuration.outcomes.update_error'));
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
