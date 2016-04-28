<?php

namespace App\Repositories;

use App\Models\ScoreEvaluation;
use InfyOm\Generator\Common\BaseRepository;

class ScoreEvaluationRepository extends BaseRepository
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
        return ScoreEvaluation::class;
    }
}
