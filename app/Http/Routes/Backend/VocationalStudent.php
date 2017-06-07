<?php

Route::group([], function() {
    /* -------------------- Resource -----------------------*/
    Route::resource('vocational_students', 'VocationalStudentController');
    Route::post('vocational_students-data', 'VocationalStudentController@data')->name('admin.vocational_students.data');

});