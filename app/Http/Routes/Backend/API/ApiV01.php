<?php

Route::group([
    'prefix'     => 'api',
    'namespace' => 'api'
], function() {
    Route::any('v1/courseAnnuals',[
        'as' => 'courseAnnuals.api.v1',
        'uses' => 'Restfull\CourseAnnualApiController@index',
    ]);
});
