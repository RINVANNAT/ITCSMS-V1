<?php


//Route::resource('courseAnnuals', 'CourseAnnualAPIController');
Route::get('courseAnnuals',[
    'as' => 'api.v1.courseAnnuals',
    'uses' => 'CourseAnnualAPIController@index',
]);


//Route::resource('degrees', 'DegreeAPIController');
Route::get('degrees',[
    'as' => 'degrees.api.v1',
    'uses' => 'DegreeAPIController@index',
]);

//Route::resource('grades', 'GradeAPIController');
Route::get('grades',[
    'as' => 'grades.api.v1',
    'uses' => 'GradeAPIController@index',
]);

//Route::resource('departments', 'DepartmentAPIController');
Route::get('departments',[
    'as' => 'departments.api.v1',
    'uses' => 'DepartmentAPIController@index',
]);

//Route::resource('semesters', 'SemesterAPIController');
Route::get('semesters',[
    'as' => 'semesters.api.v1',
    'uses' => 'SemesterAPIController@index',
]);

//Route::resource('academicYears', 'AcademicYearAPIController');
Route::get('academicYears',[
    'as' => 'academicYears.api.v1',
    'uses' => 'AcademicYearAPIController@index',
]);

Route::get('studentEvaStatuses',[
    'as' => 'studentEvaStatuses.api.v1',
    'uses' => 'StudentEvaStatusAPIController@index',
]);


Route::get('scoreEvaluations',[
    'as' => 'scoreeval.api.v1',
    'uses' => 'ScoreEvaluationAPIController@group',
]);

Route::get('attacheStudentEvalStatuses',array(  "uses"=>'StudentEvaStatusAPIController@attache',"as"=>"attacheStudentEvalStatuses.api.v1",));

