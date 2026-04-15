<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeviceModel extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     * Empty array means all attributes are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the devices that are of this specific model type.
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }
}
