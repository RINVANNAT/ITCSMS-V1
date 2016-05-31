<?php

    Route::group([], function() {
        /* -------------------- Resource -----------------------*/
        Route::resource('studentAnnuals', 'StudentAnnualController');
        Route::get('student-data', 'StudentAnnualController@data')->name('admin.student.data');

        /* -------------------- IMPORT -----------------------*/
        Route::get('student-request-import', 'StudentAnnualController@request_import')->name('admin.student.request_import');
        Route::post('student-import', 'StudentAnnualController@import')->name('admin.student.import');

        /* -------------------- EXPORT -----------------------*/
        // Request export form
        Route::get('student/request-export-fields', 'StudentAnnualController@request_export_fields')->name('admin.student.request_export_fields');
        Route::get('student/request-export-custom', 'StudentAnnualController@request_export_list_custom')->name('admin.student.request_export_custom');

        Route::post('student/export', 'StudentAnnualController@export')->name('admin.student.export');
        //Route::post('student/export-custom', 'StudentAnnualController@export_list_custom')->name('admin.student.export_custom');



        Route::get('student/{id}/reporting', 'StudentAnnualController@reporting')->name('admin.student.reporting');

        Route::get('student/{id}/reporting/export', 'StudentAnnualController@report')->name('admin.student.reporting.export');
        Route::get('student/{id}/reporting/print', 'StudentAnnualController@print_report')->name('admin.student.reporting.print');
        Route::get('student/{id}/reporting/preview', 'StudentAnnualController@preview_report')->name('admin.student.reporting.preview');

        Route::get('student/popup_index', 'StudentAnnualController@popup_index')->name('admin.student.popup_index');
    });
