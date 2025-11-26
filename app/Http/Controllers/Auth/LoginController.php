<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Update\Device;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function lupa()
    {
        return view('auth.password.lupa-password');
    }

    // public function index()
    // {
    //     // Ambil hanya device yang BELUM dipakai (user_id NULL)
    //     $availableDevices = Device::where(function ($query) {
    //             $query->where('status', 'online')
    //                 ->orWhere(function ($q) {
    //                     $q->where('status', 'in_use')
    //                         ->where('user_id', optional(Auth::user())->id);
    //                 });
    //         })
    //         ->where(function ($query) {
    //             $query->where('last_seen_at', '>', now()->subMinutes(30))
    //                 ->orWhereNull('last_seen_at');
    //         })
    //         ->orderBy('name')
    //         ->get();

    //     $lastUsedEspId = Cookie::get('last_esp_id');

    //     return view('auth.login', compact('availableDevices', 'lastUsedEspId'));
    // }

   
    public function index()
    {
        $availableDevices = Device::where(function ($query) {
                $query->where('status', 'online')
                    ->orWhere(function ($q) {
                        $q->where('status', 'in_use')
                            ->where('user_id', Auth::id()); // device milik user tetap muncul
                    });
            })
            ->orderBy('name')
            ->get();

        $lastUsedEspId = Cookie::get('last_esp_id');

        return view('auth.login', compact('availableDevices', 'lastUsedEspId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'esp_id'   => 'required|string|exists:devices,esp_id',
        ]);

        // 1. Cek user
        $user = User::where('username', $request->username)->firstOrFail();

        // 2. Autentikasi
        if (!Auth::attempt(['username' => $request->username, 'password' => $request->password], $request->has('remember'))) {
            return back()->withErrors(['password' => 'Password salah!'])->withInput();
        }

        // 3. Cari device yang sesuai dan tidak sedang dipakai
        $device = Device::where('esp_id', $request->esp_id)
            ->where('status', '!=', 'in_use')
            ->first();

        if (!$device) {
            Auth::logout();
            return back()
                ->withErrors(['esp_id' => 'Device ini sedang digunakan oleh user lain atau tidak tersedia.'])
                ->withInput();
        }

        // 4. Lock device
        $device->update([
            'user_id'        => Auth::id(),
            'status'         => 'in_use',
            'last_online_at' => now(),
        ]);

        // 5. Simpan ke session
        session([
            'selected_device' => $device,
            'selected_esp_id' => $device->esp_id,
            'selected_device_name' => $device->name ?? $device->esp_id,
        ]);

        // 6. Remember cookies
        if ($request->has('remember')) {
            Cookie::queue('username', $request->username, 60 * 24 * 30);
            Cookie::queue('last_esp_id', $device->esp_id, 60 * 24 * 30);
        } else {
            Cookie::queue(Cookie::forget('username'));
            Cookie::queue(Cookie::forget('last_esp_id'));
        }

        $request->session()->regenerate();

        // 7. Redirect
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.view'),
            'user'  => redirect()->route('order.view'),
            default => redirect()->route('login')->with('error', 'Role tidak dikenali'),
        };
    }

    public function logout(Request $request)
    {
        // Lepaskan device jika ada
        if (Auth::check()) {
            $device = Device::where('user_id', Auth::id())->first();
            if ($device) {
                $device->update([
                    'user_id' => null,
                    'status' => 'online',
                    'last_online_at' => now(),
                ]);
            }
        }

        // Logout user terlebih dahulu
        Auth::logout();

        // Hancurkan seluruh session lama
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Hapus cookie terkait
        Cookie::queue(Cookie::forget('last_esp_id'));

        return redirect()->route('login');
    }

}
