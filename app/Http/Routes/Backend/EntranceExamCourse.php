<?php

Route::group([], function() {
    Route::resource('entranceExamCourses', 'EntranceExamCourseController');
    Route::post('entranceExamCourses/{exam_id}/data', 'EntranceExamCourseController@data')->name('admin.entranceExamCourses.data');
    Route::post('entranceExamCourses/{course_id}/data_score', 'EntranceExamCourseController@data_score')->name('admin.entranceExamCourses.data_score');

});