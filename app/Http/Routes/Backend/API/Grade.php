<?php
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 3/30/17
 * Time: 2:04 PM
 */


Route::get('/all', 'GradeApiController@getAll')->name('grade.all');
Route::get('/unique', 'GradeApiController@unique')->name('grade.unique');