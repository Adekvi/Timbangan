<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function lupa()
    {
        return view('auth.password.lupa-password');
    }

    public function index()
    {
        // dd(session()->all());
        return view('auth.login');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        // Cek apakah user ada
        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return redirect()->back()
                ->withErrors(['username' => 'Username belum terdaftar. Silahkan registrasi terlebih dahulu!'])
                ->withInput();
        }

        // Autentikasi dengan Remember Me
        $remember = $request->has('remember');
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Simpan username ke cookie jika Remember Me dicentang
            if ($remember) {
                Cookie::queue('username', $request->username, 60 * 24 * 30); // 30 hari
            } else {
                Cookie::queue(Cookie::forget('username'));
            }

            // Redirect berdasarkan role
            switch (Auth::user()->role) {
                case 'admin':
                    return redirect()->route('admin.view');
                case 'user':
                    return redirect()->route('order.view');
                default:
                    return redirect()->route('login')->withErrors(['access' => 'Role tidak dikenali']);
            }
        }

        // Jika autentikasi gagal
        return redirect()->back()
            ->withErrors(['password' => 'Password salah!'])
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }
}
