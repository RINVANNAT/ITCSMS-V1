<?php

Route::group(['middleware' => 'web'], function() {
    /**
     * Switch between the included languages
     */
    Route::group(['namespace' => 'Language'], function () {
        require (__DIR__ . '/Routes/Language/Language.php');
    });

    /**
     * Frontend Routes
     * Namespaces indicate folder structure
     */
    Route::group(['namespace' => 'Frontend'], function () {
        require (__DIR__ . '/Routes/Frontend/Frontend.php');
        require (__DIR__ . '/Routes/Frontend/Access.php');
    });
});

/**
 * Backend Routes
 * Namespaces `icate folder structure
 * Admin middleware groups web, auth, and routeNeedsPermission
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'middleware' => 'admin'], function () {
    /**
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     */
    require (__DIR__ . '/Routes/Backend/Dashboard.php');
    require (__DIR__ . '/Routes/Backend/Access.php');
    require (__DIR__ . '/Routes/Backend/LogViewer.php');
    require (__DIR__ . '/Routes/Backend/Configuration.php');
    require (__DIR__ . '/Routes/Backend/Student.php');
    require (__DIR__ . '/Routes/Backend/Candidate.php');
    require (__DIR__ . '/Routes/Backend/Examination.php');
    require (__DIR__ . '/Routes/Backend/Accounting.php');
    require (__DIR__ . '/Routes/Backend/Employee.php');
    require (__DIR__ . '/Routes/Backend/Course.php');
    require (__DIR__ . '/Routes/Backend/Scholarship.php');
    require (__DIR__ . '/Routes/Backend/Absence.php');
    require (__DIR__ . '/Routes/Backend/Score.php');
    require (__DIR__ . '/Routes/Backend/Reporting.php');
    
    require (__DIR__ . '/Routes/Backend/employeeExamRole.php');
    require (__DIR__ . '/Routes/Backend/EntranceExamCourse.php');

    require (__DIR__ . '/Routes/Backend/Calendar.php');


});


/*
|--------------------------------------------------------------------------
| API routes
|--------------------------------------------------------------------------
*/

Route::group([
    'prefix' => 'api',
    'namespace' => 'API'
    ], function () {
    Route::group([
        'prefix' => 'v1'], function () {
//            require config('infyom.laravel_generator.path.api_routes');
        require (__DIR__ . '/api_routes.php');
    });
});


/*----this api is used for student portal accessibility-----*/

Route::group(['prefix'=> 'api', 'namespace' => 'API'], function() {

    Route::group(['prefix' => 'student'],function() {
        require (__DIR__ . '/Routes/Backend/API/Student.php');
    });

    Route::group(['prefix' => 'employee'], function() {
        require (__DIR__ . '/Routes/Backend/API/Employee.php');
    });

    Route::group(['prefix' => 'department'], function() {
        require (__DIR__ . '/Routes/Backend/API/Department.php');
    });

    Route::group(['prefix' => 'grade'], function() {
        require (__DIR__ . '/Routes/Backend/API/Grade.php');
    });

    Route::group(['prefix' => 'degree'], function() {
        require (__DIR__ . '/Routes/Backend/API/Degree.php');
    });

    Route::group(['prefix' => 'option'], function() {
        require (__DIR__ . '/Routes/Backend/API/Option.php');
    });

    Route::group(['prefix' => 'gender'], function() {
        require (__DIR__ . '/Routes/Backend/API/Gender.php');
    });

    Route::group(['prefix' => 'academic-year'], function() {
        require (__DIR__ . '/Routes/Backend/API/academicYear.php');
    });
});





