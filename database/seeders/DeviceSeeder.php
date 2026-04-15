<?php

namespace Database\Seeders;

use App\Models\Device;
use App\Models\DeviceModel;
use App\Models\Location;
use App\Models\Replacement;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::factory()->create(['name' => 'City Water Corp']);
        $model = DeviceModel::factory()->create([
            'name' => 'Intellisonde DI',
            'expected_battery_days' => 365,
            'expected_sensor_days' => 180,
        ]);

        $loc1 = Location::factory()->create(['tenant_id' => $tenant->id, 'name' => 'Rue de la République']);
        $loc2 = Location::factory()->create(['tenant_id' => $tenant->id, 'name' => 'Avenue du Président René Coty']);
        $loc3 = Location::factory()->create(['tenant_id' => $tenant->id, 'name' => 'Boulevard Victor Hugo']);
        $loc4 = Location::factory()->create(['tenant_id' => $tenant->id, 'name' => 'Place du Maréchal Foch']);

        // State 1: Healthy/Subscribed
        $d1 = Device::factory()->create([
            'tenant_id' => $tenant->id,
            'location_id' => $loc1->id,
            'device_model_id' => $model->id,
            'name' => 'Rue de la République',
            'battery_days_remaining' => 300,
            'sensor_days_remaining' => 150,
        ]);
        Subscription::factory()->create(['device_id' => $d1->id, 'plan_name' => 'SondeCare+', 'is_active' => true]);
        Replacement::factory()->create(['device_id' => $d1->id, 'status' => 'delivered', 'delivery_date' => '2026-01-10']);

        // State 2: Alert/Subscribed (matches the design)
        $d2 = Device::factory()->create([
            'tenant_id' => $tenant->id,
            'location_id' => $loc2->id,
            'device_model_id' => $model->id,
            'name' => 'Avenue du Président René Coty',
            'battery_days_remaining' => 79,
            'sensor_days_remaining' => 2,
        ]);
        Subscription::factory()->create(['device_id' => $d2->id, 'plan_name' => 'SondeCare+', 'is_active' => true]);
        Replacement::factory()->create(['device_id' => $d2->id, 'status' => 'delivered', 'delivery_date' => '2026-03-04']);

        // State 3: Healthy/Not Subscribed
        $d3 = Device::factory()->create([
            'tenant_id' => $tenant->id,
            'location_id' => $loc3->id,
            'device_model_id' => $model->id,
            'name' => 'Boulevard Victor Hugo',
            'battery_days_remaining' => 300,
            'sensor_days_remaining' => 150,
        ]);
        Subscription::factory()->create(['device_id' => $d3->id, 'plan_name' => 'SondeCare+', 'is_active' => false]);
        Replacement::factory()->create(['device_id' => $d3->id, 'status' => 'pending', 'delivery_date' => null]);

        // State 4: Alert/Not Subscribed
        $d4 = Device::factory()->create([
            'tenant_id' => $tenant->id,
            'location_id' => $loc4->id,
            'device_model_id' => $model->id,
            'name' => 'Place du Maréchal Foch',
            'battery_days_remaining' => 79,
            'sensor_days_remaining' => 2,
        ]);
        Subscription::factory()->create(['device_id' => $d4->id, 'plan_name' => 'SondeCare+', 'is_active' => false]);
        Replacement::factory()->create(['device_id' => $d4->id, 'status' => 'pending', 'delivery_date' => null]);
    }
}
