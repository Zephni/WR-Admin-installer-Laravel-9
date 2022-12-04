<?php

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

// Frontend routes
Route::view('/', 'home')->name('home');

// Auth routes
Route::controller(App\Http\Controllers\AuthController::class)->group(function(){
    Route::get('/login', 'login')->name('login');
    Route::post('/login-post', 'login_post')->name('login-post');
    Route::get('/register', 'register')->name('register');
    Route::post('/register-post', 'register_post')->name('register-post');
    Route::get('/logout', 'logout')->name('logout');
});
