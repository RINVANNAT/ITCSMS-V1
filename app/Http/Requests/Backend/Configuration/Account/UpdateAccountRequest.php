<?php

namespace App\Http\Requests\Backend\Configuration\Account;

use App\Http\Requests\Request;

/**
 * Class UpdateAccountRequest
 * @package App\Http\Requests\Backend\Configuration\Account
 */
class UpdateAccountRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('edit-accounts');
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
