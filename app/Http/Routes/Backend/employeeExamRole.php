<?php

Route::group([], function() {



    Route::get('exams/{id}/get-staff-by-role', 'employeeExamController@getExaminationStaffByRole')->name('admin.exam.get-staff-by-role');
    
    Route::get('exams/{id}/get-all-staff-with-roles', 'employeeExamController@getExaminationStaff')->name('admin.exam.get-all-staff-with-roles');

    Route::get('exams/{id}/get-all-roles', 'employeeExamController@getAllRoles')->name('admin.exam.get-all-roles');

//    ------end of getting selected staff for each role


//    -------get non_selected role of permanent employee

    Route::get('exams/{id}/get-all-departments', 'employeeExamController@getAllDepartments')->name('admin.exam.get-all-departements');
    Route::get('exams/{id}/get-all-positions', 'employeeExamController@getPositionByDepartments')->name('admin.exam.get-all-positions');
    Route::get('exams/{id}/get-all-staff-by-position', 'employeeExamController@getStaffWithoutRoleByPositions')->name('admin.exam.get-all-staffs-by-position');

//    -------end

    Route::post('exams/{id}/save-staff-role', 'employeeExamController@saveStaffRoles')->name('admin.exam.gsave-staff-role');

    Route::post('exams/{id}/save-role-node/new-role', 'employeeExamController@addNewRole')->name('admin.exam.save-new-role');

    Route::delete('exams/{id}/delete-role-node/delete-role', 'employeeExamController@deleteRoleNode')->name('admin.exam.delete-role-node');

    Route::put('exams/{id}/update-role-node/update-role', 'employeeExamController@changeRoleStaffs')->name('admin.exam.update-role-node');

    Route::get('exams/{id}/temp-employee-request-import', 'employeeExamController@requestImportTempEmployees')->name('admin.exam.temp_employee.request_import');

    Route::post('exams/{id}/temp-employee-import', 'employeeExamController@importTempEmployees')->name('admin.exam.temp_employee.import');

    Route::get('exams/{id}/temp-employee-export', 'employeeExamController@exportTempEmployees')->name('admin.exam.temp_employee.export');




//----------testing
    Route::get('exams/{id}/get-roles', 'employeeExamController@getRoles')->name('admin.exam.get-roles');

    Route::get('employee-exam-role', 'employeeExamController@getAll');

    Route::get('exams/{id}/get-staff-without-role', 'employeeExamController@getStaffWithoutRole');

    Route::get('get-role-by-each-staff', 'employeeExamController@getRoleStaff');

});
