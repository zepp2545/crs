<?php

Route::get('/','StudentsController@index');

// trial student
Route::post('trials/lesson_ajax','TrialStudentsController@lesson_ajax')->name('trials.lesson_ajax');
Route::post('trials/search','TrialStudentsController@search')->name('trials.search');
Route::post('trials/status','TrialStudentsController@change_status_ajax')->name('trials.status');
Route::resource('trials','TrialStudentsController');
Route::post('/trials/get_stu_info','TrialStudentsController@get_stu_info')->name('trials.get_stu_info');



// waiting list
Route::post('waiting/status','WaitingStudentsController@change_status_ajax')->name('waiting.status');
Route::post('waitings/get_stu_info','WaitingStudentsController@get_stu_info')->name('waitings.get_stu_info');
Route::get('waitings/create','WaitingStudentsController@create')->name('waitings.create');
Route::post('waitings/store','WaitingStudentsController@store')->name('waitings.store');
Route::get('waitings','WaitingStudentsController@index')->name('waitings.index');
Route::post('waitings/get_student','WaitingStudentsController@get_student_ajax')->name('waitings.get_student_ajax');
Route::get('waitings/edit/{id}','WaitingStudentsController@edit')->name('waitings.edit');
Route::put('waitings/update/{id}','WaitingStudentsController@update')->name('waitings.update');


//Regular student List
//this route has to be removed later.
Route::post('students/lesson_store/{id}','StudentsController@student_lesson_store')->name('students.student_lesson_store');
Route::post('students/lessonList','StudentsController@lessonList_ajax')->name('students.lessonList_ajax');
Route::put('students/dropouts/{id}','StudentsController@restore')->name('students.restore');
Route::get('students/lessonList','StudentsController@lessonList')->name('students.lessonList');
Route::post('students/search','StudentsController@search')->name('students.search');
Route::post('students/quit/search','StudentsController@quit_search')->name('students.quit.search');
Route::put('students/{id}/update','StudentsController@lesson_update')->name('students.lesson_update');
Route::delete('students/lesson/{id}','StudentsController@lesson_delete')->name('students.lesson_delete');
Route::get('students/dropouts','StudentsController@dropouts')->name('students.dropouts');
Route::resource('students','StudentsController',['except'=>['show', 'destroy']]);

//Bulk email
Route::get('bulkemail/create','BulkEmailController@create')->name('bulkemail.create');
Route::post('bulkemail/send','BulkEmailController@send')->name('bulkemail.send');


//Settings
//lessons
Route::resource('settings/lessonGroup','LessonGroupController',['except'=>['index','create','show','edit']]);
Route::delete('settings/lessons/delete/{id}','SettingsController@lessons_delete')->name('settings.lessons.delete');
Route::get('settings/lessons','SettingsController@lesson')->name('settings.lessons');
Route::put('settings/lessons/update/{id}','SettingsController@lessons_update')->name('settings.lessons.update');
Route::post('settings/lessons/store','SettingsController@lesson_store')->name('settings.lessons.store');

//places
Route::post('settings/places/add_place_ajax','SettingsController@add_place_ajax')->name('settings/places/add_place_ajax');
Route::get('settings/places','SettingsController@place')->name('settings.places');
Route::put('settings/places/update/{id}','SettingsController@places_update')->name('settings.places.update');
Route::post('settings/places/store','SettingsController@place_store')->name('settings.places.store');
Route::delete('settings/places/delete/{id}','SettingsController@places_delete')->name('settings.places.delete');
