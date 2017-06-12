<?php

namespace App\Http\Requests\Backend\Schedule\Timetable;

use App\Http\Requests\Request;

/**
 * Class AddRoomIntoTimetableSlotRequest
 * @package App\Http\Requests\Backend\Schedule\Timetable
 */
class AddRoomIntoTimetableSlotRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('add-room-timetable-slot');
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
