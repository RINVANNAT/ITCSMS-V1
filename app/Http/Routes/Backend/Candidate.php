<?php

    Route::group([], function() {
        Route::resource('candidates', 'CandidateController');
        Route::get('candidate-data', 'CandidateController@data')->name('admin.candidate.data');
        Route::get('candidate-request-import', 'CandidateController@request_import')->name('admin.candidate.request_import');
        Route::post('candidate-import', 'CandidateController@import')->name('admin.candidate.import');

        Route::get('candidate/popup_create', 'CandidateController@popup_create')->name('admin.candidate.popup_create');
        Route::post('candidate/popup_store', 'CandidateController@popup_store')->name('admin.candidate.popup_store');

        Route::get('candidate/{id}/register', 'CandidateController@register')->name('admin.candidate.register');
    });
