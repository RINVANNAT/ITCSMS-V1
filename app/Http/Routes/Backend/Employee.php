<?php

    Route::group([], function() {
        Route::resource('employees', 'EmployeeController');
        Route::get('employee-data', 'EmployeeController@data')->name('admin.employee.data');
        Route::get('employee-request-import', 'EmployeeController@request_import')->name('admin.employee.request_import');
        Route::post('employee-import', 'EmployeeController@import')->name('admin.employee.import');
        Route::post('employees/search', 'EmployeeController@import')->name('admin.employee.search');
    });