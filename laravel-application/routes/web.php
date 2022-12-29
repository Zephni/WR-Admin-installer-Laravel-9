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
Route::controller(\App\Http\Controllers\AdminController::class)->prefix('manage')->middleware('is_admin')->group(function () {
    // Dashboard
    Route::get('/', function () { return redirect()->route('admin.dashboard'); });
    Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');

    // Manageable models
    Route::get('/manageable-models/{model}', 'manageableModelBrowse')->name('admin.manageable-models.browse');
    Route::get('/manageable-models/{model}/create', 'manageableModelCreate')->name('admin.manageable-models.create');
    Route::get('/manageable-models/{model}/edit/{id}', 'manageableModelEdit')->name('admin.manageable-models.edit');
});

/* Temporary routes for testing purposes (will only work if env is local)
----------------------------------------------------------------*/
if (env('APP_ENV') === 'local') {
    Route::group(['prefix' => 'temp'], function(){
        Route::get('/set-permissions', function () {
            $user = App\Models\User::where('email', 'zephni@hotmail.co.uk')->first();
            $permissions = new App\Classes\Permissions();
            $permissions->master = true;
            $permissions->admin = true;
            $user->permissions = $permissions->asString();
            $user->save();
            dd($user->permissions);
        });

        Route::get('/get-permissions', function () {
            $user = App\Models\User::where('email', 'zephni@hotmail.co.uk')->first();
            dd($user->getPermissions());
        });
    });
}
