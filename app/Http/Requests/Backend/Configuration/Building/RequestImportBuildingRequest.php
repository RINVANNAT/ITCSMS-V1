<?php

namespace App\Http\Requests\Backend\Configuration\Building;

use App\Http\Requests\Request;

/**
 * Class RequestImportBuildingRequest
 * @package App\Http\Requests\Backend\Configuration\Building
 */
class RequestImportBuildingRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-buildings');
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
