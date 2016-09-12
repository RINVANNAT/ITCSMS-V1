<?php

Route::group([
    'prefix'     => 'score',
    'namespace' => 'Score'
], function() {


    Route::group([], function() {
        Route::get('input', [
            'as' => 'score.input',
            'uses' => 'ScoreController@input',
        ]);

        Route::any('updateMany', [
            'as' => 'score.updateMany',
            'uses' => 'ScoreController@updateMany',
        ]);

        Route::any('ranking', [
            'as' => 'score.ranking',
            'uses' => 'ScoreController@ranking',
        ]);

        Route::get('gen',[
            'as' => 'score.gen',
            'uses' => 'ScoreController@gen',
        ]);

    });
});
