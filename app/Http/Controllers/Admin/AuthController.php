<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        // Redirect to dashboard if already authenticated as admin
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }

    /**
     * Handle admin login.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Check if the user is an admin
            if (!$user->isAdmin()) {
                Auth::logout();
                return redirect()->route('admin.login')
                    ->with('error', 'Access denied. Admin privileges required.');
            }

            // Check if the user is active
            if (!$user->isActive()) {
                Auth::logout();
                return redirect()->route('admin.login')
                    ->with('error', 'Your account is inactive. Please contact the administrator.');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return redirect()->back()
            ->with('error', 'Invalid credentials. Please try again.')
            ->withInput($request->only('email'));
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show the password reset form.
     */
    public function showPasswordResetForm()
    {
        return view('admin.auth.passwords.email');
    }
}
