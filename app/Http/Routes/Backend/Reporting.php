<?php

    Route::group([], function() {
        Route::resource('reporting', 'ReportingController');
        Route::get('reporting-data', 'ReportingController@data')->name('admin.reporting.data');
        Route::post('reporting/status/{id}', 'ReportingController@change_status')->name('admin.reporting.status');
    });
