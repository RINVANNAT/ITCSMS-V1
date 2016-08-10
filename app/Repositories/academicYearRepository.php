<?php

namespace App\Repositories;

use App\Models\AcademicYear;
use InfyOm\Generator\Common\BaseRepository;

class academicYearRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return AcademicYear::class;
    }
}
