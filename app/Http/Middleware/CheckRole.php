<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        // Cek apakah role user ada di daftar role yang diizinkan
        if (!in_array($userRole, $roles)) {

            // Daripada abort 403, redirect ke halaman yang sesuai rolenya
            if ($userRole === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('info', 'Anda diarahkan ke halaman admin.');
            }

            return redirect()->route('dashboard')
                ->with('info', 'Anda diarahkan ke halaman user.');
        }

        return $next($request);
    }
}