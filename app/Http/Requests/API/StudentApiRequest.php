<?php

namespace App\Http\Requests\API;

use App\Http\Requests\Request;
use App\Models\Configuration;

class StudentApiRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $clientIp = request()->getClientIp();
        $smis_ftp_server = Configuration::where('key', 'smis_ftp_server')->first();

        return true;

        /*if($clientIp != $smis_ftp_server->value) {
            if($clientIp == '192.168.105.106') {
                return true;
            }
            return false;

        } else {
            return true;
        }*/

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
