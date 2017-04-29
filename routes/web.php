<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@index');

Route::get('/test', 'HomeController@test');


Route::get('/api/courses/{day}/{time}', 'CoursesApiController@getCoursesByTime');
Route::get('/api/courses/all', 'CoursesApiController@getAllCourses');
Route::get('/api/courses/{id}', 'CoursesApiController@getCourse');
Route::get('/api/courses', 'CoursesApiController@getEnrolledCourses');
Route::post('/api/courses', 'CoursesApiController@register');
Route::delete('/api/courses', 'CoursesApiController@withdraw');

Route::post('/api/command', 'SpeechController@test');
Route::get('/forTestWav', 'SpeechController@uploadWav');
