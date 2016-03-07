<?php

    Route::group([], function() {
        Route::resource('scholarships', 'ScholarshipController');
        Route::get('scholarship-data', 'ScholarshipController@data')->name('admin.scholarship.data');
        Route::get('scholarship-request-import', 'ScholarshipController@request_import')->name('admin.scholarship.request_import');
        Route::post('scholarship-import', 'ScholarshipController@import')->name('admin.scholarship.import');
        Route::post('scholarships/search', 'ScholarshipController@import')->name('admin.scholarship.search');
    });
