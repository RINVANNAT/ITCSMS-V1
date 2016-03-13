<?php

namespace App\Http\Requests\Backend\Configuration\Redouble;

use App\Http\Requests\Request;

/**
 * Class StoreRedoubleRequest
 * @package App\Http\Requests\Backend\Configuration\Redouble
 */
class StoreRedoubleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('create-redoubles');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_en' => 'required|max:255|unique:redoubles',
            'name_kh' => 'max:255',
            'name_fr' => 'max:255',
        ];
    }
}
