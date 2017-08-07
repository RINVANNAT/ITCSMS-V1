<?php

Route::group([], function() {
    Route::post('filter_by_class', 'FilterController@get_filter_value_by_class')->name('admin.filter.get_filter_by_class');
    Route::post('filter_by_group', 'FilterController@get_filter_value_by_group')->name('admin.filter.get_filter_by_group');
});