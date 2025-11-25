<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;

class MqttService
{
    // protected $client;

    // public function __construct()
    // {
    //     $host = config('mqtt.host');
    //     $port = config('mqtt.port');
    //     $clientId = config('mqtt.client_id');

    //     $this->client = new MqttClient($host, $port, $clientId);
    //     $this->client->connect();
    // }

    // public function publish($topic, $message, $qos = 1)
    // {
    //     $this->client->publish($topic, $message, $qos);
    // }

    // public function disconnect()
    // {
    //     $this->client->disconnect();
    // }

    // public function announce($version, $type)
    // {
    //     $this->publish(
    //         "device/update/announcement",
    //         json_encode([
    //             "version" => $version,
    //             "device_type" => $type
    //         ])
    //     );

    //     $this->disconnect();
    // }
}
