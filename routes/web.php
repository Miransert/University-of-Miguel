<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;

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

Route::redirect('/', 'courses')->name('home');

//
Route::get('courses', [CourseController::class, 'index'])->name('index');

Route::get('courses/create', [CourseController::class, 'displayCreate'])->name('create');

Route::post('courses', [CourseController::class, 'createCourse']);

//
Route::get('courses/{course}', [CourseController::class, 'showCourse'])->name('courses.show');

Route::get('/courses/{course}/edit', [CourseController::class, 'displayUpdate']);

Route::put('/courses/{course}', [CourseController::class, 'updateCourse']);

Route::delete('/courses/{course}', [CourseController::class, 'deleteCourse']);
