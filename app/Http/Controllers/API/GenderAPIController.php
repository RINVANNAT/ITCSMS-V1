<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Utils\FormParamManager;

class GenderAPIController extends Controller
{
    public function getAll() {

        $genders = DB::table('genders')->get();
        return $genders;
    }

    public function unique(Request $request) {
        $params = FormParamManager::getFormParams($request);
        $degree = DB::table('genders')->where('id',$params['gender_id'])->first();
        return (array) $degree;
    }
}
