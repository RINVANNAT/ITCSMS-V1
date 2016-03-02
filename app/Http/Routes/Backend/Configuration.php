<?php

/**
 * This overwrites the Log Viewer Package routes so we can use middleware to protect it the way we want
 * You shouldn't have to change anything
 */

Route::group([
    'prefix'     => 'configuration',
    'namespace' => 'Configuration'
], function() {

    Route::group([], function() {
        Route::resource('departments', 'DepartmentController');
        Route::get('department-data', 'DepartmentController@data')->name('admin.configuration.department.data');
    });
    Route::group([], function() {
        Route::resource('degrees', 'DegreeController');
        Route::get('degree-data', 'DegreeController@data')->name('admin.configuration.degree.data');
    });
    Route::group([], function() {
        Route::resource('grades', 'GradeController');
        Route::get('grade-data', 'GradeController@data')->name('admin.configuration.grade.data');
    });
    Route::group([], function() {
        Route::resource('academicYears', 'AcademicYearController');
        Route::get('academicYear-data', 'AcademicYearController@data')->name('admin.configuration.academicYear.data');
    });
});