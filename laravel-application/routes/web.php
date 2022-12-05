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

// Temporary route to modify permissions to zephni user
Route::get('/set-permissions', function () {
    $user = App\Models\User::where('email', 'zephni@hotmail.co.uk')->first();
    $user->permissions = [
        'admin'     => true, // admin means can log in to admin section
        'master'    => true, // master means can do anything, may want to add more granular permissions later
    ];
    $user->save();
    dd($user->permissions);
});

// Frontend routes
Route::view('/', 'home')->name('home');

// Auth routes
Route::controller(App\Http\Controllers\AuthController::class)->group(function(){
    // Login
    Route::get('/login', 'login')->name('login');
    Route::post('/login-post', 'login_post')->name('login-post');

    // Register
    Route::get('/register', 'register')->name('register');
    Route::post('/register-post', 'register_post')->name('register-post');

    // Logout
    Route::get('/logout', 'logout')->name('logout');
});
