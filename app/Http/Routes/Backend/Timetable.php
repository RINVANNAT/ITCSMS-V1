<?php

Route::group(['namespace' => 'Schedule', 'prefix' => 'schedule'], function () {

    /** Http Requesting. */
    Route::get('/timetables', 'TimetableController@index')->name('admin.schedule.timetables.index');
    Route::get('/timetables/create', 'TimetableController@create')->name('admin.schedule.timetables.create');
    Route::get('/timetables/show', 'TimetableController@show')->name('admin.schedule.timetables.show');
    Route::get('/timetables/get_timetables', 'TimetableController@get_timetables')->name('admin.schedule.timetables.get_timetables');

    /** Ajax Requesting. */
    Route::post('timetables/filter', 'TimetableController@filter')->name('admin.schedule.timetables.filter');
    Route::post('timetables/store', 'TimetableController@store')->name('admin.schedule.timetables.store');
    Route::post('timetables/get_timetable_slots', 'TimetableController@get_timetable_slots')->name('get_timetable_slots');
    Route::post('timetables/move_timetable_slot', 'TimetableController@move_timetable_slot')->name('move_timetable_slot');
    Route::post('timetables/resize_timetable_slot', 'TimetableController@resize_timetable_slot')->name('resize_timetable_slot');
    Route::post('timetables/insert_room_into_timetable_slot', 'TimetableController@insert_room_into_timetable_slot')->name('insert_room_into_timetable_slot');
    Route::post('timetables/remove_room_from_timetable_slot', 'TimetableController@remove_room')->name('remove_room');

    /** Options controls. */
    Route::post('timetables/get_weeks', 'TimetableController@get_weeks')->name('admin.schedule.timetables.get_weeks');
    Route::post('timetables/get_options', 'TimetableController@get_options')->name('admin.schedule.timetables.get_options');
    Route::post('timetables/get_groups', 'TimetableController@get_groups')->name('admin.schedule.timetables.get_groups');
    Route::post('timetables/get_course_sessions', 'TimetableController@get_course_sessions')->name('admin.schedule.timetables.get_course_sessions');
    Route::post('timetables/get_rooms', 'TimetableController@get_rooms')->name('admin.schedule.timetables.get_rooms');
    Route::post('timetables/search_rooms', 'TimetableController@search_rooms')->name('admin.schedule.timetables.search_rooms');
    Route::post('timetables/filter-courses-sessions', 'TimetableController@filterCoursesSessions')->name('admin.schedule.timetables.filterCoursesSessions');

    /** Clone route. */
    Route::post('timetables/clone', 'TimetableController@cloneTimetable')->name('admin.schedule.timetables.clone');

});

