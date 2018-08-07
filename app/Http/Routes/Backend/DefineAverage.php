<?php

Route::group(['prefix' => 'define-average'], function (){
    Route::post('get-average', 'DefineAverageController@getAverage')->name('define-average.get-average');
    Route::post('store-average', 'DefineAverageController@storeAverage')->name('define-average.store-average');
});