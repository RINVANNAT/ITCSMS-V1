<?php

Route::get('{id_card}/{dob}/get-student-by-id-card', 'SystemVoteController@getStudentByIdCard');
Route::get('get-question-option', 'SystemVoteController@getQuestionOption');