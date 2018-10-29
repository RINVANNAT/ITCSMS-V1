<?php

    Route::group([], function() {
        Route::resource('candidates', 'CandidateController');

        Route::post('candidate-data', 'CandidateController@data')->name('admin.candidate.data');
        Route::get('candidate-request-import', 'CandidateController@request_import')->name('admin.candidate.request_import');
        Route::post('candidate-import', 'CandidateController@import')->name('admin.candidate.import');

        Route::get('candidate/{id}/register', 'CandidateController@register')->name('admin.candidate.register');
        Route::get('candidate/request_register_student_dut', 'CandidateController@requestRegisterStudentDUT')->name('admin.candidate.request_register_student_dut');//{id}= exam_id

        // Choix department for candidate
        Route::get('candidate/register_candidate_department', 'CandidateController@register_candidate_department')->name('admin.candidate.register_candidate_department');
        Route::post('candidate/list_candidate_department', 'CandidateController@list_candidate_department')->name('admin.candidate.list_candidate_department');
        Route::delete('candidate/clear_department/{id}', 'CandidateController@clear_department')->name('admin.candidate.clear_department');
        Route::post('candidate/store_candidate_department', 'CandidateController@store_candidate_department')->name('admin.candidate.store_candidate_department');
        Route::get('candidate/export_chosen_departments', 'CandidateController@export_chosen_departments')->name('admin.candidate.export_chosen_departments');
    });
