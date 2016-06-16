<?php

namespace App\Http\Requests\Backend\Configuration\RoomType;

use App\Http\Requests\Request;

/**
 * Class RequestImportRoomTypeRequest
 * @package App\Http\Requests\Backend\Configuration\RoomType
 */
class RequestImportRoomTypeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-roomTypes');
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
