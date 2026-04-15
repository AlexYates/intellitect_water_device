<?php

namespace Tests\Unit;

use App\Models\Device;
use App\Models\DeviceModel;
use App\Models\Replacement;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Services\DeviceStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeviceStatusServiceTest extends TestCase
{
    use RefreshDatabase;

    private DeviceStatusService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DeviceStatusService;
    }

    public function test_it_correctly_identifies_alert_state_when_battery_is_low(): void
    {
        $tenant = Tenant::factory()->create();
        $device = Device::factory()->create([
            'tenant_id' => $tenant->id,
            'battery_days_remaining' => 5,
            'sensor_days_remaining' => 100,
        ]);

        $status = $this->service->getDeviceWidgetData($device->id, $tenant->id);

        $this->assertTrue($status['status']['has_alert']);
    }

    public function test_it_correctly_identifies_alert_state_when_sensor_is_low(): void
    {
        $tenant = Tenant::factory()->create();
        $device = Device::factory()->create([
            'tenant_id' => $tenant->id,
            'battery_days_remaining' => 100,
            'sensor_days_remaining' => 5,
        ]);

        $status = $this->service->getDeviceWidgetData($device->id, $tenant->id);

        $this->assertTrue($status['status']['has_alert']);
    }

    public function test_it_is_not_in_alert_when_thresholds_are_met(): void
    {
        $tenant = Tenant::factory()->create();
        $device = Device::factory()->create([
            'tenant_id' => $tenant->id,
            'battery_days_remaining' => 6,
            'sensor_days_remaining' => 6,
        ]);

        $status = $this->service->getDeviceWidgetData($device->id, $tenant->id);

        $this->assertFalse($status['status']['has_alert']);
    }

    public function test_it_correctly_identifies_online_status(): void
    {
        $tenant = Tenant::factory()->create();
        $onlineDevice = Device::factory()->create([
            'tenant_id' => $tenant->id,
            'last_telemetry_at' => now(),
        ]);
        $offlineDevice = Device::factory()->create([
            'tenant_id' => $tenant->id,
            'last_telemetry_at' => now()->subHours(25),
        ]);

        $this->assertTrue($this->service->getDeviceWidgetData($onlineDevice->id, $tenant->id)['status']['is_online']);
        $this->assertFalse($this->service->getDeviceWidgetData($offlineDevice->id, $tenant->id)['status']['is_online']);
    }

    public function test_it_returns_full_data_structure(): void
    {
        $tenant = Tenant::factory()->create();
        $model = DeviceModel::factory()->create(['name' => 'Wtr-100']);
        $device = Device::factory()->create([
            'tenant_id' => $tenant->id,
            'device_model_id' => $model->id,
            'name' => 'Kitchen Sink',
            'battery_days_remaining' => 300,
            'sensor_days_remaining' => 150,
        ]);

        Subscription::factory()->create([
            'device_id' => $device->id,
            'plan_name' => 'Premium',
            'is_active' => true,
        ]);

        Replacement::factory()->create([
            'device_id' => $device->id,
            'status' => 'delivered',
            'delivery_date' => now()->subDays(10),
        ]);

        $status = $this->service->getDeviceWidgetData($device->id, $tenant->id);

        $this->assertEquals('Kitchen Sink', $status['name']);
        $this->assertEquals('Wtr-100', $status['model']);
        $this->assertEquals(300, $status['telemetry']['battery_days']);
        $this->assertTrue($status['subscription']['is_active']);
        $this->assertEquals('delivered', $status['replacement']['status']);
    }
}
