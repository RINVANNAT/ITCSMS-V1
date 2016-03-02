<?php

/**
 * This overwrites the Log Viewer Package routes so we can use middleware to protect it the way we want
 * You shouldn't have to change anything
 */
Route::group([
    'prefix'     => 'configuration',
    'namespace' => 'Configuration'
], function() {

    Route::group([], function() {
        Route::resource('departments', 'DepartmentController');
        Route::get('data', 'DepartmentController@data')->name('admin.configuration.department.data');
    });
});
