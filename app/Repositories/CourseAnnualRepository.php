<?php namespace App\Repositories;

use App\Models\CourseAnnual;
use InfyOm\Generator\Common\BaseRepository;

class CourseAnnualRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        "name",
        "department_id",
        "degree_id",
        "grade_id",
        "academic_year_id",
        "employee_id",
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return CourseAnnual::class;
    }
}
