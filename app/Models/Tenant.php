<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
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
     * Get the physical locations belonging to the tenant.
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    /**
     * Get all devices owned by the tenant.
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }
}
