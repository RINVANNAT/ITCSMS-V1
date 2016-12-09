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
    });

    Route::group([], function() {
        Route::post('course-program-data', 'CourseController@data')->name('admin.course.course_program.data');
        Route::get('course-program-request-import', 'CourseController@request_import')->name('admin.course.course_program.request_import');
        Route::post('course-program-import', 'CourseController@import')->name('admin.course.course_program.import');
        Route::resource('course_program', 'CourseController');
        Route::get('course-request-import-config', 'CourseController@request_import_config')->name('admin.course.course_program.request_import_config');
        Route::post('course-import-config', 'CourseController@import_config')->name('admin.course.course_program.import_config');

    });

    Route::get('course-annual/course-assignment', 'CourseAnnualController@courseAssignment')->name('admin.course.course_assignment');

    Route::get('course-annual/get-departments', 'CourseAnnualController@getAllDepartments')->name('admin.course.get_department');

    Route::get('course-annual/get-teacher-by-department', 'CourseAnnualController@getAllTeacherByDepartmentId')->name('admin.course.get_teacher_by_department');

    Route::get('course-annual/get-course-by-teacher', 'CourseAnnualController@getSeletedCourseByTeacherID')->name('admin.course.get_course_by_teacher');

    Route::get('course-annual/get-course-by-department', 'CourseAnnualController@getAllCourseByDepartment')->name('admin.course.get_course_by_department');

    Route::delete('course-annual/remove-course-from-teacher', 'CourseAnnualController@removeCourse')->name('admin.course.remove_course_from_teacher');

    Route::post('course-annual/assign-course-teacher', 'CourseAnnualController@assignCourse')->name('admin.course.assign_course_teacher');


    Route::get('course-annual/edit_course_annual', 'CourseAnnualController@formEditCourseAnnual')->name('admin.course.form_edit_course_annual');
    Route::put('course-annual/{id}/edit_course_annual', 'CourseAnnualController@editCourseAnnual')->name('admin.course.edit_course_annual');

    Route::post('course-annual/add_course_annual', 'CourseAnnualController@douplicateCourseAnnual')->name('admin.course.add_course_annual');

    Route::delete('course-annual/delete_course_annual', 'CourseAnnualController@deleteCourseAnnual')->name('admin.course.delete_course_annual');

    Route::get('course-annual/generate-course-annual', 'CourseAnnualController@generateCourseAnnual')->name('admin.course.generate_course_annual');


    Route::get('course-annual/{id}/form-input-score-course-annual', 'CourseAnnualController@getFormScoreByCourse')->name('admin.course.form_input_score_course_annual');


    Route::post('course-annual/save-score-course-annual', 'CourseAnnualController@getsaveScoreByCourse')->name('admin.course.save_score_course_annual');
    Route::get('course-annual/get-data-course-annual-score', 'CourseAnnualController@getCourseAnnualScoreByAjax')->name('admin.course.get_data_course_annual_score');

    Route::post('course-annual/add-new-column-courseannual', 'CourseAnnualController@insertPercentageNameNPercentage')->name('admin.course.add_new_column_courseannual');


    Route::post('course-annual/save-number-absence', 'CourseAnnualController@storeNumberAbsence')->name('admin.course.save_number_absence');







});
