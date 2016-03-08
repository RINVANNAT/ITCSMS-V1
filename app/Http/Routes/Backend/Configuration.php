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
        Route::get('highSchool-request-import', 'HighSchoolController@request_import')->name('admin.configuration.highSchool.request_import');
        Route::post('highSchool-import', 'HighSchoolController@import')->name('admin.configuration.highSchool.import');
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
        Route::get('studentBac2-request-import', 'StudentBac2Controller@request_import')->name('admin.configuration.studentBac2.request_import');
        Route::post('studentBac2-import', 'StudentBac2Controller@import')->name('admin.configuration.studentBac2.import');
    });
    Route::group([], function() {
        Route::resource('schoolFees', 'SchoolFeeRateController');
        Route::get('schoolFee-data/{with_scholarships}/{scholarship_id}', 'SchoolFeeRateController@data')->name('admin.configuration.schoolFee.data');
        Route::get('schoolFee-request-import', 'SchoolFeeRateController@request_import')->name('admin.configuration.schoolFee.request_import');
        Route::post('schoolFee-import', 'SchoolFeeRateController@import')->name('admin.configuration.schoolFee.import');
    });
});