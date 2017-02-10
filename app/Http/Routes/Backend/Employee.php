<?php

    Route::group([], function() {
        Route::resource('employees', 'EmployeeController');
        Route::post('employee-data', 'EmployeeController@data')->name('admin.employee.data');
        Route::get('employee-request-import', 'EmployeeController@request_import')->name('admin.employee.request_import');
        Route::post('employee-import', 'EmployeeController@import')->name('admin.employee.import');
//        Route::post('employees/search', 'EmployeeController@search')->name('admin.employee.search');
        Route::get('employee-search', 'EmployeeController@search')->name('admin.employee.search');
        
       
    });
