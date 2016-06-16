<?php namespace App\Utils;


class ArrayUtils{
    /**
     * input
    * $array : array of  array
    * $key : key of array in array to be unique
     *
     * output
     * array of unique array
     *
     * example:

    input:

    $details = array(
    0 => array("id"=>"1", "name"=>"Mike",    "num"=>"9876543210"),
    1 => array("id"=>"2", "name"=>"Carissa", "num"=>"08548596258"),
    2 => array("id"=>"1", "name"=>"Mathew",  "num"=>"784581254"),
    );

    output:

    $details = array(
    0 => array("id"=>"1","name"=>"Mike","num"=>"9876543210"),
    1 => array("id"=>"2","name"=>"Carissa","num"=>"08548596258"),
    );
     */


    public static function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();

        foreach($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[$i] = $val;
            }
            $i++;
        }
        return $temp_array;
    }
}


