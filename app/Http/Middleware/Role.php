<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        Log::debug('Roles received in middleware: ', [$roles]);

        // Parse string roles menjadi array
        $allowedRoles = is_array($roles) ? $roles : explode(',', $roles);
        $allowedRoles = array_map('trim', $allowedRoles);

        // Perlessly check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userRole = Auth::user()->role;

        // Izinkan superadmin untuk semua aksi
        if ($userRole === 'admin') {
            return $next($request);
        }

        // Periksa apakah peran pengguna ada dalam daftar peran yang diizinkan
        foreach ($allowedRoles as $role) {
            if ($userRole === $role) {
                return $next($request);
            }
        }

        // Jika peran tidak cocok, redirect ke halaman utama
        return redirect('/')->with('error', 'Akses dilarang. Anda tidak memiliki izin untuk mengakses halaman ini.');
    }
}
