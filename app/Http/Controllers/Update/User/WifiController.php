<?php

namespace App\Http\Controllers\Update\User;

use App\Http\Controllers\Controller;
use App\Models\Update\Device;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WifiController extends Controller
{
    public function updateWifi(Request $request)
    {
        $request->validate([
            'ssid' => 'required|string',
            'password' => 'required|string',
        ]);

        // Ambil device yang sedang digunakan user
        $device = Device::where('user_id', Auth::id())->first();

        if (!$device) {
            return response()->json(['success' => false, 'message' => 'Device tidak ditemukan untuk user ini.'], 404);
        }

        $device->wifi_ssid = $request->ssid;
        $device->wifi_password = $request->password;
        $device->wifi_updated_at = now();   // <--- PENTING
        $device->save();

        return response()->json(['success' => true]);
    }

    public function getWifiConfig()
    {
        $device = Device::where('user_id', Auth::id())->first();

        if (!$device) {
            return response()->json(['ssid'=>'', 'password'=>'']);
        }

        return response()->json([
            'ssid' => $device->wifi_ssid,
            'password' => $device->wifi_password
        ]);
    }

    public function stream()
    {
        // Harus ada device yang sedang dipakai user
        $device = Device::where('user_id', Auth::id())->firstOrFail();

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');

        // Kirim dulu event "connected"
        echo "event: connected\n";
        echo "data: " . json_encode(['esp_id' => $device->esp_id]) . "\n\n";
        ob_flush();
        flush();

        $lastChecked = now();

        while (true) {
            // Cek apakah ada perubahan wifi_ssid / password sejak terakhir dicek
            $updated = Device::where('id', $device->id)
                ->where('wifi_updated_at', '>', $lastChecked)
                ->first(['wifi_ssid', 'wifi_password', 'wifi_updated_at']);

            if ($updated) {
                echo "event: wifi-change\n";
                echo "data: " . json_encode([
                    'ssid' => $updated->wifi_ssid,
                    'password' => $updated->wifi_password
                ]) . "\n\n";
                ob_flush();
                flush();

                $lastChecked = $updated->wifi_updated_at;
            }

            if (connection_aborted()) break;

            // Tidur 1 detik (bisa 0.5s kalau mau lebih cepat)
            sleep(1);
        }
    }

}
