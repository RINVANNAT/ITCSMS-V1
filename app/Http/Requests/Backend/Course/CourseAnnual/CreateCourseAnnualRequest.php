<?php namespace App\Http\Requests\Backend\Course\CourseAnnual;

use App\Http\Requests\Request;
/**
 * Class CreateCourseAnnualRequest
 * @package App\Http\Requests\Backend\Configuration\CourseAnnual
 */
class CreateCourseAnnualRequest extends Request
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
        ];
    }
}
