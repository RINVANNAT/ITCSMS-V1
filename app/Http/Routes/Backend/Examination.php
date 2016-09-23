<?php

Route::group([], function() {
    /* ---------- Exam Resources ---------- */
    Route::resource('exams', 'ExamController', ['except' => ['index','create','store','show','edit']]);
    Route::get('exams/{type_id}/index', 'ExamController@index')->name('admin.exam.index');
    Route::get('exams/{type_id}/create', 'ExamController@create')->name('admin.exam.create');
    Route::post('exams/{exam_id}/store', 'ExamController@store')->name('admin.exam.store');
    Route::get('exams/{type_id}/detail/{exam_id}', 'ExamController@show')->name('admin.exam.show');
    Route::get('exams/{type_id}/edit/{exam_id}', 'ExamController@edit')->name('admin.exam.edit');

    Route::post('exams/{id}/data', 'ExamController@data')->name('admin.exam.data');

    Route::get('exams/{id}/get_buildings', 'ExamController@get_buildings')->name('admin.exam.get_buildings');

    Route::get('exams/{id}/get_staffs', 'ExamController@get_staffs')->name('admin.exam.get_staffs');
    Route::get('exams/{id}/count_seat_exam', 'ExamController@count_seat_exam')->name('admin.exam.count_seat_exam');
    Route::get('exams/{id}/count_assigned_seat', 'ExamController@count_assigned_seat')->name('admin.exam.count_assigned_seat');

    Route::post('exams/{id}/save_rooms','ExamController@save_rooms')->name('admin.exam.save_rooms');
    Route::post('exams/{id}/generate_rooms','ExamController@generate_rooms')->name('admin.exam.generate_rooms');
    Route::post('exams/{id}/merge_rooms','ExamController@merge_rooms')->name('admin.exam.merge_rooms');
    Route::post('exams/{id}/split_room','ExamController@split_room')->name('admin.exam.split_room');
    Route::post('exams/{id}/add_room','ExamController@add_room')->name('admin.exam.add_room');
    Route::post('exams/{id}/delete_rooms','ExamController@delete_rooms')->name('admin.exam.delete_rooms');
    Route::post('exams/{id}/edit_seats','ExamController@edit_seats')->name('admin.exam.edit_seats');
    Route::get('exams/{id}/view_room_secret_code', 'ExamController@view_room_secret_code')->name('admin.exam.view_room_secret_code');
    Route::post('exams/{id}/save_room_secret_code', 'ExamController@save_room_secret_code')->name('admin.exam.save_room_secret_code');
    Route::get('exams/{id}/export_room_secret_code', 'ExamController@export_room_secret_code')->name('admin.exam.export_room_secret_code');
    Route::get('exams/{id}/refresh_room', 'ExamController@refresh_room')->name('admin.exam.refresh_room');
    Route::get('exams/{id}/sort_room_capacity', 'ExamController@sort_room_capacity')->name('admin.exam.sort_room_capacity');

    Route::post('exams/{id}/get_courses', 'ExamController@get_courses')->name('admin.exam.get_courses');

    Route::get('exams/{id}/download_attendance_list', 'ExamController@download_attendance_list')->name('admin.exam.download_attendance_list');
    Route::get('exams/{id}/download_candidate_list', 'ExamController@download_candidate_list')->name('admin.exam.download_candidate_list');
    Route::get('exams/{id}/download_room_sticker', 'ExamController@download_room_sticker')->name('admin.exam.download_room_sticker');
    Route::get('exams/{id}/download_correction_sheet', 'ExamController@download_correction_sheet')->name('admin.exam.download_correction_sheet');
    Route::get('exams/{id}/download_candidate_list_by_register_id', 'ExamController@download_candidate_list_by_register_id')->name('admin.exam.download_candidate_list_by_register_id');
    Route::get('exams/{id}/download_candidate_list_dut', 'ExamController@download_candidate_list_dut')->name('admin.exam.download_candidate_list_dut');
    Route::get('exams/{id}/download_candidate_list_ing', 'ExamController@download_candidate_list_ing')->name('admin.exam.download_candidate_list_ing');
    Route::get('exams/{id}/download_registration_statistic', 'ExamController@download_registration_statistic')->name('admin.exam.download_registration_statistic');

    Route::get('exams/{id}/check_missing_candidates', 'ExamController@check_missing_candidates')->name('admin.exam.check_missing_candidates');
    Route::get('exams/{id}/find_missing_candidates', 'ExamController@find_missing_candidates')->name('admin.exam.find_missing_candidates');

//-------------Vannat

    Route::get('exams/{id}/request_input_score_courses', 'ExamController@requestInputScoreCourses')->name('admin.exam.request_input_score_courses');
    Route::get('exams/{id}/request_change_building_room', 'ExamController@getBuildingRequestion')->name('admin.exam.request_change_building_room');
    Route::get('exams/{id}/request_input_score_form', 'ExamController@getRequestInputScoreForm')->name('admin.exam.request_input_score_form');

    Route::get('exams/{id}/ajax_request_room_course_selection', 'ExamController@getAllRooms')->name('admin.exam.ajax_request_room_course_selection');

    Route::post('exams/{id}/insert_exam_score_candidate', 'ExamController@insertScoreForEachCandiate')->name('admin.exam.insert_exam_score_candidate');

    Route::get('exams/{id}/report_exam_score_candidate', 'ExamController@reportErrorCandidateScores')->name('admin.exam.report_exam_score_candidate');


    Route::post('exams/{id}/add_new_correction_score', 'ExamController@addNewCorrectionScore')->name('admin.exam.add_new_correction_score');

    Route::get('exams/{id}/candidate_exam_result_score', 'ExamController@candidateResultExamScores')->name('admin.exam.candidate_exam_result_score');

    Route::post('exams/{id}/candidate_calculatioin_exam_score', 'ExamController@calculateCandidateScores')->name('admin.exam.candidate_calculatioin_exam_score');

    Route::get('/candidate_result_lists', 'ExamController@candidateResultLists')->name('candidate_result_lists');

    Route::get('print/print_candidate_result_lists', 'ExamController@printCandidateResultLists')->name('print_candidate_result_lists');

    Route::get('exams/{id}/print_candidate_error_score', 'ExamController@printCandidateErrorScore')->name('admin.exam.print_candidate_error_socre');

    Route::get('exams/{id}/ajax-check-candidate_socore', 'ExamController@checkCandidateScores')->name('admin.exam.ajax_check_candidate_score');

    Route::get('exams/{id}/candidate_generate_room', 'ExamController@generate_room')->name('admin.exam.candidate.generate_room');


    //-----DUT Examination

    Route::get('exams/{id}/request-form-generate-score', 'ExamController@formGenerateScores')->name('admin.exam.request_form_generate_score');

    Route::get('exams/{id}/candidate-DUT-generate-result', 'ExamController@generateCandidateDUTResult')->name('admin.exam.candidate_dut_generate_result');

    Route::get('exams/{id}/DUT-candidate-result-lists', 'ExamController@getDUTCandidateResultLists')->name('admin.exam.dut_candidate_result_lists');

    Route::get('exams/{id}/DUT-candidate-result-list-type', 'ExamController@getDUTCandidateResultListTypes')->name('admin.exam.dut_candidate_result_list_type');

    Route::get('exams/{id}/print-candidate-dut-result', 'ExamController@printCandidateDUTResult')->name('admin.exam.print_candidate_dut_result');



});
