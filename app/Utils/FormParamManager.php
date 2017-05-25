<?php
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 3/30/17
 * Time: 9:29 AM
 */

namespace App\Utils;


class FormParamManager
{


    public static function getFormParams($request) {

        $paramData = [];
        $params = $request->input('params');
        $attributes = $request->input('attributes');

        $where = $request->where;
        $token = $request->token;

        $params = explode(' ', $params);
        $attributes = explode(' ', $attributes);

        foreach($params as $key =>  $param) {

            $paramData =  array_merge($paramData, [$param => $attributes[$key] ]);
        }
        return $paramData;
    }

    public static function getArrayFormParams($request) {

        $params = $request->all();
        return $params;
    }

    public static function studentIdParams($request) {

    }
}