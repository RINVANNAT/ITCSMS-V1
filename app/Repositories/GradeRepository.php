<?php

namespace App\Repositories;

use App\Models\Grade;
use InfyOm\Generator\Common\BaseRepository;

class GradeRepository extends BaseRepository
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
        return Grade::class;
    }
}
