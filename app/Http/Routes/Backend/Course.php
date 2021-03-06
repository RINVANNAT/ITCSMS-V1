<?php


Route::group([
    'prefix'     => 'course',
    'namespace' => 'Course'
], function() {



    Route::group([], function() {
        Route::any('update_score_per/{id}/', 'CourseAnnualController@update_score_per')->name('admin.course.course_annual.update_score_per');
        Route::resource('course_annual', 'CourseAnnualController');
        Route::post('course-annual-data', 'CourseAnnualController@data')->name('admin.course.course_annual.data');
        Route::get('course-request-import', 'CourseAnnualController@request_import')->name('admin.course.course_annual.request_import');
        Route::post('course-import', 'CourseAnnualController@import')->name('admin.course.course_annual.import');
        Route::get('course-disable-enable-scoring/{id}', 'CourseAnnualController@toggle_scoring')->name('admin.course.course_annual.toggle_scoring');
        Route::post('course-mass-disable-scoring', 'CourseAnnualController@disable_scoring')->name('admin.course.course_annual.disable_scoring');
        Route::post('course-mass-enable-scoring', 'CourseAnnualController@enable_scoring')->name('admin.course.course_annual.enable_scoring');
        Route::post('course-mass-only-retake-scoring', 'CourseAnnualController@only_retake_scoring')->name('admin.course.course_annual.only_retake_scoring');
        Route::post('/course-annual/load-reference-course', 'CourseAnnualController@loadReferenceCourse')->name('course_annual.load_reference_course');
    });

    Route::group([], function() {
        Route::post('course-program-data', 'CourseController@data')->name('admin.course.course_program.data');
        Route::get('course-program/activate/{id}', 'CourseController@activate')->name('admin.course.course_program.activate');
        Route::get('course-program-request-import', 'CourseController@request_import')->name('admin.course.course_program.request_import');
        Route::post('course-program-import', 'CourseController@import')->name('admin.course.course_program.import');
        Route::resource('course_program', 'CourseController');
        Route::get('course-request-import-config', 'CourseController@request_import_config')->name('admin.course.course_program.request_import_config');
        Route::post('course-import-config', 'CourseController@import_config')->name('admin.course.course_program.import_config');

        Route::get('/course-program/dept-has-option', 'CourseController@getDeptOption')->name('course_program.dept_option');

        Route::get('/course-program/export-list', 'CourseController@exportList')->name('course_program.export_list');

    });

    Route::group([], function() {
        Route::post('course-session-data', 'CourseSessionController@data')->name('admin.course.course_session.data');
        Route::resource('course_session', 'CourseSessionController');
    });


//    -------- --------------- course session assignment -----

    Route::get('course-annual/course-assignment', 'CourseAnnualController@courseAssignment')->name('admin.course.course_assignment');

    Route::get('course-annual/get-departments', 'CourseAnnualController@getAllDepartments')->name('admin.course.get_department');

    Route::get('course-annual/get-teacher-by-department', 'CourseAnnualController@getAllTeacherByDepartmentId')->name('admin.course.get_teacher_by_department');

    Route::get('course-annual/get-course-by-teacher', 'CourseAnnualController@getSeletedCourseByTeacherID')->name('admin.course.get_course_by_teacher');

    Route::get('course-annual/get-course-by-department', 'CourseAnnualController@getAllCourseByDepartment')->name('admin.course.get_course_by_department');

    Route::delete('course-annual/remove-course-from-teacher', 'CourseAnnualController@removeCourse')->name('admin.course.remove_course_from_teacher');

    Route::post('course-annual/assign-course-teacher', 'CourseAnnualController@assignCourse')->name('admin.course.assign_course_teacher');


    Route::get('course-annual/edit_course_annual', 'CourseAnnualController@formEditCourseAnnual')->name('admin.course.form_edit_course_annual');

    Route::get('course-annual/get-student-group', 'CourseAnnualController@studentGroupByDept')->name('course_annual.student_group');

    Route::put('course-annual/{id}/edit_course_annual', 'CourseAnnualController@updateCourseSession')->name('admin.course.edit_course_annual'); /*---course session instead --*/

    Route::post('course-annual/add_course_annual', 'CourseAnnualController@douplicateCourseAnnual')->name('admin.course.add_course_annual');

    Route::delete('course-annual/delete_course_annual', 'CourseAnnualController@deleteCourseSession')->name('admin.course.delete_course_annual');/*--delete course session not course annual ---*/

    Route::get('course-annual/generate-course-annual', 'CourseAnnualController@generateCourseAnnual')->name('admin.course.generate_course_annual');

    Route::get('course-annual/get-student-group-filtering', 'CourseAnnualController@filteringStudentGroup')->name('course_annual.get_group_filtering');

    Route::get('course-annual/get-student-group-selection', 'CourseAnnualController@getStudentGroupSelection')->name('course_annual.get_student_group_selection');



//    ------input score by each course annual ----------------

    Route::get('course-annual/{id}/form-input-score-course-annual', 'CourseAnnualController@getFormScoreByCourse')->name('admin.course.form_input_score_course_annual');
    Route::post('course-annual/save-score-course-annual', 'CourseAnnualController@saveScoreByCourseAnnual')->name('admin.course.save_score_course_annual');
    Route::get('course-annual/get-data-course-annual-score', 'CourseAnnualController@getCourseAnnualScoreByAjax')->name('admin.course.get_data_course_annual_score');
    Route::post('course-annual/add-new-column-courseannual', 'CourseAnnualController@insertPercentageNameNPercentage')->name('admin.course.add_new_column_courseannual');
    Route::post('course-annual/save-number-absence', 'CourseAnnualController@storeNumberAbsence')->name('admin.course.save_number_absence');
    Route::delete('course-annual/delete-score', 'CourseAnnualController@deleteScoreFromScorePercentage')->name('admin.course.delete-score');
    Route::post('course-annual/{id}/get-average-score', 'CourseAnnualController@calculateAverageByCourseAnnual')->name('admin.course.get_average_score');

    Route::post('course_annual/ajax-switch-course-annaul', 'CourseAnnualController@switchCourseAnnual')->name('course_annual.ajax_switch_course_annual');

    Route::post('course_annual/ajax-save-each-cell-notation', 'CourseAnnualController@saveEachCellNotationCourseAnnual')->name('course_annual.save_each_cell_notation');
    Route::get('/export-course-score-annual', 'CourseAnnualController@exportCourseScore')->name('course_annual.export_course_score_annual');
    Route::get('/course-annual-import-score', 'CourseAnnualController@formImportScore')->name('course_annual.form_import_score');
    Route::post('/course-annual/{id}/import-score', 'CourseAnnualController@importScore')->name('course_annual.import_file');
    Route::get('/course-annual/get-other-dept', 'CourseAnnualController@getDepts')->name('course_annual.get_other_dept');
    Route::get('/course-annual/get-other-lecturer', 'CourseAnnualController@getOtherLecturer')->name('course_annual.get_other_lecturer');
    Route::get('/list-group-by-course-annual-id', 'CourseAnnualController@getGroupByCourseAnnual')->name('course.list_group_by_course_annual_id');
    Route::get('/course-annual/is-allow-scoring', 'CourseAnnualController@isAllowScoring')->name('course_annual.is_allow_scoring');
    Route::get('/course-annual/clone-score', 'CourseAnnualController@cloneScore')->name('course_annual.clone_score');
    Route::get('/course-annual/popup-clone-score-panel', 'CourseAnnualController@cloneScorePanel')->name('course_annual.popup_clone_score_panel');

    Route::post('/course-annual/is-allow-cloning-course', 'CourseAnnualController@isAllowCloningCourse')->name('course_annual.is_allow_cloning_course');





    Route::get('/course-annual/dept-option', 'CourseAnnualController@getDeptOption')->name('course_annual.dept_option');

//    --------------evaluation score for course annually -------

    Route::get('course-annual/get-form-all-course-annual-score', 'CourseAnnualController@formScoreAllCourseAnnual')->name('admin.course.get_form_evaluation_score');
    Route::get('course-annual/get-all-data-course-annual-score', 'CourseAnnualController@allHandsontableData')->name('admin.course.get_all_handsontable_data');
    Route::get('course-annual/filter-course-annual-scores', 'CourseAnnualController@allHandsontableData')->name('admin.course.filter_course_annual_scores');
    Route::get('course-annual/print_total_score', 'CourseAnnualController@print_total_score')->name('admin.course.print_total_score');
    Route::get('course-annual/-get-form-all-score-properties', 'CourseAnnualController@formAllScoreSelection')->name('course_annual.form_all_score_properties');
    Route::post('course_annual/save-each-cell-observation', 'CourseAnnualController@saveEachObservation')->name('course_annual.save_each_cell_observation');
    Route::post('course_annual/save-each-cell-remark', 'CourseAnnualController@saveEachRemark')->name('course_annual.save_each_cell_remark');
    Route::post('course_annual/save-each-cell-general-remark', 'CourseAnnualController@saveEachGeneralRemark')->name('course_annual.save_each_cell_general_remark');
    Route::get('course-annual/export-view-total-score', 'CourseAnnualController@exportTotalScore')->name('course_annual.export_view_total_score');
    Route::get('course-annual/student-redouble-liste}', 'CourseAnnualController@studentRedoubleListe')->name('course_annual.student_redouble_exam');
    Route::post('course-annual/export-student-redouble-list}', 'CourseAnnualController@exportStudentRedoubleList')->name('course_annual.export_student_re_exam');
    Route::post('course-annual/save-student-resit}', 'CourseAnnualController@saveStudentResit')->name('save_student_resit_exam');
    Route::post('course-annual/export-supplementary-subject-list', 'CourseAnnualController@exportSupplementarySubjects')->name('course_annual.export_supplementary_subject');
    Route::post('/student/update-status', 'CourseAnnualController@updateStudentStatus')->name('student.update_status');
    Route::get('course-annual/empty-view', 'CourseAnnualController@emptyView')->name('empty_view');
    Route::get('/course-annual/student-final-result', 'CourseAnnualController@getStudentFinalResult')->name('course_annual.student_annual_final_result');
    Route::get('/student/dismiss', 'CourseAnnualController@getStudentDismiss')->name('student.dismiss');
    Route::get('/student/redouble', 'CourseAnnualController@getStudentRedouble')->name('student.redouble');
    Route::get('/student/resit-subject-lists', 'CourseAnnualController@resitSubjectLists')->name('student.resit_subject_lists');
    Route::post('/course/store-resit-score', 'CourseAnnualController@storeResitScore')->name('admin.score.store_resit');

    Route::get('/course/{id}/import-score-success', 'CourseAnnualController@successImportedScore')->name('admin.score.success_imported');

    Route::get('/directed-course-annual', 'CourseAnnualController@directedCourseAnnuals')->name('coursen_annual.get_directed_course');
    Route::post('/course-annual/publish-score', 'CourseAnnualController@publishScore')->name('coursen_annual.publish_score');


    /*----competency-url---*/

    Route::get('/course/get-form-proficency', 'CourseAnnualController@proficencyFormScore')->name('course.proficency_form_score');
    Route::post('/course-annual/proficency/score-data', 'CourseAnnualController@proficencyData')->name('course_annual.proficency_score_data');
    Route::get('/course-annual/header-competency-score', 'CourseAnnualController@competencyHeader')->name('course_annual.competency_header');
    Route::post('/course-annual/save-competency-score','CourseAnnualController@storeCompetencyScore' )->name('admin.course.course_annual_store_competency_score');
    Route::post('/course-annual/competency-score/calculate', 'CourseAnnualController@calculate' )->name('course_annual.competency_score.calculate');
    Route::get('/course-annual/competency-score/{id}/export', 'CourseAnnualController@export' )->name('course_annual.competency_score.export');
    Route::post('/course-annual/competency-score/{id}/import-score', 'CourseAnnualController@importCompetencyScore' )->name('course_annual.competency_score.import');
    Route::get('/course-annual/competency/request-print-certificate','CourseAnnualController@requestPrintCertificate' )->name('course_annual.competency.request_print_certificate');
    Route::post('/course-annual/competency/data-for-request-print-certificate','CourseAnnualController@getDataForRequestPrintCertificate' )->name('course_annual.competency.data_for_request_print_certificate');
    Route::get('/course-annual/competency/print-certificate','CourseAnnualController@printCertificate' )->name('course_annual.competency.print_certificate');
    Route::get('/course-annual/competency/mark-printed-certificate','CourseAnnualController@markPrintedCertificate' )->name('course_annual.competency.mark_printed_certificate');

    /*---end competency url ---*/


    /*---generate student next academic ---*/

    Route::post('/evaluation/student/generate-next-academic', 'CourseAnnualController@generateStudentNextAcademic' )->name('evaluation.student.generate_next_academic');

    /*---end generate student next academic ---*/

    Route::get('request_print_transcript', 'CourseAnnualController@request_print_transcript')->name('admin.student.request_print_transcript');
    Route::post('request_print_transcript_data', 'CourseAnnualController@request_print_transcript_data')->name('admin.student.request_print_transcript_data');
    Route::get('print_transcript', 'CourseAnnualController@print_transcript')->name('admin.student.print_transcript');
    Route::get('mark_printed_transcript', 'CourseAnnualController@mark_printed_transcript')->name('admin.student.mark_printed_transcript');
    Route::get('print_student_list_transcript', 'CourseAnnualController@print_student_list_transcript')->name('admin.student.print_student_list_transcript');

    /*---Print attestation route ---*/

    Route::get('request_print_attestation', 'CourseAnnualController@request_print_attestation')->name('admin.student.request_print_attestation');
    Route::get('print_attestation', 'CourseAnnualController@print_attestation')->name('admin.student.print_attestation');
    Route::post('request_print_attestation_data', 'CourseAnnualController@request_print_attestation_data')->name('admin.student.request_print_attestation_data');

    /*---End of print attestation route ---*/

    /*---Print average final year route ---*/

    Route::get('print_average_final_year/{type}', 'CourseAnnualController@print_average_final_year')->name('admin.student.print_average_final_year');

    /*---End of print average final year route ---*/


    /*
     * ---student statistic----
     * */

    Route::get('/student/statistic-radie', 'CourseAnnualController@student_statistic_radie')->name('student.statistic_radie');

    // axios request
    Route::post('get-key-issued-data', 'CourseAnnualController@getKeyIssuedData');
    Route::post('get-key-issued-data/store', 'CourseAnnualController@getKeyIssuedDateStore');

    Route::post('attestation/get-key-issued-date-attestation', 'CourseAnnualController@getKeyIssuedAttestationDate');
    Route::post('attestation/get-key-issued-date-attestation/store', 'CourseAnnualController@getKeyIssuedDateAttestationStore');
});
