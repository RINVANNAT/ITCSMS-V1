<?php

Route::group([], function() {
    Route::resource('exams', 'ExamController', ['except' => ['index','create','store']]);
    Route::get('exams/{id}/index', 'ExamController@index')->name('admin.exam.index');
    Route::get('exams/{id}/create', 'ExamController@create')->name('admin.exam.create');
    Route::post('exams/{id}/store', 'ExamController@store')->name('admin.exam.store');

    Route::get('exams/{id}/data', 'ExamController@data')->name('admin.exam.data');

    Route::get('exams/{id}/get_buildings', 'ExamController@get_buildings')->name('admin.exam.get_buildings');

    Route::get('exams/{id}/get_staffs', 'ExamController@get_staffs')->name('admin.exam.get_staffs');
    Route::get('exams/{id}/count_seat_exam', 'ExamController@count_seat_exam')->name('admin.exam.count_seat_exam');

    Route::get('exams/{id}/get_rooms', 'ExamController@get_rooms')->name('admin.exam.get_rooms');
    Route::post('exams/{id}/save_rooms','ExamController@save_rooms')->name('admin.exam.save_rooms');
    Route::post('exams/{id}/generate_rooms','ExamController@generate_rooms')->name('admin.exam.generate_rooms');
    Route::post('exams/{id}/merge_rooms','ExamController@merge_rooms')->name('admin.exam.merge_rooms');
    Route::post('exams/{id}/split_rooms','ExamController@split_rooms')->name('admin.exam.split_rooms');
    Route::post('exams/{id}/delete_rooms','ExamController@delete_rooms')->name('admin.exam.delete_rooms');
    Route::get('exams/{id}/view_room_secret_code', 'ExamController@view_room_secret_code')->name('admin.exam.view_room_secret_code');
    Route::post('exams/{id}/save_room_secret_code', 'ExamController@save_room_secret_code')->name('admin.exam.save_room_secret_code');

    Route::post('exams/{id}/get_courses', 'ExamController@get_courses')->name('admin.exam.get_courses');

    Route::get('exams/{id}/download_attendance_list', 'ExamController@download_attendance_list')->name('admin.exam.download_attendance_list');
    Route::get('exams/{id}/download_candidate_list', 'ExamController@download_candidate_list')->name('admin.exam.download_candidate_list');
    Route::get('exams/{id}/download_room_sticker', 'ExamController@download_room_sticker')->name('admin.exam.download_room_sticker');
    Route::get('exams/{id}/download_correction_sheet', 'ExamController@download_correction_sheet')->name('admin.exam.download_correction_sheet');
    Route::get('exams/{id}/download_candidate_list_by_register_id', 'ExamController@download_candidate_list_by_register_id')->name('admin.exam.download_candidate_list_by_register_id');


//-------------Vannat

    Route::get('exams/{id}/request_input_score_courses', 'ExamController@requestInputScoreCourses')->name('admin.exam.request_input_score_courses');
    Route::get('exams/{id}/request_change_building_room', 'ExamController@getBuildingRequestion')->name('admin.exam.request_change_building_room');
    Route::get('exams/{id}/request_input_score_form', 'ExamController@getRequestInputScoreForm')->name('admin.exam.request_input_score_form');

    Route::get('exams/{id}/ajax_request_room_course_selection', 'ExamController@requestRoomCourseSelection')->name('admin.exam.ajax_request_room_course_selection');

    Route::post('exams/{id}/insert_exam_score_candidate', 'ExamController@insertScoreForEachCandiate')->name('admin.exam.insert_exam_score_candidate');

    Route::get('exams/{id}/report_exam_score_candidate', 'ExamController@reportErrorCandidateScores')->name('admin.exam.report_exam_score_candidate');


    Route::post('exams/{id}/add_new_correction_score', 'ExamController@addNewCorrectionScore')->name('admin.exam.add_new_correction_score');

    Route::get('exams/{id}/candidate_exam_result_score', 'ExamController@candidateResultExamScores')->name('admin.exam.candidate_exam_result_score');

    Route::post('exams/{id}/candidate_calculatioin_exam_score', 'ExamController@calculateCandidateScores')->name('admin.exam.candidate_calculatioin_exam_score');

    Route::get('/candidate_result_lists', 'ExamController@candidateResultLists')->name('candidate_result_lists');

    Route::get('print/print_candidate_result_lists', 'ExamController@printCandidateResultLists')->name('print_candidate_result_lists');




    Route::get('exams/{id}/candidate_generate_room', 'ExamController@generate_room')->name('admin.exam.candidate.generate_room');


});
