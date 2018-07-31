<?php

    Route::group([], function() {
        /* -------------------- Resource -----------------------*/
        Route::resource('studentAnnuals', 'StudentAnnualController');
        Route::post('student-data', 'StudentAnnualController@data')->name('admin.student.data');

        /* -------------------- Actions -----------------------*/
        Route::get('student-generate-group', 'StudentAnnualController@generate_group')->name('admin.student.generate_group');
        Route::get('student-generate-id-card', 'StudentAnnualController@generate_id_card')->name('admin.student.generate_id_card');
        Route::get('student-request-print-id-card', 'StudentAnnualController@request_print_id_card')->name('admin.student.request_print_id_card');
        Route::get("student-request-print-examination-attendance-list",'StudentAnnualController@request_print_examination_attendance_list')->name('admin.student.request_print_examination_attendance_list');
        Route::get("student-print-examination-attendance-list",'StudentAnnualController@print_examination_attendance_list')->name('admin.student.print_examination_attendance_list');
        Route::get('student-search', 'StudentAnnualController@search')->name('admin.student.search');

        Route::get('student-print-id-card', 'StudentAnnualController@print_id_card')->name('admin.student.print_id_card');
        Route::get('student-print-inform-success', 'StudentAnnualController@print_inform_success')->name('admin.student.print_inform_success');

        /* -------------------- IMPORT -----------------------*/
        Route::get('student-request-import', 'StudentAnnualController@request_import')->name('admin.student.request_import');
        Route::post('student-import', 'StudentAnnualController@import')->name('admin.student.import');

        /* -------------------- EXPORT -----------------------*/
        // Request export form
        Route::get('student/request-export-fields', 'StudentAnnualController@request_export_fields')->name('admin.student.request_export_fields');

        Route::get('student/request-export-custom', 'StudentAnnualController@request_export_list_custom')->name('admin.student.request_export_custom');

        Route::post('student/export', 'StudentAnnualController@export')->name('admin.student.export');
        //Route::post('student/export-custom', 'StudentAnnualController@export_list_custom')->name('admin.student.export_custom');


        Route::get('student/{id}/reporting', 'StudentAnnualController@reporting')->name('admin.student.reporting');

        Route::get('student/{id}/reporting/export', 'StudentAnnualController@report')->name('admin.student.reporting.export');
        Route::get('student/{id}/reporting/print', 'StudentAnnualController@print_report')->name('admin.student.reporting.print');
        Route::get('student/{id}/reporting/preview', 'StudentAnnualController@preview_report')->name('admin.student.reporting.preview');

        Route::get('student/popup_index', 'StudentAnnualController@popup_index')->name('admin.student.popup_index');


        Route::get('student/number-student-annual', 'StudentAnnualController@getNumberStudent')->name('admin.student.number_student_annual');


        /* ------------generate id card----------- */

        Route::post('student/{id}/generate_student_id_card', 'StudentAnnualController@generate_id_card')->name('admin.student.generate_student_id_card');

        /*--generate group---*/


        Route::get('student/form_generate_student_group', 'StudentAnnualController@formGenerateGroup')->name('admin.student.form_generate_student_group');
        Route::get('student/generate_student_group', 'StudentAnnualController@generatGroup')->name('admin.student.generate_student_group');

        Route::get('student/export-generated-group', 'StudentAnnualController@generate_group')->name('student.annual.export_generated_group');

        Route::get('/student-annual/load-course', 'StudentAnnualController@loadCourse')->name('student.annual.load_course');


        /*--end generate group---*/

        Route::get('student/annual/export-lists-format', 'StudentAnnualController@exportFormatLists')->name('admin.student_annual.export_format_lists');
        Route::post('student/annual/import', 'StudentAnnualController@importStudentGroup')->name('student_annual.import');
    });

