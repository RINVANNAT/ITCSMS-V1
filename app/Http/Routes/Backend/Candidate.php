<?php

    Route::group([], function() {
        Route::resource('candidates', 'CandidateController');
        Route::get('candidate-data', 'CandidateController@data')->name('admin.candidate.data');
        Route::get('candidate-request-import', 'CandidateController@request_import')->name('admin.candidate.request_import');
        Route::post('candidate-import', 'CandidateController@import')->name('admin.candidate.import');
    });
