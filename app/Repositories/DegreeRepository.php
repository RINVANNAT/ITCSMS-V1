<?php

namespace App\Repositories;

use App\Models\Degree;
use InfyOm\Generator\Common\BaseRepository;

class DegreeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Degree::class;
    }
}
