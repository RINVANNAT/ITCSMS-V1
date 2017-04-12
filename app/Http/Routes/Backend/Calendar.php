<?php

Route::group(['namespace' => 'Schedule', 'prefix' => 'schedule'], function () {

    Route::resource('calendars', 'CalendarController');

//    Route::get('calendars/fullcalendar/{year}', 'CalendarController@getEventsByYear')->name('fullcalendar.viewer');
    Route::get('calendars/fullcalendar/events', 'CalendarController@listEventsOnSideLeft')->name('events.viewer');
    Route::post('calendars/fullcalendar/add', 'CalendarController@dragEvent')->name('fullcalendar.add');
    // Rendering events
    Route::get('calendars/events/render', 'CalendarController@getEvents')->name('fullcalendar.render');
    // Delete event
    Route::post('calendars/fullcalendar/delete', 'CalendarController@deleteEvent')->name('fullcalendar.delete');
    // Resizing event
    Route::post('calendars/fullcalendar/resize', 'CalendarController@resizeEvent')->name('fullcalendar.resize');
    // Moving event
    Route::post('calendars/fullcalendar/move', 'CalendarController@moveEvent')->name('fullcalendar.move');


    // Create event
    Route::post('calendars/events/store', 'CalendarController@store')->name('events.store');
    // AjaxTraitCalendarController with traits
    Route::get('/departments', 'CalendarController@getDepartments');
});

