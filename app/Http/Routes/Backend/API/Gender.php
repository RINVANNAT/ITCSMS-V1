<?php
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 3/30/17
 * Time: 2:04 PM
 */


Route::get('/all', 'GenderAPIController@getAll')->name('gender.all');
Route::get('/unique', 'GenderAPIController@unique')->name('gender.unique');