<?php

namespace App\Http\Requests\Backend\Configuration\OutcomeType;

use App\Http\Requests\Request;

/**
 * Class EditOutcomeTypeRequest
 * @package App\Http\Requests\Backend\Configuration\OutcomeType
 */
class EditOutcomeTypeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-outcomeTypes');
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
