<?php

namespace Tests\Unit;

use App\Models\Device;
use App\Services\TelemetryIngestionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class TelemetryIngestionServiceTest extends TestCase
{
    use RefreshDatabase;

    private TelemetryIngestionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TelemetryIngestionService;
    }

    public function test_it_rolls_back_if_ingestion_fails_halfway(): void
    {
        $device = Device::factory()->create([
            'battery_days_remaining' => 100,
            'sensor_days_remaining' => 100,
        ]);

        // We want to force a failure during the transaction.
        // A simple way is to mock the device's relation to throw an exception.
        // Or we can use DB::shouldReceive to mock a failure.

        DB::shouldReceive('transaction')
            ->once()
            ->andThrow(new \Exception('Database failure'));

        try {
            $this->service->processWebhook($device->id, [
                'battery_days' => 50,
                'sensor_days' => 50,
            ]);
        } catch (\Exception $e) {
            $this->assertEquals('Database failure', $e->getMessage());
        }

        // Verify that the device was NOT updated
        $device->refresh();
        $this->assertEquals(100, $device->battery_days_remaining);
        $this->assertEquals(100, $device->sensor_days_remaining);
    }
}
