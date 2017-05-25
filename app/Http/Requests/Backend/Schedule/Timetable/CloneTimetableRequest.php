<?php

namespace App\Http\Requests\Backend\Schedule\Timetable;

use App\Http\Requests\Request;

/**
 * Class CloneTimetableRequest
 * @package App\Http\Requests\Backend\Schedule\Timetable
 */
class CloneTimetableRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('clone-timetable');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /*return [
            'weeks' => 'required|array|min:1',
            'groups' => 'required|array|min:1'
        ];*/
        return [
            //
        ];
    }

    /**
     * Custom validate error message.
     *
     * @return array
     */
    /*public function messages()
    {
        return [
            'weeks.required' => 'At least chose a week field.',
            'groups.required' => 'At least chose a group field.',
        ];
    }*/
}
