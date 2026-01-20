<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        // For static design - allow access without checking auth
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // For static design - accept any dummy data and redirect
        // No actual authentication required
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Just set a session variable to indicate "logged in" for static design
        $request->session()->put('logged_in', true);
        $request->session()->put('user_email', $request->email);
        
        // Always redirect to admin dashboard (static design)
        return redirect()->intended(route('home'));
    }

    public function logout(Request $request)
    {
        // Clear session for static design
        $request->session()->forget('logged_in');
        $request->session()->forget('user_email');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        // Implementation for forgot password
        return back()->with('status', 'Password reset link sent to your email.');
    }

    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        // Implementation for reset password
        return redirect()->route('login')->with('status', 'Password reset successfully.');
    }
}

