<?php

Route::group(['middleware' => 'auth', 'prefix' => 'internship', 'namespace' => 'Internship'], function () {
    Route::get('/', 'InternshipController@index')->name('internship.index');
    Route::get('/create', 'InternshipController@create')->name('internship.create');
    Route::get('{internships}/print_internship', 'InternshipController@print_internship')->name('internship.print_internship');
    Route::get('{internship}/edit', 'InternshipController@edit')->name('internship.edit');
    Route::post('/store', 'InternshipController@store')->name('internship.store');
    Route::get('{internship}/delete', 'InternshipController@delete')->name('internship.delete');
    Route::get('/data', 'InternshipController@data')->name('internship.data');
    Route::get('student-search', 'InternshipController@search')->name('internship.search');
    Route::post('get-students', 'InternshipController@getStudents')->name('internship.get-students');
    Route::post('/mark_as_printed', 'InternshipController@markPrinted')->name('internship.mark_as_printed');

    Route::post('/remote-internship-companies', 'InternshipController@remoteInternshipCompanies')->name('internship.remote-internship-companies');
});