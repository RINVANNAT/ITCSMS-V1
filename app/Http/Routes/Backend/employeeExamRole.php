<?php

Route::group([], function() {



    Route::get('exams/{id}/get-staff-by-role', 'employeeExamController@getExaminationStaffByRole')->name('admin.exam.get-staff-by-role');
    
    Route::get('exams/{id}/get-all-staff-with-roles', 'employeeExamController@getExaminationStaff')->name('admin.exam.get-all-staff-with-roles');

    Route::get('exams/{id}/get-all-roles', 'employeeExamController@getAllRoles')->name('admin.exam.get-all-roles');

//    ------end of getting selected staff for each role


//    -------get non_selected role of permanent employee

    Route::get('exams/{id}/get-all-departments', 'employeeExamController@getAllDepartements')->name('admin.exam.get-all-departements');

//    -------end

    Route::get('employee-exam-role', 'employeeExamController@getAll');

    Route::get('get-role-by-each-staff', 'employeeExamController@getRoleStaff');

});
