<?php

namespace App\Repositories;

use App\Models\CourseAnnual;
use InfyOm\Generator\Common\BaseRepository;

class CourseAnnualRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'test'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CourseAnnual::class;
    }
}
