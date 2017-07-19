<?php

Route::get('dashboard', 'DashboardController@index')->name('admin.dashboard');
Route::post('dashboard/get_teacher_timetable', 'DashboardController@get_teacher_timetable');
Route::post('dashboard/move_timetable_slot', 'DashboardController@move_timetable_slot_teacher')->name('teacher.move_timetable_slot');
Route::post('dashboard/resize_timetable_slot', 'DashboardController@resize_timetable_slot_teacher')->name('teacher.resize_timetable_slot');