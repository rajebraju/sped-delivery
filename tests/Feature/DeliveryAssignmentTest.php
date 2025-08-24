<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\DeliveryZone;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\DeliveryMan;
use Illuminate\Support\Facades\DB;
class DeliveryAssignmentTest extends TestCase
{
    
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_assigns_nearest_delivery_person()
    {
        $user = User::factory()->create();
        $token = $user->createToken('TestToken')->plainTextToken;

        $restaurant = Restaurant::factory()->create();
        $deliveryMan = DeliveryMan::factory()->create([
            'name' => 'Raj',
            'location' => DB::raw("POINT(0.001, 0.001)"), // close
            'is_available' => true
        ]);

        DeliveryZone::create([
            'restaurant_id' => $restaurant->id,
            'type' => 'radius',
            'radius_km' => 10,
            'center_lat' => 0,
            'center_lng' => 0,
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/orders', [
                'restaurant_id' => $restaurant->id,
                'delivery_address_lat' => 0.002,
                'delivery_address_lng' => 0.002,
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('orders', [
            'delivery_man_id' => $deliveryMan->id,
            'status' => 'assigned'
        ]);
    }
}
