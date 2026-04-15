<?php

namespace App\Services;

use App\Events\TelemetryUpdated;
use App\Models\Device;
use Illuminate\Support\Facades\DB;

class TelemetryIngestionService
{
    public function processWebhook(int $deviceId, array $payload): void
    {
        DB::transaction(function () use ($deviceId, $payload) {
            $device = Device::findOrFail($deviceId);

            $device->update([

                'battery_days_remaining' => $payload['battery_days'],
                'sensor_days_remaining' => $payload['sensor_days'],
                'last_telemetry_at' => now(),
            ]);

            $device->telemetryLogs()->create([
                'battery_days_remaining' => $payload['battery_days'],
                'sensor_days_remaining' => $payload['sensor_days'],
                'recorded_at' => now(),
            ]);

            TelemetryUpdated::dispatch($device);
        });
    }
}
