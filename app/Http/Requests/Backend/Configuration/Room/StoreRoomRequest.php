<?php

namespace App\Http\Requests\Backend\Configuration\Room;

use App\Http\Requests\Request;

/**
 * Class StoreRoomRequest
 * @package App\Http\Requests\Backend\Configuration\Room
 */
class StoreRoomRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-rooms');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required',
            'name_en' => 'required',
        ];
    }
}
