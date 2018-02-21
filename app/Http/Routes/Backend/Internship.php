<?php

Route::group(['middleware' => 'auth', 'prefix' => 'internship', 'namespace' => 'Internship'], function () {
    Route::get('/', 'InternshipController@index')->name('internship.index');
    Route::get('/create', 'InternshipController@create')->name('internship.create');
    Route::get('/edit', 'InternshipController@edit')->name('internship.edit');
    Route::post('/store', 'InternshipController@store')->name('internship.store');
    Route::get('student-search', 'InternshipController@search')->name('internship.search');
});