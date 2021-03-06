<?php namespace App\Models;

use App\Models\Schedule\Calendar\Event\Event;
use Illuminate\Database\Eloquent\Model as Model;

class Department extends Model
{

    public $table = "departments";


    public $fillable = [
        "id",
        "name_kh",
        "name_en",
        "name_fr",
        "code",
        "is_specialist",
        "description",
        "school_id",
        "create_uid",
        "write_uid",
        "school_id",
        "type"


    ];

    public function school()
    {
        return $this->belongsTo('App\Models\School');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\Access\User', 'create_uid');
    }

    public function lastModifier()
    {
        return $this->belongsTo('App\Models\Access\User', 'write_uid');
    }

    public function department_options()
    {
        return $this->hasMany('App\Models\DepartmentOption');
    }

    public function student_annuals()
    {
        return $this->hasMany('App\Models\StudentAnnual');
    }

    public function candidates()
    {
        return $this->belongsToMany('App\Models\Candidate', 'candidate_department');
    }

    public function degrees()
    {
        return $this->belongsToMany('App\Models\Degree');
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        "name_kh" => "string",
        "name_en" => "string",
        "name_fr" => "string",
        "code" => "string",
        "description" => "string"
    ];

    public static $rules = [
        "name_kh" => "Required",
        "name_en" => "Required",
        "name_fr" => "Required",
        "code" => "Required"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    /**
     * Get department id by authentication.
     *
     * @return mixed
     */
    public static function getDepartmentIdByAuthentication()
    {
        return Department::find(Employee::where('user_id', auth()->user()->id)->first()->department_id);
    }
}
