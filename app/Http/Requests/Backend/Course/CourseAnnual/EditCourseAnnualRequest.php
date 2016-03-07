<?php

namespace App\Http\Requests\Backend\Configuration\CourseAnnual;

use App\Http\Requests\Request;

/**
 * Class EditCourseAnnualRequest
 * @package App\Http\Requests\Backend\Configuration\CourseAnnual
 */
class EditCourseAnnualRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-courseAnnuals');
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
