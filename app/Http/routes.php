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
    require (__DIR__ . '/Routes/Backend/Reporting.php');
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

