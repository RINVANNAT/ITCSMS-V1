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
        Route::post('department-data', 'DepartmentController@data')->name('admin.configuration.department.data');
    });
    Route::group([], function() {
        Route::resource('degrees', 'DegreeController');
        Route::post('degree-data', 'DegreeController@data')->name('admin.configuration.degree.data');
    });
    Route::group([], function() {
        Route::resource('grades', 'GradeController');
        Route::post('grade-data', 'GradeController@data')->name('admin.configuration.grade.data');
    });
    Route::group([], function() {
        Route::resource('academicYears', 'AcademicYearController');
        Route::post('academicYear-data', 'AcademicYearController@data')->name('admin.configuration.academicYear.data');
    });
    Route::group([], function() {
        Route::resource('accounts', 'AccountController');
        Route::post('account-data', 'AccountController@data')->name('admin.configuration.account.data');
    });
    Route::group([], function() {
        Route::resource('buildings', 'BuildingController');
        Route::post('building-data', 'BuildingController@data')->name('admin.configuration.building.data');
        Route::get('building-request-import', 'BuildingController@request_import')->name('admin.configuration.buildings.request_import');
        Route::post('building-import', 'BuildingController@import')->name('admin.configuration.buildings.import');
    });
    Route::group([], function() {
        Route::resource('highSchools', 'HighSchoolController');
        Route::post('highSchool-data', 'HighSchoolController@data')->name('admin.configuration.highSchool.data');
        Route::get('highSchool-request-import', 'HighSchoolController@request_import')->name('admin.configuration.highSchool.request_import');
        Route::post('highSchool-import', 'HighSchoolController@import')->name('admin.configuration.highSchool.import');
        Route::get('highSchool/search', 'HighSchoolController@highschool_search')->name('admin.configuration.highSchool.search');
    });
    Route::group([], function() {
        Route::resource('incomeTypes', 'IncomeTypeController');
        Route::post('incomeType-data', 'IncomeTypeController@data')->name('admin.configuration.incomeType.data');
    });
    Route::group([], function() {
        Route::resource('outcomeTypes', 'OutcomeTypeController');
        Route::post('outcomeType-data', 'OutcomeTypeController@data')->name('admin.configuration.outcomeType.data');

        Route::get('outcomeType-request-import', 'OutcomeTypeController@request_import')->name('admin.outcomeType.request_import');
        Route::post('outcomeType-import', 'OutcomeTypeController@import')->name('admin.outcomeType.import');
    });
    Route::group([], function() {
        Route::resource('rooms', 'RoomController');
        Route::post('room-data', 'RoomController@data')->name('admin.configuration.room.data');
        Route::get('room-request-import', 'RoomController@request_import')->name('admin.configuration.rooms.request_import');
        Route::post('room-import', 'RoomController@import')->name('admin.configuration.rooms.import');
    });
    Route::group([], function() {
        Route::resource('studentBac2s', 'StudentBac2Controller');
        Route::post('studentBac2-data', 'StudentBac2Controller@data')->name('admin.configuration.studentBac2.data');
        Route::get('studentBac2-request-import', 'StudentBac2Controller@request_import')->name('admin.configuration.studentBac2.request_import');
        Route::post('studentBac2-import', 'StudentBac2Controller@import')->name('admin.configuration.studentBac2.import');

        Route::get('studentBac2/popup_index', 'StudentBac2Controller@popup_index')->name('admin.studentBac2.popup_index');
    });
    Route::group([], function() {
        Route::resource('schoolFees', 'SchoolFeeRateController');
        Route::post('schoolFee-data/{with_scholarships}', 'SchoolFeeRateController@data')->name('admin.configuration.schoolFee.data');
        Route::get('schoolFee-request-import', 'SchoolFeeRateController@request_import')->name('admin.configuration.schoolFee.request_import');
        Route::post('schoolFee-import', 'SchoolFeeRateController@import')->name('admin.configuration.schoolFee.import');
    });

    Route::group([], function() {
        Route::resource('departmentOptions', 'DepartmentOptionController');
        Route::post('departmentOption-data', 'DepartmentOptionController@data')->name('admin.configuration.departmentOption.data');
    });

    Route::group([], function() {
        Route::resource('promotions', 'PromotionController');
        Route::post('promotion-data', 'PromotionController@data')->name('admin.configuration.promotion.data');
    });

    Route::group([], function() {
        Route::resource('roomTypes', 'RoomTypeController');
        Route::post('roomType-data', 'RoomTypeController@data')->name('admin.configuration.roomType.data');
        Route::get('roomType-request-import', 'RoomTypeController@request_import')->name('admin.configuration.roomTypes.request_import');
        Route::post('roomType-import', 'RoomTypeController@import')->name('admin.configuration.roomTypes.import');
    });

    Route::group([], function() {
        Route::resource('redoubles', 'RedoubleController');
        Route::post('redouble-data', 'RedoubleController@data')->name('admin.configuration.redouble.data');
    });
});