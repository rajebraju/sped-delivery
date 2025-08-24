<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Restaurant
        $restaurant = DB::table('restaurants')->insertGetId([
            'name' => 'Pizza Palace',
            'location' => DB::raw("POINT(0, 0)"),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Delivery Zone (radius)
        DB::table('delivery_zones')->insert([
            'restaurant_id' => $restaurant,
            'type' => 'radius',
            'radius_km' => 5,
            'center' => DB::raw("POINT(0, 0)"),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create Delivery Man
        DB::table('delivery_men')->insert([
            'name' => 'John Doe',
            'location' => DB::raw("POINT(0.01, 0.01)"),
            'is_available' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}