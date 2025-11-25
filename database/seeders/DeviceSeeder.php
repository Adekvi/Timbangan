<?php

namespace Database\Seeders;

use App\Models\Update\Device;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $deviceTypes = ['esp32', 'esp8266', 'esp32-s2'];

        for ($i = 1; $i <= 10; $i++) {
            // Tentukan status device
            $statusChance = rand(1, 100);
            if ($statusChance <= 50) {
                // 50% device bebas
                $status = 'offline';
                $userId = null;
            } elseif ($statusChance <= 80) {
                // 30% device online tapi belum digunakan
                $status = 'online';
                $userId = null;
            } else {
                // 20% device sedang dipakai user
                $status = 'in_use';
                $userId = rand(1, 5); // pastikan user dengan id ini ada
            }

            Device::create([
                'esp_id' => 'ESP' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => 'Device ' . $i,
                'device_type' => $deviceTypes[array_rand($deviceTypes)],
                'ip_address' => '192.168.1.' . rand(2, 254),
                'current_firmware_version' => 'v' . rand(1, 3) . '.' . rand(0, 9),
                'user_id' => $userId,
                'status' => $status,
                'wifi_ssid' => null,
                'wifi_password' => null,
                'last_seen_at' => Carbon::now(),
                'last_online_at' => Carbon::now(),
            ]);
        }
    }
}
