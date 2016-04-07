<?php

namespace App\Repositories;

use App\Models\Semester;
use InfyOm\Generator\Common\BaseRepository;

class SemesterRepository extends BaseRepository
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
        return Semester::class;
    }
}
