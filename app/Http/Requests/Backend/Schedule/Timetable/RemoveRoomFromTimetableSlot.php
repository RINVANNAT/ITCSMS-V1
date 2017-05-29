<?php

namespace App\Http\Requests\Backend\Schedule\Timetable;

use App\Http\Requests\Request;

class RemoveRoomFromTimetableSlot extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('remove-room');
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
