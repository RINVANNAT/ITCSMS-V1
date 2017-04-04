<?php

    Route::group(['namespace' => 'Schedule', 'prefix' => 'schedule'], function(){

        Route::resource('calendars', 'CalendarController');

        Route::get('calendars/event/{year}', 'CalendarController@getEventsByYear')->name('calendars.event.year');
        Route::post('calendars/event/drag', 'CalendarController@dragEvent')->name('calendars.event.drag');

        Route::get('calendars/events/render', 'CalendarController@getEvents')->name('calendars.event.render');

    });

