<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

// Models
use App\Models\Restaurant;
use App\Models\DeliveryZone;
use Database\Factories\RestaurantFactory;

// For Sanctum
use App\Models\User; // You'll create this user model for API auth
use Illuminate\Support\Facades\Hash;

class OrderPlacementTest extends TestCase
{
    use RefreshDatabase;

    // protected function setUp(): void
    // {
    //     parent::setUp();

    //     // Ensure the route is registered
    //     Route::middleware('auth:sanctum')->group(function () {
    //         Route::post('/api/orders', function () {
    //             return response()->json(['message' => 'OK']);
    //         })->name('orders.store');
    //     });
    // }

    public function test_order_rejected_outside_zone()
    {
        //  Create a user and authenticate
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $token = $user->createToken('TestToken')->plainTextToken;

        // Create a restaurant
        $restaurant = RestaurantFactory::new()->create();

        //  Define a small radius zone (3 km) around (0, 0)
        DeliveryZone::create([
            'restaurant_id' => $restaurant->id,
            'type' => 'radius',
            'radius_km' => 3,
            'center_lat' => 0,
            'center_lng' => 0,
        ]);

        // Send request with Authorization header
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson('/api/orders', [
            'restaurant_id' => $restaurant->id,
            'delivery_address_lat' => 2,
            'delivery_address_lng' => 2,
        ]);

        // Assert: Should return 400 (not 401 anymore)
        $response->assertStatus(400);
        $response->assertJsonFragment([
            'message' => 'Delivery address is outside the service area.'
        ]);
    }
}