<?php

namespace App\Http\Requests\Backend\Schedule\Timetable;

use App\Http\Requests\Request;

/**
 * Class ShowTimetableRequest
 * @package App\Http\Requests\Backend\Schedule\Timetable
 */
class ShowTimetableRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (access()->allow('view-timetable')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
