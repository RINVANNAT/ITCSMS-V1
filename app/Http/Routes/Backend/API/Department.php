<?php
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 3/30/17
 * Time: 2:04 PM
 */


Route::get('/all', 'DepartmentApiController@getAll')->name('department.all');
Route::get('/get-unique', 'DepartmentApiController@unique')->name('department.unique');