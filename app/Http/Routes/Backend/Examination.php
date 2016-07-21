<?php

Route::group([], function() {
    Route::resource('exams', 'ExamController', ['except' => ['index','create','store']]);
    Route::get('exams/{id}/index', 'ExamController@index')->name('admin.exam.index');
    Route::get('exams/{id}/create', 'ExamController@create')->name('admin.exam.create');
    Route::post('exams/{id}/store', 'ExamController@store')->name('admin.exam.store');

    Route::get('exams/{id}/data', 'ExamController@data')->name('admin.exam.data');

    Route::get('exams/{id}/get_buildings', 'ExamController@get_buildings')->name('admin.exam.get_buildings');

    Route::get('exams/{id}/get_staffs', 'ExamController@get_staffs')->name('admin.exam.get_staffs');
    Route::get('exams/{id}/count_seat_exam', 'ExamController@count_seat_exam')->name('admin.exam.count_seat_exam');

    Route::get('exams/{id}/get_rooms', 'ExamController@get_rooms')->name('admin.exam.get_rooms');
    Route::post('exams/{id}/save_rooms','ExamController@save_rooms')->name('admin.exam.save_rooms');
    Route::post('exams/{id}/delete_rooms','ExamController@delete_rooms')->name('admin.exam.delete_rooms');
    Route::get('exams/{id}/view_room_secret_code', 'ExamController@view_room_secret_code')->name('admin.exam.view_room_secret_code');
    Route::post('exams/{id}/save_room_secret_code', 'ExamController@save_room_secret_code')->name('admin.exam.save_room_secret_code');

    Route::post('exams/{id}/get_courses', 'ExamController@get_courses')->name('admin.exam.get_courses');
    Route::post('exams/{id}/get_entranceExamCourses', 'ExamController@get_entranceExamCourses')->name('admin.exam.get_entranceExamCourses');
    Route::get('exams/{id}/request_add_courses', 'ExamController@request_add_courses')->name('admin.exam.request_add_courses');
    Route::post('exams/{id}/save_entrance_exam_course', 'ExamController@save_entrance_exam_course')->name('admin.exam.save_entrance_exam_course');

    Route::get('exams/{id}/request_input_score_courses', 'ExamController@requestInputScoreCourses')->name('admin.exam.request_input_score_courses');

    Route::get('exams/{id}/candidate_generate_room', 'ExamController@generate_room')->name('admin.exam.candidate.generate_room');
});
