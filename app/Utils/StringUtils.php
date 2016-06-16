<?php namespace App\Utils;

use Illuminate\Support\Facades\DB;


class StringUtils
{
    public static function dbString($string){
        return DB::connection()->getPdo()->quote($string);
    }
}