<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Model;

class StudentBac2 extends Model
{

    public $fillable = [
        "can_id",
        "mcs_no",
        "province_id",
        "name_kh",
        "dob",
        "gender_id",
        'father_name',
        "mother_name",
        "pob",
        "highschool_id",
        "room",
        "seat",
        "bac_math_grade",
        "bac_phys_grade",
        "bac_chem_grade",
        "percentile",
        "grade",
        "program",
        "desc",
        "bac_year",
        "status",
        "is_registered"
    ];

    public $timestamps = false;
    public $table = "studentBac2s";

    public function setDobAttribute($date){
        $this->attributes['dob'] = Carbon::createFromFormat('m/d/y H:i:s', $date);
    }

    public function getDobAttribute($date){
        $carbon_date = Carbon::createFromFormat('Y-m-d H:i:s', $date);
        $result = $carbon_date->format('d/m/Y');
        return $result;
    }

    public function gender(){
        return $this->belongsTo('App\Models\Gender');
    }

    public function bacYear(){
        return $this->belongsTo('App\Models\AcademicYear','bac_year');
    }

    public function bacTotal(){
        return $this->belongsTo('App\Models\GdeGrade','grade');
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

    public function placeOfBirth(){
        return $this->belongsTo('App\Models\Origin','pob');
    }

    public function province(){
        return $this->belongsTo('App\Models\Origin','province_id');
    }

    public function highSchool(){
        return $this->belongsTo('App\Models\highSchools','highschool_id');
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
