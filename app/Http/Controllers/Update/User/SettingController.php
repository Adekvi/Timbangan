<?php

namespace App\Http\Controllers\Update\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function setting()
    {
        $profile = Auth::user();

        return view('admin.setting.index', compact('profile'));
    }

   public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = [
            'username' => $request->username,
            'line' => $request->line,
        ];

        // Password jika diisi
        if ($request->filled('password')) {
            $data['password'] = $request->password;
            // $data['password'] = bcrypt($request->password);
        }

        // Upload foto
        if ($request->hasFile('foto')) {

            // Hapus foto lama
            if ($user->foto && file_exists(storage_path('app/public/profile/' . $user->foto))) {
                unlink(storage_path('app/public/profile/' . $user->foto));
            }

            // Simpan foto baru
            $originalName = $request->file('foto')->getClientOriginalName();
            $fotoPath = $request->file('foto')->storeAs('profile', $originalName, 'public');

            // Simpan path ke DB
            $data['foto'] = $fotoPath;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
