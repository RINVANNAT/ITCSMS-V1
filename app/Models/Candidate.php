<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Model;

class Candidate extends Model
{

    public $fillable = [
        "name_latin",
        "name_kh",
        "register_id",
        "dob",
        "mcs_no",
        "can_id",
        "phone",
        "email",
        "address",
        "address_current",
        "register_from",
        "promotion_id",
        "gender_id",
        "math_c",
        "math_w",
        "math_na",
        "phys_chem_c",
        "phys_chem_w",
        "phys_chem_na",
        "logic_c",
        "logic_w",
        "logic_na",
        "total_s",
        "average",
        "bac_percentile",
        "highschool_id",
        "bac_total_grade",
        "bac_math_grade",
        "bac_phys_grade",
        "bac_chem_grade",
        "bac_year",
        "province_id",
        "pob",
        "gender_id",
        "create_uid",
        "write_uid",
        "academic_year_id",
        "degree_id",
        "exam_id",
        "studentBac2_id"
    ];


    public $table = "candidates";
    protected $dates = ['dob'];

    public function setDobAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/Y', $value);
        $this->attributes['dob'] = $date->format('Y/m/d');
    }

    public function getToPayAttribute()
    {
        $scholarship_id = null;
        $to_pay = \App\Models\SchoolFeeRate::where('promotion_id',$this->promotion_id)->where('degree_id',$this->_id);
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

    /*public function getTotalTransactionAttribute(){
        $payslips = \App\Models\Payslip::where('candidate_id',$this->id)->get();

        $total_income_dollar = 0;

        foreach($payslips as $payslip){

        }
    }*/

	public function creator(){
		return $this->belongsTo('App\Models\Access\User','create_uid');
	}
	public function lastModifier(){
		return $this->belongsTo('App\Models\Access\User','write_uid');
	}
	public function academic_year(){
		return $this->belongsTo('App\Models\AcademicYear','academic_year_id');
	}

    public function bacYear(){
        return $this->belongsTo('App\Models\AcademicYear','bac_year');
    }

    public function bacTotal(){
        return $this->belongsTo('App\Models\GdeGrade','bac_total_grade');
    }
    public function bacMath(){
        return $this->belongsTo('App\Models\GdeGrade','bac_math_grade');
    }
    public function bacPhys(){
        return $this->belongsTo('App\Models\GdeGrade','bac_phys_grade');
    }
    public function bacChem(){
        return $this->belongsTo('App\Models\GdeGrade','bac_chem_grade');
    }

    public function exam(){
        return $this->belongsTo('App\Models\Exam');
    }

    public function gender(){
        return $this->belongsTo('App\Models\Gender');
    }

    public function origin(){
        return $this->belongsTo('App\Models\Origin','province_id');
    }

    public function pob(){
        return $this->belongsTo('App\Models\Origin','pob');
    }

    public function degree(){
        return $this->belongsTo('App\Models\Degree');
    }

    public function department(){
        return $this->belongsTo('App\Models\Department');
    }

    public function grade(){
        return $this->belongsTo('App\Models\Grade');
    }

    public function departments(){
        //return $this->belongsToMany('App\Models\Department')->withPivot('rank')->where('is_success',true);
        return $this->belongsToMany('App\Models\Department','candidate_department')->withPivot('rank');
    }


    public function payslipClient(){
        return $this->belongsTo('App\Models\PayslipClient');
    }

    public function high_school(){
        return $this->belongsTo('App\Models\HighSchool','highschool_id');
    }

    public function promotion(){
        return $this->belongsTo('App\Models\Promotion','promotion_id');
    }

    public function preferred_departments(){
        return $this->belongsToMany('App\Models\Department')->withPivot('rank');
    }

    public function room(){
        return $this->belongsTo('App\Models\ExamRoom','room_id');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

	public static $rules = [
	];

}
