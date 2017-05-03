<?php

Route::group(['namespace' => 'Schedule', 'prefix' => 'schedule'], function () {

    /** Http Requesting */
    Route::get('/timetables', 'TimetableController@index')->name('admin.schedule.timetables.index');
    Route::get('/timetables/create', 'TimetableController@create')->name('admin.schedule.timetables.create');
    Route::get('/timetables/show', 'TimetableController@show')->name('admin.schedule.timetables.show');

    /** Ajax Requesting */
    Route::post('timetables/filter', 'TimetableController@filter')->name('admin.schedule.timetables.filter');

    Route::post('timetables/get_weeks', 'TimetableController@get_weeks')->name('admin.schedule.timetables.get_weeks');
    Route::post('timetables/get_options', 'TimetableController@get_options')->name('admin.schedule.timetables.get_options');
    Route::post('timetables/get_groups', 'TimetableController@get_groups')->name('admin.schedule.timetables.get_groups');
    Route::post('timetables/get_course_sessions', 'TimetableController@get_course_sessions')->name('admin.schedule.timetables.get_course_sessions');

    Route::post('timetables/filter-courses-sessions', 'TimetableController@filterCoursesSessions')->name('admin.schedule.timetables.filterCoursesSessions');

    /** Clone route */
    Route::post('timetables/clone', 'TimetableController@cloneTimetable')->name('admin.schedule.timetables.clone');

});

