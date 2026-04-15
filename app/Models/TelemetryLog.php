<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TelemetryLog extends Model
{
    // Disable default timestamps since we are using 'recorded_at' specifically
    public $timestamps = false;

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    /**
     * The device that generated this telemetry log.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
