<?php

Route::group([
    'prefix'     => 'score',
    'namespace' => 'Score'
], function() {


    Route::group([], function() {
        Route::get('absences/indexByGroup', [
            'as' => 'absences.indexByGroup',
            'uses' => 'AbsenceController@indexByGroup',
        ]);

        Route::get('absences/editMany',[
            'as' => 'absences.editMany',
            'uses' => 'AbsenceController@editMany',
        ]);

        Route::any('absences/updateMany', [
            'as' => 'absences.updateMany',
            'uses' => 'AbsenceController@updateMany',
        ]);

        Route::get('absences/createMany', [
            'as' => 'absences.craeteMany',
            'uses' => 'AbsenceController@editMany',
        ]);


        Route::get('absences/input', [
            'as' => 'absences.input',
            'uses' => 'AbsenceController@input',
        ]);

        Route::resource('absences', 'AbsenceController');
        Route::get('absences/{id}/delete', [
            'as' => 'absences.delete',
            'uses' => 'AbsenceController@destroy',
        ]);


    });
});
