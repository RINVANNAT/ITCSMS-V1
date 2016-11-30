<?php namespace App\Http\Requests\Backend\Course\CourseAnnual;

use App\Http\Requests\Request;

/**
 * Class GenerateCourseAnnualRequest
 * @package App\Http\Requests\Backend\Configuration\CourseAnnual
 */
class GenerateCourseAnnualRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('generate-course-annual');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
}
