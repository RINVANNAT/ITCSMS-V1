<?php namespace App\Repositories;

use App\Models\AcademicYear;
use InfyOm\Generator\Common\BaseRepository;

class AcademicYearRepository extends BaseRepository
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
        return \App\Models\AcademicYear::class;
    }
}
