<?php

namespace App\Http\Requests\Backend\Configuration\RoomType;

use App\Http\Requests\Request;

/**
 * Class UpdateRoomTypeRequest
 * @package App\Http\Requests\Backend\Configuration\RoomType
 */
class UpdateRoomTypeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-roomTypes');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255|unique:roomTypes',
        ];
    }
}
