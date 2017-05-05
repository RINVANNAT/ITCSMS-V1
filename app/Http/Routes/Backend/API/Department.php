<?php
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 3/30/17
 * Time: 2:04 PM
 */


Route::get('/all', 'DepartmentAPIController@getAll')->name('department.all');
Route::get('/get-unique', 'DepartmentAPIController@unique')->name('department.unique');