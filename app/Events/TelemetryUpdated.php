<?php

namespace App\Events;

use App\Models\Device;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TelemetryUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Device $device) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('devices'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'telemetry.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->device->id,
            'battery_days_remaining' => $this->device->battery_days_remaining,
            'sensor_days_remaining' => $this->device->sensor_days_remaining,
            'last_telemetry_at' => $this->device->last_telemetry_at->toIso8601String(),
        ];
    }
}
