<?php
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 3/30/17
 * Time: 2:04 PM
 */


Route::get('/all', 'DegreeAPIController@getAll')->name('degree.all');
Route::get('/unique', 'DegreeAPIController@unique')->name('degree.unique');