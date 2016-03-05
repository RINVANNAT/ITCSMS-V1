<?php

Route::group([], function() {
    Route::resource('exams', 'ExamController', ['except' => ['index','create','store']]);
    Route::get('exam/{id}/index', 'ExamController@index')->name('admin.exam.index');
    Route::get('exam/{id}/create', 'ExamController@create')->name('admin.exam.create');
    Route::post('exam/{id}/store', 'ExamController@store')->name('admin.exam.store');

    Route::get('exam-entrance-engineer-data', 'ExamController@entrance_engineer_data')->name('admin.exam.entrance_engineer.data');
    Route::get('exam-entrance-dut-data', 'ExamController@entrance_dut_data')->name('admin.exam.entrance_dut.data');
    Route::get('exam-final-semester-data', 'ExamController@final_semester_data')->name('admin.exam.final_semester.data');
});
