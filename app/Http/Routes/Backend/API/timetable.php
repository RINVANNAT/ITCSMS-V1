<?php

Route::get('/show/{academic_year_id}/{student_id_card}/{semester_id}/{week_id}', 'TimetableApiController@show');
/** get all semester */
Route::get('/get_semesters', 'TimetableApiController@get_semesters');
Route::get('/get_weeks', 'TimetableApiController@get_weeks');