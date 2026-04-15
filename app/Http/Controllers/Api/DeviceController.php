<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DeviceStatusService;
use App\Services\TelemetryIngestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function __construct(
        private DeviceStatusService $statusService,
        private TelemetryIngestionService $telemetryService
    ) {}

    public function status(Request $request, int $id): JsonResponse
    {
        // Assuming middleware authenticates and provides tenant ID
        $tenantId = $request->user()->tenant_id ?? 1;

        try {
            $data = $this->statusService->getDeviceWidgetData($id, $tenantId);

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Device not found or unauthorised'], 404);
        }
    }

    public function telemetryWebhook(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'battery_days' => 'required|integer',
            'sensor_days' => 'required|integer',
        ]);

        try {
            $this->telemetryService->processWebhook($id, $validated);

            return response()->json(['message' => 'Telemetry processed successfully'], 202);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Device not found or unauthorised'], 404);
        }
    }
}
