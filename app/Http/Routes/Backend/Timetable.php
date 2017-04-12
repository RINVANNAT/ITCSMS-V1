<?php

Route::group(['namespace' => 'Schedule', 'prefix' => 'schedule'], function () {

    Route::resource('timetables', 'TimetableController');

});

