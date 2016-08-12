<?php


Route::group([
    'prefix'     => 'course',
    'namespace' => 'Course'
], function() {

    Route::group([], function() {
        Route::resource('course_annual', 'CourseAnnualController');
        Route::post('course-annual-data', 'CourseAnnualController@data')->name('admin.course.course_annual.data');
        Route::get('course-request-import', 'CourseAnnualController@request_import')->name('admin.course.course_annual.request_import');
        Route::post('course-import', 'CourseAnnualController@import')->name('admin.course.course_annual.import');

    });
    Route::group([], function() {
        Route::get('course-program-data', 'CourseController@data')->name('admin.course.course_program.data');
        Route::get('course-program-request-import', 'CourseController@request_import')->name('admin.course.course_program.request_import');
        Route::post('course-program-import', 'CourseController@import')->name('admin.course.course_program.import');
        Route::resource('course_program', 'CourseController');
        Route::get('course-request-import-config', 'CourseController@request_import_config')->name('admin.course.course_program.request_import_config');
        Route::post('course-import-config', 'CourseController@import_config')->name('admin.course.course_program.import_config');

    });
});
