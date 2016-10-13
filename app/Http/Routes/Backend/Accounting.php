<?php


Route::group([
    'prefix'     => 'accounting',
    'namespace' => 'Accounting'
], function() {

    Route::group([], function() {
        Route::resource('incomes', 'IncomeController');
        Route::post('income-data', 'IncomeController@data')->name('admin.accounting.income.data');
        Route::get('income/{id}/print', 'IncomeController@print_income')->name('admin.accounting.income.print');
        Route::get('income/{id}/print_income_candidate', 'IncomeController@print_income_candidate')->name('admin.accounting.income.print_candidate');
        Route::post('income/{id}/refund', 'IncomeController@refund')->name('admin.accounting.income.refund');
        Route::get('income/{id}/simple_print', 'IncomeController@print_simple_income')->name('admin.accounting.income.simple_print');

        Route::get('income-request-import', 'IncomeController@request_import')->name('admin.accounting.income.request_import');
        Route::post('income-import', 'IncomeController@import')->name('admin.accounting.income.import');
        Route::get('income-import-done', 'IncomeController@import_done')->name('admin.accounting.income.import_done');

    });

    Route::group([], function() {
        Route::resource('outcomes', 'OutcomeController');
        Route::post('outcome-data', 'OutcomeController@data')->name('admin.accounting.outcome.data');
        Route::get('client/search', 'OutcomeController@client_search')->name('admin.client.search');

        Route::get('outcome/{id}/simple_print', 'OutcomeController@print_simple_outcome')->name('admin.accounting.outcome.simple_print');
        Route::get('outcome/export', 'OutcomeController@export')->name('admin.accounting.outcome.export');
    });

    Route::group([], function() {
        Route::get('studentPayments', 'IncomeController@student_payment')->name('admin.accounting.studentPayment');
        Route::get('studentPayment-data', 'IncomeController@student_payment_data')->name('admin.accounting.studentPayment.data');

        Route::get('studentPayment/request-register-income/{id}', 'IncomeController@request_register_income')->name('admin.accounting.studentPayment.request_register_income');

        Route::get('candidatePayments', 'IncomeController@candidate_payment')->name('admin.accounting.candidatePayment');
        Route::get('candidatePayment-data', 'IncomeController@candidate_payment_data')->name('admin.accounting.candidatePayment.data');
        Route::get('payslipHistory', 'IncomeController@payslip_history')->name('admin.accounting.payslipHistory.data');
        Route::get('studentPayments/{studentId}/print', 'IncomeController@print_student_payment')->name('admin.accounting.studentPayment.print');

        Route::get('income/export', 'IncomeController@export')->name('admin.accounting.income.export');
    });

    Route::group([], function() {
        Route::resource('customers', 'CustomerController');
        Route::get('customer-data', 'CustomerController@data')->name('admin.accounting.customer.data');
        Route::get('customer/popup_create', 'CustomerController@popup_create')->name('admin.accounting.customer.popup_create');
        Route::post('customer/popup_store', 'CustomerController@popup_store')->name('admin.accounting.customer.popup_store');
    });

});

