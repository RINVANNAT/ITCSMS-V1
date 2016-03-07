<?php


Route::group([
    'prefix'     => 'accounting',
    'namespace' => 'Accounting'
], function() {

    Route::group([], function() {
        Route::resource('incomes', 'IncomeController');
        Route::get('income-data', 'IncomeController@data')->name('admin.accounting.income.data');
    });
    Route::group([], function() {
        Route::resource('outcomes', 'OutcomeController');
        Route::get('outcome-data', 'OutcomeController@data')->name('admin.accounting.outcome.data');
    });

    Route::group([], function() {
        Route::resource('customers', 'CustomerController');
        Route::get('customer-data', 'CustomerController@data')->name('admin.accounting.customer.data');
    });

});