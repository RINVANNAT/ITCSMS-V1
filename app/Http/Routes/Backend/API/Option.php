<?php
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 3/30/17
 * Time: 2:04 PM
 */


Route::get('/all', 'DepartmentOptionAPIController@getAll')->name('option.all');
Route::get('/department', 'DepartmentOptionAPIController@getOptionByDeptId')->name('option.get_optioin_by_deparment_id');
Route::get('/unique', 'DepartmentOptionAPIController@unique')->name('option.unique');