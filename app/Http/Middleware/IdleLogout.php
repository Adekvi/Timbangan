<?php

namespace App\Http\Middleware;

use App\Models\Update\Device;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IdleLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // User belum login -> lanjut
        if (!Auth::check()) {
            return $next($request);
        }

        // Jika session sudah expired, Auth::check() tetap true,
        // tetapi session akan direfresh di request berikutnya.
        // Jadi kita perlu cek session Anda sendiri.
        $lastActivity = session('last_activity');

        $maxIdle = config('session.lifetime') * 60; // detik
        $now = now()->timestamp;

        if ($lastActivity && ($now - $lastActivity) > $maxIdle) {

            // reset device yang lagi dipakai user
            Device::where('user_id', Auth::id())
                ->where('status', 'in_use')
                ->update([
                    'status' => 'online',
                    'user_id' => null
                ]);

            Auth::logout();
            session()->flush();

            return redirect()->route('login')
                ->with('message', 'Session anda habis.');
        }

        // update last activity
        session(['last_activity' => $now]);

        return $next($request);
    }
}
