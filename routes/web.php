<?php

use App\Models\Faculty;
use Illuminate\Support\Facades\Route;

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

Route::get('courses', function() {
    $faculties = Faculty::all();
    return view('courses.index')->with('faculties', $faculties);
});

Route::get('courses/{course}', function() {
    return view('courses.show');
})->name('courses.show');
