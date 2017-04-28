<?php
/**
 * Created by PhpStorm.
 * User: vannat_gic
 * Date: 3/29/17
 * Time: 11:48 AM
 */

Route::get('/score', 'StudentApiController@studentScoreAnnually')->name('student.score');
Route::get('/data', 'StudentApiController@studentDataFromDB')->name('student.data');
Route::get('/annual-object', 'StudentApiController@studentObject')->name('student.annual_object');
Route::get('/program', 'StudentApiController@student_program')->name('student.program');