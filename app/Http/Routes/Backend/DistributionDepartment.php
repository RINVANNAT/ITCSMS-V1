<?php

Route::group(['namespace' => 'DistributionDepartment',
    'prefix' => 'distribution-department'], function () {
    Route::get('/', 'DistributionDepartmentController@index')->name('distribution-department.index');
    Route::get('get-generate-page', 'DistributionDepartmentController@getGeneratePage')->name('distribution-department.get-generate-page');
    Route::post('generate', 'DistributionDepartmentController@generate')->name('distribution-department.generate');
    Route::get('edit/{id}', 'DistributionDepartmentController@edit')->name('distribution-department.edit');
    Route::get('delete', 'DistributionDepartmentController@delete')->name('distribution-department.delete');
    Route::get('data', 'DistributionDepartmentController@data')->name('distribution-department.data');

    Route::get('import-data', 'DistributionDepartmentController@importData')->name('distribution-department.import-data');
    Route::post('get-academic-year', 'DistributionDepartmentController@getAcademicYear')->name('distribution-department.get-academic-year');
    Route::post('get-department', 'DistributionDepartmentController@getDepartment')->name('distribution-department.get-department');
    Route::post('get-total-student-annuals', 'DistributionDepartmentController@getTotalStudentAnnuals')->name('distribution-department.get-total-student-annuals');

    Route::get('{academic_year_id}/export', 'DistributionDepartmentController@export')->name('distribution-department.export');
});