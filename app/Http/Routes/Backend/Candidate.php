<?php

    Route::group([], function() {
        Route::resource('candidates', 'CandidateController');

        Route::post('candidate-data', 'CandidateController@data')->name('admin.candidate.data');
        Route::get('candidate-request-import', 'CandidateController@request_import')->name('admin.candidate.request_import');
        Route::post('candidate-import', 'CandidateController@import')->name('admin.candidate.import');

        Route::get('candidate/{id}/register', 'CandidateController@register')->name('admin.candidate.register');
        Route::get('candidate/request_register_student_dut', 'CandidateController@requestRegisterStudentDUT')->name('admin.candidate.request_register_student_dut');//{id}= exam_id
    });
