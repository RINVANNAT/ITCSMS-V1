<?php namespace App\Http\Requests\Backend\Course\CourseAnnual;
use App\Http\Requests\Request;

/**
 * Class DeleteCourseAnnualRequest
 * @package App\Http\Requests\Backend\Configuration\CourseAnnual
 */
class DeleteCourseAnnualRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('delete-courseAnnuals');
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
