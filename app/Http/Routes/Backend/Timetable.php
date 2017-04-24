<?php

Route::group(['namespace' => 'Schedule', 'prefix' => 'schedule'], function () {

    /** Http Requesting */
    Route::get('/timetables', 'TimetableController@index')->name('admin.schedule.timetables.index');
    Route::get('/timetables/create', 'TimetableController@create')->name('admin.schedule.timetables.create');
    Route::get('/timetables/show', 'TimetableController@show')->name('admin.schedule.timetables.show');

    /** Ajax Requesting */
    Route::post('timetables/filter', 'TimetableController@filter')->name('admin.schedule.timetables.filter');
    Route::post('timetables/filter-courses-sessions', 'TimetableController@filterCoursesSessions')->name('admin.schedule.timetables.filterCoursesSessions');

    /** Clone route */
    Route::post('timetables/clone', 'TimetableController@cloneTimetable')->name('admin.schedule.timetables.clone');

});

