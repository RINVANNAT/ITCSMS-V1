<?php

Route::group([], function() {

    Route::get('employee-exam-role', 'employeeExamController@getAll');
    Route::get('employee-exam-role/{id}', 'employeeExamController@getExaminationStaffByRole');
    Route::get('employee-get-all-staff', 'employeeExamController@getExaminationStaff');

    Route::get('get-role-by-each-staff', 'employeeExamController@getRoleStaff');

    Route::get('get-role-for-all', 'employeeExamController@getAllRoles');

});
