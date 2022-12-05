<?php

use Illuminate\Support\Facades\Route;

/* Temporary routes for testing purposes (will only work if env is local)
----------------------------------------------------------------*/
if (env('APP_ENV') === 'local') {
    Route::group(['prefix' => 'temp'], function(){
        Route::get('/set-permissions', function () {
            $user = App\Models\User::where('email', 'zephni@hotmail.co.uk')->first();
            $user->permissions = [
                'admin'  => true, // admin means can access admin section, if authenticated but admin is false, then this application allows general authenticated users
                'master' => true, // master means can do anything, may want to add more granular permissions later
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
    Route::middleware('redirect_if_authenticated')->group(function(){
        // Login
        Route::get('/login', 'login')->name('login');
        Route::post('/login-post', 'login_post')->name('login-post');

        // Register
        Route::get('/register', 'register')->name('register');
        Route::post('/register-post', 'register_post')->name('register-post');
    });

    // Logout
    Route::get('/logout', 'logout')->name('logout');
});

/* Admin routes
----------------------------------------------------------------*/
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/', function () {return 'Logged in as admin';})->name('admin-home');
});
