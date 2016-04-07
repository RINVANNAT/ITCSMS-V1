<?php

namespace App\Repositories;

use App\Models\academicYear;
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
        return academicYear::class;
    }
}
