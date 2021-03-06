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
Route::get('/prop', 'StudentApiController@student_prop')->name('student.prop');
Route::post('/dept-by-student', 'StudentApiController@studentByDept')->name('student.department');
Route::get('/student-classmate', 'StudentApiController@studentClassmate')->name('student.classmate');
Route::post('get-students', 'StudentApiController@getStudents')->name('student.get-students');