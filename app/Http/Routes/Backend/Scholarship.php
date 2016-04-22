<?php

    Route::group([], function() {
        Route::get('scholarships/request-import-holder', 'ScholarshipController@request_import_holder')->name('admin.scholarship.request_import_holder');
        Route::post('scholarships/import-holder', 'ScholarshipController@import_holder')->name('admin.scholarship.import_holder');

        Route::resource('scholarships', 'ScholarshipController');
        Route::get('scholarship-data', 'ScholarshipController@data')->name('admin.scholarship.data');
        Route::get('scholarship-request-import', 'ScholarshipController@request_import')->name('admin.scholarship.request_import');
        Route::post('scholarship-import', 'ScholarshipController@import')->name('admin.scholarship.import');
        Route::post('scholarships/search', 'ScholarshipController@import')->name('admin.scholarship.search');
        Route::post('scholarships/holder', 'ScholarshipController@add_holder')->name('admin.scholarship.holder');

    });
