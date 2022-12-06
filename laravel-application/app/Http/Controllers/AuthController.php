<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * register
     * Register a new user (post request)
     * @param  mixed $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->permissions = [
            'master' => false,
            'admin'  => true
        ];
        $user->save();

        // Login the user and redirect to home page
        Auth::login($user);
        return redirect()->route('home');
    }

    /**
     * login
     * Login (post request)
     * @param  mixed $request
     * @return Response
     */
    public function login(Request $request): Response
    {
        // Validate request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Gather the email and password from the request
        $credentials = $request->only('email', 'password');

        // Attempt to login the user and redirect to home page if successful, otherwise back to login page with error
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }
        else
        {
            return back()->withErrors([
                'message' => 'The provided credentials do not match our records.',
            ]);
        }
    }


    /**
     * forgot_password
     * Forgot password (post request), sends email to user with reset link
     * @param  mixed $request
     * @return Response
     */
    public function forgot_password(Request $request): Response
    {
        // Validate request
        $request->validate([
            'email' => 'required|email'
        ]);

        // Sends the reset link to the user
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Redirects to login page with email if successful, otherwise back to forgot password page with error
        return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
    }

    /**
     * reset_password
     * Resets a users password by timed token sent to email if valid (post request)
     * @param  mixed $request
     * @return Response
     */
    public function reset_password(Request $request): Response
    {
        // Validate request
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Resets the password of the user based on the token, email, password and password confirmation
        $status = Password::reset(
            $request->only('token', 'email', 'password', 'password_confirmation'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // Redirects to login page with email if successful, otherwise back to reset page with error
        return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with(['status' => __($status), 'email' => $request->email])
                : back()->withErrors(['email' => [__($status)]]);
    }


    /**
     * logout
     * Logout the current user (post request)
     * @param  mixed $request
     * @return Response
     */
    public function logout(Request $request): Response
    {
        // Logout the user
        Auth::logout();

        // Invalidate the session and regenerate the CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to home page
        return redirect()->route('home');
    }
}
