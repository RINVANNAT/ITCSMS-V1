<?php namespace App\Http\Requests\Backend\Course\CourseSession;

use App\Http\Requests\Request;

/**
 * Class UpdateCourseSessionRequest
 * @package App\Http\Requests\Backend\Configuration\CourseSession
 */
class UpdateCourseSessionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-courseSessions');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "lecturer_id" => "required|numeric",
            "course_annual_id" => "required|numeric",
            "time_tp" => "required|numeric",
            "time_course" => "required|numeric",
            "time_td" => "required|numeric"
        ];
    }
}
