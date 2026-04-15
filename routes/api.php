<?php

use App\Http\Controllers\Api\DeviceController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/devices/{id}/status', [DeviceController::class, 'status']);
    Route::post('/devices/{id}/telemetry', [DeviceController::class, 'telemetryWebhook']);
});
