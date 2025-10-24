<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function loginForm(Request $request)
    {
        return view('auth.login');
    }

    public function signupForm(Request $request)
    {
        return view('auth.signup');
    }
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }


    public function signup(SignupRequest $request)
    {
        Log::debug('An informational message.');
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login-form');
    }

    public function profile(Request $request)
    {
        // Logic to get user profile
    }

    public function updateProfile(Request $request)
    {
        // Logic to update user profile
    }

    public function changePassword(Request $request)
    {
        // Logic to change user password
    }

    public function forgotPassword(Request $request)
    {
        // Logic for forgot password
    }


    public function resetPassword(Request $request)
    {
        // Logic to reset user password
    }


}
