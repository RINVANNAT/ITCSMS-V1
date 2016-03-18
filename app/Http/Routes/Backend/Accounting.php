<?php


Route::group([
    'prefix'     => 'accounting',
    'namespace' => 'Accounting'
], function() {

    Route::group([], function() {
        Route::resource('incomes', 'IncomeController');
        Route::get('income-data', 'IncomeController@data')->name('admin.accounting.income.data');
        Route::get('income/{id}/print', 'IncomeController@print_income')->name('admin.accounting.income.print');

    });

    Route::group([], function() {
        Route::resource('outcomes', 'OutcomeController');
        Route::get('outcome-data', 'OutcomeController@data')->name('admin.accounting.outcome.data');
        Route::get('client/search', 'OutcomeController@client_search')->name('admin.client.search');
    });

    Route::group([], function() {
        Route::get('studentPayments', 'IncomeController@student_payment')->name('admin.accounting.studentPayment');
        Route::get('studentPayment-data', 'IncomeController@student_payment_data')->name('admin.accounting.studentPayment.data');
        Route::get('payslipHistory/{payslip_client_id}', 'IncomeController@payslip_history')->name('admin.accounting.payslipHistory.data');
        Route::get('studentPayments/{studentId}/print', 'IncomeController@print_student_payment')->name('admin.accounting.studentPayment.print');
    });

    Route::group([], function() {
        Route::resource('customers', 'CustomerController');
        Route::get('customer-data', 'CustomerController@data')->name('admin.accounting.customer.data');
        Route::get('customer/popup_create', 'CustomerController@popup_create')->name('admin.accounting.customer.popup_create');
        Route::post('customer/popup_store', 'CustomerController@popup_store')->name('admin.accounting.customer.popup_store');
    });

});

