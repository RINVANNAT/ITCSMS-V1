<?php

    Route::group([], function() {
        Route::resource('studentAnnuals', 'StudentAnnualController');
        Route::get('student-data', 'StudentAnnualController@data')->name('admin.student.data');
        Route::get('student-request-import', 'StudentAnnualController@request_import')->name('admin.student.request_import');
        Route::post('student-import', 'StudentAnnualController@import')->name('admin.student.import');
        Route::get('student/export', 'StudentAnnualController@export_list')->name('admin.student.export');

        Route::get('student/{id}/reporting', 'StudentAnnualController@reporting')->name('admin.student.reporting');

        Route::get('student/{id}/reporting/export', 'StudentAnnualController@export')->name('admin.student.reporting.export');
        Route::get('student/{id}/reporting/print', 'StudentAnnualController@print_report')->name('admin.student.reporting.print');
        Route::get('student/{id}/reporting/preview', 'StudentAnnualController@preview_report')->name('admin.student.reporting.preview');
    });
