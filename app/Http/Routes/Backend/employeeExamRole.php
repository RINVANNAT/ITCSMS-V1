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


    Route::get('exams/{id}/assign-room-staff-lists', 'employeeExamController@viewRoleStaffLists')->name('admin.exam.assign_room_staff_lists');


    Route::get('exams/{id}/print-role-staff-lists', 'employeeExamController@printViewRoleStaffLists')->name('admin.exam.print_role_staff_lists');


    Route::get('exams/{id}/get-staff-by-role-course', 'employeeExamController@getStaffByRoleCourse')->name('admin.exam.get_staff_by_role_course');

    Route::get('exams/{id}/get-room-list-by-role', 'employeeExamController@getRoomByRole')->name('admin.exam.get_room_list_by_role');

    Route::put('exams/{id}/update-staff-with-room', 'employeeExamController@updateStaffRoom')->name('admin.exam.update_staff_with_room');

    Route::get('exams/{id}/get-popup-view-yes-no', 'employeeExamController@getPopUpMessage')->name('admin.exam.get_popup_view_yes_no');

    Route::delete('exams/{id}/delete-room-from-staff', 'employeeExamController@deleteRoomFromStaff')->name('admin.exam.delete_room_from_staff');

    Route::get('exams/{id}/staff-role-room-examination-export', 'employeeExamController@staffRoleRoomExport')->name('admin.exam.staff_role_room_examination_export');






//----------testing
    Route::get('exams/{id}/get-roles', 'employeeExamController@getRoles')->name('admin.exam.get-roles');

    Route::get('employee-exam-role', 'employeeExamController@getAll');

    Route::get('exams/{id}/get-staff-without-role', 'employeeExamController@getStaffWithoutRole');

    Route::get('get-role-by-each-staff', 'employeeExamController@getRoleStaff');

});
