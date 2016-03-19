<?php

Route::group([
    'prefix'     => 'score',
    'namespace' => 'Score'
], function() {

    Route::group([], function() {
        Route::resource('absences', 'AbsenceController');
        Route::get('absences/{id}/delete', [
            'as' => 'absences.delete',
            'uses' => 'AbsenceController@destroy',
        ]);

    });
});
