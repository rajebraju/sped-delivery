<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class DeliveryManFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'location' => DB::raw("POINT({$this->faker->longitude}, {$this->faker->latitude})"),
            'is_available' => true,
        ];
    }
}