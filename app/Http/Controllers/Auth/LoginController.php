<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            return Auth::user()->isAdmin()
                ? redirect()->intended('admin/dashboard')
                : redirect()->intended('/dashboard');
        }

        // Pesan error spesifik
        $userExists = \App\Models\User::where('username', $request->username)->exists();

        return back()
            ->withErrors([
                $userExists ? 'password' : 'username'
                => $userExists ? 'Password salah.' : 'Username tidak ditemukan.',
            ])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
