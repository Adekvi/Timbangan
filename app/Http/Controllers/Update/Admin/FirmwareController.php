<?php

namespace App\Http\Controllers\Update\Admin;

use App\Http\Controllers\Controller;
use App\Models\Update\Device;
use App\Models\Update\Device_update;
use App\Models\Update\Firmwares;
use App\Services\MqttService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FirmwareController extends Controller
{
    protected $mqtt;

    public function __construct(MqttService $mqtt)
    {
        $this->middleware(['auth', 'role:admin']);
        $this->mqtt = $mqtt;
    }

    public function upload(Request $request)
    {
        $request->validate([
            'version'      => 'required|string',
            'device_type'  => 'required|string',
            'file_path'    => 'required|file',
        ]);

        $file = $request->file('file_path');

        // Nama file asli
        $fileName = $file->getClientOriginalName();

        // Simpan file ke storage
        $path = $file->storeAs('firmwares', $fileName);

        // URL untuk di-download ESP32 via OTA
        $downloadUrl = Storage::url($path);

        // Simpan ke DB
        $firmware = Firmwares::create([
            'version'      => $request->version,
            'device_type'  => $request->device_type,
            'file_name'    => $fileName,
            'file_path'    => $path,
            'download_url' => $downloadUrl,
            'notes'        => $request->notes,
        ]);

        return back()->with('success', 'Firmware berhasil diupload. Silahkan posting ke user.');
    }

    public function postToUsers($deviceId)
    {
        // Device target
        $device = Device::findOrFail($deviceId);

        // Firmware terbaru untuk tipe device itu
        $firmware = Firmwares::where('device_type', $device->device_type)
                    ->orderBy('id', 'desc')
                    ->first();

        if (!$firmware) {
            return back()->with('error', 'Tidak ada firmware untuk device type ini.');
        }

        // Buat record device_update
        Device_update::create([
            'device_id'   => $device->id,
            'firmware_id' => $firmware->id,
            'status'      => 'pending',
        ]);

        // Kirim MQTT ke device sesuai esp_id
        $this->mqtt->publish(
            "device/{$device->esp_id}/ota",
            json_encode([
                "version" => $firmware->version,
                "url"     => $firmware->download_url
            ]),
            1
        );

        return back()->with('success', 'Firmware berhasil diposting ke user.');
    }
}
