<?php

namespace App\Services;

use App\Models\Device;

class DeviceStatusService
{
    const CRITICAL_DAYS_THRESHOLD = 5;

    public function getDeviceWidgetData(int $deviceId, int $tenantId): array
    {
        $device = Device::with(['model', 'subscription', 'replacement'])
            ->where('tenant_id', $tenantId)
            ->findOrFail($deviceId);

        $hasAlert = $device->battery_days_remaining <= self::CRITICAL_DAYS_THRESHOLD ||
                    $device->sensor_days_remaining <= self::CRITICAL_DAYS_THRESHOLD;

        return [
            'id' => $device->id,
            'name' => $device->name,
            'model' => $device->model->name,
            'status' => [
                'has_alert' => $hasAlert,
                'is_online' => $device->last_telemetry_at && $device->last_telemetry_at->isAfter(now()->subHours(24)),
            ],
            'telemetry' => [
                'battery_days' => $device->battery_days_remaining,
                'sensor_days' => $device->sensor_days_remaining,
            ],
            'subscription' => [
                'plan_name' => 'SondeCare+',
                'is_active' => $device->subscription?->is_active ?? false,
            ],
            'replacement' => [
                'status' => $device->replacement?->status ?? 'none',
                'delivery_date' => $device->replacement?->delivery_date?->format('Y-m-d'),
            ],
        ];
    }
}
