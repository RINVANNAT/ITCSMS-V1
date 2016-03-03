<?php

namespace App\Http\Requests\Backend\Configuration\OutcomeType;

use App\Http\Requests\Request;

/**
 * Class DeleteOutcomeTypeRequest
 * @package App\Http\Requests\Backend\Configuration\OutcomeType
 */
class DeleteOutcomeTypeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('delete-outcomeTypes');
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
