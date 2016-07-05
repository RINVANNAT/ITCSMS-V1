<?php

namespace App\Http\Requests\API;

use App\Models\CourseAnnual;
use InfyOm\Generator\Request\APIRequest;

class CreateCourseAnnualAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "department_id" => "Required",
            "degree_id" => "Required|numeric",
            "grade_id" => "Required|numeric",
            "academic_year_id" => "Required|numeric",
        ];
    }
}
