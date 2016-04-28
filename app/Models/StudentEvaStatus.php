<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class StudentEvaStatus
 * @package App\Models
 */
class StudentEvaStatus extends Model
{

    public $table = 'studentEvalStatuses';
    


    public $fillable = [
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];
}
