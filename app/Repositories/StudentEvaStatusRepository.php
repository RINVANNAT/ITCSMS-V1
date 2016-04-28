<?php

namespace App\Repositories;

use App\Models\StudentEvaStatus;
use InfyOm\Generator\Common\BaseRepository;

class StudentEvaStatusRepository extends BaseRepository
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
        return StudentEvaStatus::class;
    }
}
