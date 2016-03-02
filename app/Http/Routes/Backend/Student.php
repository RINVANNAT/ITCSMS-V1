<?php

    Route::group([], function() {
        Route::resource('studentAnnuals', 'StudentAnnualController');
        Route::get('student-data', 'StudentAnnualController@data')->name('admin.student.data');
        Route::get('student-request-import', 'StudentAnnualController@request_import')->name('admin.student.request_import');
        Route::post('student-import', 'StudentAnnualController@import')->name('admin.student.import');
    });
