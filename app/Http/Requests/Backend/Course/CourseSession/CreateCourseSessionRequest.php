<?php namespace App\Http\Requests\Backend\Course\CourseSession;

use App\Http\Requests\Request;

/**
 * Class CreateCourseSessionRequest
 * @package App\Http\Requests\Backend\Configuration\CourseSession
 */
class CreateCourseSessionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-courseCourseSessions');
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
