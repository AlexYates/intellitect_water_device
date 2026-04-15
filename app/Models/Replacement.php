<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Replacement extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'delivery_date' => 'date',
    ];

    /**
     * The device this replacement order belongs to.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
