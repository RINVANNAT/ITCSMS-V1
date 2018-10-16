<?php

Route::group(['namespace' => 'Schedule', 'prefix' => 'schedule', 'middleware' => ['access.timetable']], function () {

    /** Http Requesting. */
    Route::get('/timetables', 'TimetableController@index')->name('admin.schedule.timetables.index');
    Route::get('/timetables/create/{academic?}/{department?}/{degree?}/{option?}/{grade?}/{semester?}/{group?}/{week?}', 'TimetableController@create')
        ->name('admin.schedule.timetables.create');
    Route::get('/timetables/show/{timetable}', 'TimetableController@show')->name('admin.schedule.timetables.show');
    Route::post('/timetables/delete', 'TimetableController@delete')->name('admin.schedule.timetables.delete');
    Route::post('/timetables/publish', 'TimetableController@publish')->name('admin.schedule.timetables.publish');
    Route::post('/timetables/get_timetables', 'TimetableController@get_timetables')->name('admin.schedule.timetables.get_timetables');

    /** Ajax Requesting. */
    Route::post('timetables/filter', 'TimetableController@filter')->name('admin.schedule.timetables.filter');
    Route::post('timetables/store', 'TimetableController@store')->name('admin.schedule.timetables.store');
    Route::post('timetables/get_timetable_slots', 'TimetableController@get_timetable_slots')->name('get_timetable_slots');
    Route::post('timetables/move_timetable_slot', 'TimetableController@move_timetable_slot')->name('move_timetable_slot');
    Route::post('timetables/resize_timetable_slot', 'TimetableController@resize_timetable_slot')->name('resize_timetable_slot');
    Route::post('timetables/insert_room_into_timetable_slot', 'TimetableController@insert_room_into_timetable_slot')->name('insert_room_into_timetable_slot');
    Route::post('timetables/remove_room_from_timetable_slot', 'TimetableController@remove_room')->name('remove_room');
    Route::post('timetables/get_suggest_room', 'TimetableController@get_suggest_room')->name('get_suggest_room');
    Route::post('timetables/get_conflict_info', 'TimetableController@get_conflict_info')->name('get_conflict_info');
    Route::post('timetables/merge_timetable_slot', 'TimetableController@merge_timetable_slot')->name('merge_timetable_slot');
    Route::post('timetables/remove_timetable_slot', 'TimetableController@remove_timetable_slot')->name('remove_timetable_slot');
    Route::post('timetables/search_course_program', 'TimetableController@search_course_program')->name('search_course_program');
    Route::post('timetables/assign_lecturer_to_course_program', 'TimetableController@assign_lecturer_to_course_program')->name('assign_lecturer_to_course_program');
    Route::post('timetables/assign_lecturer_to_timetable_slot', 'TimetableController@assign_lecturer_to_timetable_slot')->name('assign_lecturer_to_timetable_slot');
    Route::post('timetables/reset', 'TimetableController@reset')->name('reset'); // rest by filter option

    /** timetable assignment */
    Route::post('timetables/export_course_program', 'TimetableController@export_course_program')->name('export_course_program');
    Route::post('timetables/assign_turn_create_timetable', 'TimetableController@assign_turn_create_timetable')->name('assign_turn_create_timetable');
    Route::post('timetables/get_timetable_assignment', 'TimetableController@get_timetable_assignment')->name('get_timetable_assignment');
    Route::post('timetables/assign/delete', 'TimetableController@assign_delete')->name('assign.delete');
    Route::post('timetables/timetables/assign/update', 'TimetableController@assign_update')->name('assign.update');
    Route::post('timetables/timetables/update/assign', 'TimetableController@update_assign_timetable')->name('update_assign_timetable');

    /** Options controls. */
    Route::post('timetables/get_weeks', 'TimetableController@get_weeks')->name('admin.schedule.timetables.get_weeks');
    Route::post('timetables/get_options', 'TimetableController@get_options')->name('admin.schedule.timetables.get_options');
    Route::post('timetables/get_groups', 'TimetableController@get_groups')->name('admin.schedule.timetables.get_groups');
    Route::post('timetables/get_grades', 'TimetableController@get_grades')->name('admin.schedule.timetables.get_grades');
    Route::post('timetables/get_course_programs', 'TimetableController@get_course_programs')->name('admin.schedule.timetables.get_course_programs');
    Route::post('timetables/get_rooms', 'TimetableController@get_rooms')->name('admin.schedule.timetables.get_rooms');
    Route::post('timetables/get_employees', 'TimetableController@get_employees')->name('admin.schedule.timetables.get_employees');
    Route::post('timetables/search_rooms', 'TimetableController@search_rooms')->name('admin.schedule.timetables.search_rooms');
    Route::post('timetables/filter-courses-sessions', 'TimetableController@filterCoursesSessions')->name('admin.schedule.timetables.filterCoursesSessions');
    Route::post('timetables/search_timetable_groups', 'TimetableController@searchTimetableGroup')->name('admin.schedule.timetables.searchTimetableGroup');
    Route::post('timetables/store_new_group', 'TimetableController@storeTimetableGroup')->name('admin.schedule.timetables.store_new_group');

    /** Clone route. */
    Route::post('timetables/clone', 'TimetableController@cloneTimetable')->name('admin.schedule.timetables.clone');
    Route::post('timetables/clone/weeks', 'TimetableController@get_all_weeks')->name('clone.weeks');
    Route::post('timetables/clone_timetable_form', 'TimetableController@clone_timetable_form')->name('clone_timetable_form');
    Route::post('timetables/clone/clone_timetable', 'TimetableController@clone_timetable')->name('clone_timetable');

    /** Print */
    Route::get('timetables/print/{id}', 'TimetableController@print_timetable')->name('timetables.print');
    Route::post('timetables/template-print', 'TimetableController@get_template_print')->name('timetables.template_print');

    /** Export Excel */
    Route::get('timetables/export/{id}', 'TimetableController@export_timetable')->name('timetables.export');
    Route::post('timetables/export/file', 'TimetableController@export_file')->name('timetables.export_file');

    /** Check available room */
    Route::group(['prefix' => 'timetables/check-available-room'], function () {
        Route::get('', 'CheckAvailableRoomController@index')->name('check-available-room.index');
        Route::get('/get_rooms', 'CheckAvailableRoomController@get_rooms')->name('check-available-room.get_rooms');
        Route::post('/get-unavailable-room-info', 'CheckAvailableRoomController@get_unavailable_room_info')->name('check-available-room.get_unavailable_room_info');
    });

    /** Assign groups */
    Route::post('group/assign-group', 'TimetableController@assignGroup');
});