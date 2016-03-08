<?php

    Route::group([], function() {
        Route::resource('studentAnnuals', 'StudentAnnualController');
        Route::get('student-data/{scholarship_id}', 'StudentAnnualController@data')->name('admin.student.data');
        Route::get('student-request-import', 'StudentAnnualController@request_import')->name('admin.student.request_import');
        Route::post('student-import', 'StudentAnnualController@import')->name('admin.student.import');

        Route::get('student/{id}/reporting', 'StudentAnnualController@reporting')->name('admin.student.reporting');
        Route::post('student/{id}/reporting-data', 'StudentAnnualController@reporting_data')->name('admin.student.reporting_data');
    });
