<?php

namespace Database\Factories;

use App\Models\DeviceModel;
use App\Models\Location;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'location_id' => function (array $attributes) {
                return Location::factory()->create(['tenant_id' => $attributes['tenant_id']])->id;
            },
            'device_model_id' => DeviceModel::factory(),
            'name' => 'Avenue du Président René Coty',
            'battery_days_remaining' => 79,
            'sensor_days_remaining' => 2,
            'last_telemetry_at' => now(),
        ];
    }
}
