<?php

namespace App\Http\Requests\Backend\Internship;

use App\Http\Requests\Request;

class StoreInternshipRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return access()->allow('store-internship');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'person' => 'required',
            'company' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'hot_line' => 'required',
            'e_mail_address' => 'required',
            'web' => 'required',
            'title' => 'required',
            'training_field' => 'required',
            'start' => 'required',
            'end' => 'required'
        ];
    }
}
