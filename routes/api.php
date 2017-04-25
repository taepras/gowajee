<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/courses/{day}/{time}', 'CoursesApiController@getCoursesByTime');
Route::get('/courses/{id}', 'CoursesApiController@getCourse');
Route::get('/courses', 'CoursesApiController@getAllCourses');
Route::post('/courses', 'CoursesApiController@register');
Route::delete('/courses', 'CoursesApiController@withdraw');
