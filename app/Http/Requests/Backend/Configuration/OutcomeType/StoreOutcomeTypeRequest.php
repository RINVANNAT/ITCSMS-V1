<?php

namespace App\Http\Requests\Backend\Configuration\OutcomeType;

use App\Http\Requests\Request;

/**
 * Class StoreOutcomeTypeRequest
 * @package App\Http\Requests\Backend\Configuration\OutcomeType
 */
class StoreOutcomeTypeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-outcomeTypes');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => 'required|unique:outcomeTypes|max:4',
            'name' => 'required',
            'origin' => 'max:10'
        ];
    }
}
