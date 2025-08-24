<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class RestaurantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'location' => DB::raw("POINT({$this->faker->longitude}, {$this->faker->latitude})"),
        ];
    }
}