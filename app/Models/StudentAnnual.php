<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Support\Facades\DB;

class StudentAnnual extends Model
{
    
	public $table = "studentAnnuals";
    

	public $fillable = [
        "to_pay",
        "to_pay_currency",
        "group",
        "active",
	    "promotion_id",
        "history_id",
        "department_id",
        "degree_id",
        "grade_id",
        "academic_year_id",
        "student_id",
        "create_uid",
        "write_uid",
        "department_option_id",
        "general_remark",
        "is_suspend"
	];
    //LATER do this latter

    public function getCountIncomeAttribute(){
        $count_income = 0;
        if($this->payslip_client_id!=null){
            $count_income = \App\Models\Income::where('payslip_client_id',$this->payslip_client_id)->count();
        }
        return $count_income;
    }

    public function getCountOutcomeAttribute(){
        $count_outcome = 0;
        if($this->payslip_client_id!=null){
            $count_joutcome = \App\Models\Outcome::where('payslip_client_id',$this->payslip_client_id)->count();
        }
        return $count_outcome;
    }

    public function getToPayArrayAttribute()
    {
        $total_topay = null;
        $currency = null;
        if($this->to_pay != null) {
            $total_topay = $this->to_pay;
            $currency = $this->to_pay_currency;
            //$topay = $total_topay." ".$currency;

        } else {
            $scholarship_ids = DB::table('scholarship_student_annual')->where('student_annual_id', $this->id)->lists('scholarship_id');
            $school_fee = SchoolFeeRate::leftJoin('department_school_fee_rate', 'schoolFeeRates.id', '=', 'department_school_fee_rate.school_fee_rate_id')
                ->leftJoin('grade_school_fee_rate', 'schoolFeeRates.id', '=', 'grade_school_fee_rate.school_fee_rate_id')
                ->where('promotion_id', $this->promotion_id)
                ->where('degree_id', $this->degree_id)
                ->where('grade_school_fee_rate.grade_id', $this->grade_id)
                ->where('department_school_fee_rate.department_id', $this->department_id);
            if (sizeof($scholarship_ids) > 0) { //This student have scholarship, so his payment might be changed
                $scolarship_fee = clone $school_fee;
                $scolarship_fee = $scolarship_fee
                    ->whereIn('scholarship_id', $scholarship_ids)
                    ->select(['to_pay', 'to_pay_currency'])
                    ->get();
                if ($scolarship_fee->count() > 0) {
                    $currency = $scolarship_fee->first()->to_pay_currency;
                    $total_topay = floatval($scolarship_fee->first()->to_pay);
                } else { // Scholarships student have, doesn't change school payment fee, so we need to check it again
                    $school_fee = $school_fee
                        ->select(['to_pay', 'to_pay_currency'])
                        ->get();
                    if ($school_fee->count() == 0) {
                        $total_topay = null;
                        $topay = "Not found";
                    }
                    $total_topay = floatval($school_fee->first()->to_pay);
                    $currency = $school_fee->first()->to_pay_currency;
                }
            } else {
                // This student doesn't have scholarship
                $school_fee = $school_fee
                    ->select(['to_pay', 'to_pay_currency'])
                    ->get();
                if ($school_fee->count() == 0) {
                    $total_topay = null;
                    $topay = "Not found"; // This mean, scholarship fee isn't update to the latest version, ask finance staff to update it !!
                } else {
                    $total_topay = floatval($school_fee->first()->to_pay);
                    $currency = $school_fee->first()->to_pay_currency;
                }
            }
        }

        return array('total'=>$total_topay,'currency'=>$currency);
    }

    public function getPaidAttribute(){
        $paids = Income::select(['amount_dollar','amount_riel'])
            ->where('payslip_client_id',$this->payslip_client_id)
            ->where('is_refund',false)->get();

        $total_paid = 0;
        foreach($paids as $paid){
            if($paid->amount_dollar != ''){
                $total_paid += floatval($paid->amount_dollar);
            } else {
                $total_paid += floatval($paid->amount_riel);
            }
        }

        return $total_paid;
    }

    public function promotion(){
        return $this->belongsTo('App\Models\Promotion');
    }
    public function history(){
        return $this->belongsTo('App\Models\History');
    }
    public function department(){
        return $this->belongsTo('App\Models\Department');
    }
    public function department_option(){
        return $this->belongsTo('App\Models\DepartmentOption');
    }
    public function degree(){
        return $this->belongsTo('App\Models\Degree');
    }

    public function grade(){
        return $this->belongsTo('App\Models\Grade');
    }

    public function academic_year(){
        return $this->belongsTo('App\Models\AcademicYear');
    }

    public function student(){
        return $this->belongsTo('App\Models\Student');
    }

    public function scholarships(){
        return $this->belongsToMany('App\Models\Scholarship',"scholarship_student_annual");
    }

    public function creator(){
        return $this->belongsTo('App\Models\Access\User','create_uid');
    }
    public function lastModifier(){
        return $this->belongsTo('App\Models\Access\User','write_uid');
    }

    public function payslipClient(){
        return $this->belongsTo('App\Models\PayslipClient');
    }

    public function evalStatus()
    {
        return $this->belongsToMany('App\Models\StudentEvalStatus');
    }

    public function groupStudentAnnuals()
    {
        return $this->belongsToMany('App\Models\Group', 'group_student_annuals', 'student_annual_id', 'group_id');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "promotion" => "integer"
    ];

	public static $rules = [

	];

	public function distributionDepartmentResult ()
    {
        return $this->hasOne(DistributionDepartmentResult::class);
    }
}
