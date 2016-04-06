<?php


Route::group([
    'prefix'     => 'course',
    'namespace' => 'Course'
], function() {

    Route::group([], function() {
        Route::resource('course_annual', 'CourseAnnualController');
        Route::get('course-annual-data', 'CourseAnnualController@data')->name('admin.course.course_annual.data');
    });
    Route::group([], function() {
        Route::resource('course_program', 'CourseController');
        Route::get('course-program-data', 'CourseController@data')->name('admin.course.course_program.data');
    });

});


Route::any('api/v1/courseAnnuals',[
    'as' => 'courseAnnuals.api.v1',
    'uses' => 'Restfull\CourseAnnualApiController@index',
]);