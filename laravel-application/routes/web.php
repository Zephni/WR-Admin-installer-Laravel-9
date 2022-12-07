<?php

use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request;

/* Frontend routes
----------------------------------------------------------------*/
Route::view('/', 'home')->name('home');

/* Auth routes
----------------------------------------------------------------*/
Route::controller(App\Http\Controllers\AuthController::class)->group(function () {
    Route::middleware('guest')->group(function(){
        // Login
        Route::view('/login', 'auth.login')->name('login');
        Route::post('/login', 'login')->name('login-post');

        // Register
        Route::view('/register', 'auth.register')->name('register');
        Route::post('/register', 'register')->name('register-post');

        // Forgot password
        Route::view('/forgot-password', 'auth.forgot-password')->name('password.forgot');
        Route::post('/forgot-password', 'forgot_password')->name('password.forgot.request');

        // Reset password
        Route::get('/reset-password/{token}', function(Request $request, $token){
            return view('auth.reset-password', [
                'token' => $token,
                'email' => $request->email ?? ''
            ]);
        })->name('password.reset');
        Route::post('/reset-password', 'reset_password')->name('password.reset.request');
    });

    // Logout
    Route::get('/logout', 'logout')->name('logout');
});

/* Admin routes
----------------------------------------------------------------*/
Route::controller(\App\Http\Controllers\AdminController::class)->prefix('manage')->group(function () {
    // Dashboard
    Route::get('/', function () { return redirect()->route('admin.dashboard'); });
    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');

    // Logout
    Route::get('/logout', 'logout')->name('admin.logout');
});

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
