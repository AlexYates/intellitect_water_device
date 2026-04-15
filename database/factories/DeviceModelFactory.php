<?php

// database/factorires/DeviceModelFactory.php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeviceModelFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Using the UI design's text as the default, but allowing overrides
            'name' => 'Intellisonde DI',
            'expected_battery_days' => 100, // Used to calculate progress bar %
            'expected_sensor_days' => 60,   // Used to calculate progress bar %
        ];
    }
}
