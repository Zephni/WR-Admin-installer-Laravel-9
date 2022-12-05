<?php

use Illuminate\Support\Facades\Route;

/* Temporary routes for testing purposes (will only work if env is local)
----------------------------------------------------------------*/
if (env('APP_ENV') === 'local') {
    Route::group(['prefix' => 'temp'], function(){
        Route::get('/set-permissions', function () {
            $user = App\Models\User::where('email', 'zephni@hotmail.co.uk')->first();
            $user->permissions = [
                'master' => true, // master means can do anything, may want to add more granular permissions later
                'admin'  => true, // admin means can access admin section, if authenticated but admin is false, then this application allows general authenticated users
                // examples of granular permissions:
                // 'tables.create' => ['table_name', ...],
                // 'tables.update' => ['table_name', ...],
                // 'tables.delete' => ['table_name', ...],
            ];
            $user->save();
            dd($user->permissions);
        });
    });
}

/* Frontend routes
----------------------------------------------------------------*/
Route::view('/', 'home')->name('home');

/* Auth routes
----------------------------------------------------------------*/
Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::middleware('guest')->group(function(){
        // Login
        Route::get('/login', 'login')->name('login');
        Route::post('/login-post', 'login_post')->name('login-post');

        // Register
        Route::get('/register', 'register')->name('register');
        Route::post('/register-post', 'register_post')->name('register-post');

        // Forgot password
        Route::get('/forgot-password', 'forgot_password')->name('forgot-password');
        Route::post('/forgot-password-send-request', 'forgot_password_send_request')->name('forgot-password-send-request');

        // Reset password
        Route::get('/reset-password/{token}', 'reset_password')->name('password.reset');
        Route::post('/reset-password-post', 'reset_password_post')->name('reset-password-post');
    });

    // Logout
    Route::get('/logout', 'logout')->name('logout');
});

/* Admin routes
----------------------------------------------------------------*/
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'is_admin']], function () {
    Route::get('/', function () {
        return 'Logged in as admin';
    })->name('admin-home');
});
