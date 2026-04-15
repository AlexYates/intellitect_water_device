<?php

use App\Models\Device;
use App\Services\DeviceStatusService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function (DeviceStatusService $statusService) {
    // Pick first device for the demo
    $device = Device::first();

    $data = $device ? $statusService->getDeviceWidgetData($device->id, $device->tenant_id) : null;

    return Inertia::render('welcome', [
        'deviceData' => $data,
    ]);
})->name('home');
