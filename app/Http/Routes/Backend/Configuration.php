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
    Route::group([], function() {
        Route::resource('accounts', 'AccountController');
        Route::get('account-data', 'AccountController@data')->name('admin.configuration.account.data');
    });
    Route::group([], function() {
        Route::resource('buildings', 'BuildingController');
        Route::get('building-data', 'BuildingController@data')->name('admin.configuration.building.data');
    });
    Route::group([], function() {
        Route::resource('highSchools', 'HighSchoolController');
        Route::get('highSchool-data', 'HighSchoolController@data')->name('admin.configuration.highSchool.data');
    });
    Route::group([], function() {
        Route::resource('incomeTypes', 'IncomeTypeController');
        Route::get('incomeType-data', 'IncomeTypeController@data')->name('admin.configuration.incomeType.data');
    });
    Route::group([], function() {
        Route::resource('outcomeTypes', 'OutcomeTypeController');
        Route::get('outcomeType-data', 'OutcomeTypeController@data')->name('admin.configuration.outcomeType.data');
    });
    Route::group([], function() {
        Route::resource('rooms', 'RoomController');
        Route::get('room-data', 'RoomController@data')->name('admin.configuration.room.data');
    });
    Route::group([], function() {
        Route::resource('studentBac2s', 'StudentBac2Controller');
        Route::get('studentBac2-data', 'StudentBac2Controller@data')->name('admin.configuration.studentBac2.data');
    });
});