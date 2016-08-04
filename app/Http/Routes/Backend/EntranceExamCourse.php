<?php

Route::group([], function() {
    Route::resource('entranceExamCourses', 'EntranceExamCourseController');
    Route::post('entranceExamCourses/{exam_id}/data', 'EntranceExamCourseController@data')->name('admin.entranceExamCourses.data');

    /*Route::post('entranceExamCourses/{id}/get_entranceExamCourses', 'ExamController@get_entranceExamCourses')->name('admin.exam.get_entranceExamCourses');
    Route::get('exams/{id}/request_add_courses', 'ExamController@request_add_courses')->name('admin.exam.request_add_courses');
    Route::delete('exams/delete_entranceExamCourses/{course_id}', 'ExamController@delete_entranceExamCourses')->name('admin.exam.delete_entranceExamCourses');
    Route::post('exams/{id}/save_entrance_exam_course', 'ExamController@save_entrance_exam_course')->name('admin.exam.save_entrance_exam_course');*/



});