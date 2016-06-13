<?php

Route::group([], function() {
    Route::resource('exams', 'ExamController', ['except' => ['index','create','store']]);
    Route::get('exams/{id}/index', 'ExamController@index')->name('admin.exam.index');
    Route::get('exams/{id}/create', 'ExamController@create')->name('admin.exam.create');
    Route::post('exams/{id}/store', 'ExamController@store')->name('admin.exam.store');

    Route::get('exams/{id}/data', 'ExamController@data')->name('admin.exam.data');
    Route::get('exams/{id}/get_courses', 'ExamController@get_courses')->name('admin.exam.get_courses');
});
