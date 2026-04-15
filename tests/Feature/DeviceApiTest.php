<?php

// tests/Feature/DeviceApiTest.php

use App\Events\TelemetryUpdated;
use App\Models\Device;
use App\Models\Tenant;
use Illuminate\Support\Facades\Event;

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

beforeEach(function () {
    $this->tenant = Tenant::factory()->create();
});

it('returns correctly structured device status data', function () {
    $device = Device::factory()->create([
        'tenant_id' => $this->tenant->id,
        'battery_days_remaining' => 79,
        'sensor_days_remaining' => 2,
    ]);

    $response = getJson("/api/v1/devices/{$device->id}/status");

    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                'id', 'name', 'model',
                'status' => ['has_alert', 'is_online'],
                'telemetry' => ['battery_days', 'sensor_days'],
                'subscription', 'replacement',
            ],
        ])
        ->assertJsonPath('data.status.has_alert', true)
        ->assertJsonPath('data.telemetry.battery_days', 79);
});

it('returns 404 when requesting a non-existent device', function () {
    getJson('/api/v1/devices/999/status')
        ->assertNotFound()
        ->assertJson(['error' => 'Device not found or unauthorised']);
});

it('prevents a tenant from accessing another tenant\'s device', function () {
    $otherTenant = Tenant::factory()->create();
    $otherDevice = Device::factory()->create(['tenant_id' => $otherTenant->id]);

    // Current acting tenant (id: 1) attempts to access otherTenant's device
    getJson("/api/v1/devices/{$otherDevice->id}/status")
        ->assertNotFound()
        ->assertJson(['error' => 'Device not found or unauthorised']);
});

it('processes telemetry webhooks, updates the device, and broadcasts event', function () {
    Event::fake();

    $device = Device::factory()->create(['tenant_id' => $this->tenant->id]);

    $payload = [
        'battery_days' => 78,
        'sensor_days' => 1,
    ];

    $response = postJson("/api/v1/devices/{$device->id}/telemetry", $payload);

    $response->assertAccepted();

    $this->assertDatabaseHas('devices', [
        'id' => $device->id,
        'battery_days_remaining' => 78,
        'sensor_days_remaining' => 1,
    ]);

    $this->assertDatabaseHas('telemetry_logs', [
        'device_id' => $device->id,
        'battery_days_remaining' => 78,
        'sensor_days_remaining' => 1,
    ]);

    Event::assertDispatched(TelemetryUpdated::class, function ($event) use ($device) {
        return $event->device->id === $device->id
            && $event->device->battery_days_remaining === 78
            && $event->device->sensor_days_remaining === 1;
    });
});

it('returns 404 when sending telemetry to a non-existent device', function () {
    postJson('/api/v1/devices/999/telemetry', [
        'battery_days' => 50,
        'sensor_days' => 50,
    ])
        ->assertNotFound()
        ->assertJson(['error' => 'Device not found or unauthorised']);
});

it('validates the telemetry webhook payload', function ($payload, $errors) {
    $device = Device::factory()->create(['tenant_id' => $this->tenant->id]);

    postJson("/api/v1/devices/{$device->id}/telemetry", $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors($errors);
})->with([
    'missing battery_days' => [['sensor_days' => 1], ['battery_days']],
    'missing sensor_days' => [['battery_days' => 78], ['sensor_days']],
    'non-integer battery_days' => [['battery_days' => 'string', 'sensor_days' => 1], ['battery_days']],
    'non-integer sensor_days' => [['battery_days' => 78, 'sensor_days' => 'string'], ['sensor_days']],
]);
