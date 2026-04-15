<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

#[Signature('app:simulate-telemetry {deviceId}')]
#[Description('Simulate telemetry updates for a device with randomised values')]
class SimulateTelemetry extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deviceId = $this->argument('deviceId');
        $payload = [
            'battery_days' => rand(1, 100),
            'sensor_days' => rand(1, 100),
        ];

        $response = Http::post(url("/api/v1/devices/{$deviceId}/telemetry"), $payload);

        if ($response->successful()) {
            $this->info("Telemetry simulated successfully for device {$deviceId}: ".json_encode($payload));
        } else {
            $this->error("Failed to simulate telemetry for device {$deviceId}: ".$response->body());
        }
    }
}
