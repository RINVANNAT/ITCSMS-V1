<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

class StudentAnnual extends Model
{
    
	public $table = "studentAnnuals";
    

	public $fillable = [
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
        "department_option_id"
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

    public function getToPayAttribute()
    {
        $scholarship_id = null;
        $to_pay = \App\Models\SchoolFeeRate::where('promotion_id',$this->promotion_id)->where('degree_id',$this->degree_id);
        if($this->gender_id == 2){
            $scholarship_id = 1; // This is Boursier Partielle for all woman in ITC
            $to_pay = $to_pay->where('scholarship_id',$scholarship_id);
        }
        $to_pay = $to_pay->first();
        if($to_pay==null){
            return 0;
        } else {
            return $to_pay->to_pay.$to_pay->to_pay_currency;
        }
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
        return $this->belongsToMany('App\Models\Scholarship');
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

}
