<?php
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 3/30/17
 * Time: 2:04 PM
 */


Route::get('/all', 'AcademicYearApiController@getAll')->name('academic_year.all');
Route::get('/unique', 'AcademicYearApiController@unique')->name('academic_year.unique');