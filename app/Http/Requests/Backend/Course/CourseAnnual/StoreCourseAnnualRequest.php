<?php namespace App\Http\Requests\Backend\Course\CourseAnnual;

use App\Http\Requests\Request;

/**
 * Class StoreCourseAnnualRequest
 * @package App\Http\Requests\Backend\Configuration\CourseAnnual
 */
class StoreCourseAnnualRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-courseAnnuals');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'semester_id' => 'required',
            'academic_year_id' => 'required',
            'department_id' => 'required',
            'degree_id' => 'required',
            'grade_id' => 'required',
            'course_id' => 'required',
        ];
    }
}
