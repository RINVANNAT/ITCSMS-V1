<?php
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 3/30/17
 * Time: 2:04 PM
 */


Route::get('/all', 'GradeAPIController@getAll')->name('grade.all');
Route::get('/unique', 'GradeAPIController@unique')->name('grade.unique');