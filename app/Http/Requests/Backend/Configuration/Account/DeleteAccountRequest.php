<?php

namespace App\Http\Requests\Backend\Configuration\Account;

use App\Http\Requests\Request;

/**
 * Class DeleteAccountRequest
 * @package App\Http\Requests\Backend\Access\Account
 */
class DeleteAccountRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('delete-accounts');
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
