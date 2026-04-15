<?php

// database/factorires/SubscriptionFactory.php

namespace Database\Factories;

use App\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'device_id' => Device::factory(),
            'plan_name' => $this->faker->randomElement(['SondeCare+', 'SondeCare Basic', 'SondeCare Pro']),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }
}
