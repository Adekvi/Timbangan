<?php

namespace App\Http\Controllers\Update\User;

use App\Http\Controllers\Controller;
use App\Models\Update\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SwitchController extends Controller
{
    public function available()
    {
        $devices = Device::where(function ($query) {
                $query->where('status', 'online');
                    //   ->orWhere(function ($q) {
                    //       $q->where('status', 'in_use')
                    //         ->where('user_id', Auth::id());
                    //   });
            })
            ->orderBy('name')
            ->get(['id', 'esp_id', 'name', 'status']);

        // dd($devices);

        return response()->json($devices);
    }

    public function switch(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:devices,id'
        ]);

        $newDevice = Device::findOrFail($request->device_id);

        // Kunci logika: device baru harus online & tidak dipakai user lain
        if (
            $newDevice->status === 'in_use' &&
            $newDevice->user_id !== Auth::id()
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Device sedang digunakan oleh pengguna lain.'
            ], 403);
        }

        $oldDevice = Device::where('user_id', Auth::id())->first();

        DB::transaction(function () use ($oldDevice, $newDevice) {
            if ($oldDevice) {
                $oldDevice->update([
                    'user_id' => null,
                    'status' => 'online',
                ]);
            }

            $newDevice->update([
                'user_id' => Auth::id(),
                'status' => 'in_use',
            ]);
        });

        session(['active_esp' => $newDevice->esp_id]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil pindah ke device: ' . $newDevice->name,
            'device' => $newDevice
        ]);
    }

}
