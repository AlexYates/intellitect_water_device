<?php

// database/factorires/ReplacementFactory.php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReplacementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'device_id' => Device::factory(),
            'status' => $this->faker->randomElement(['pending', 'shipped', 'delivered']),
            'delivery_date' => $this->faker->dateTimeBetween('now', '+2 weeks')->format('Y-m-d'),
        ];
    }
}
