<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Order; 
use App\Services\AssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'delivery_address_lat' => 'required|numeric',
            'delivery_address_lng' => 'required|numeric',
        ]);

        $restaurant = Restaurant::findOrFail($request->restaurant_id);

        $lat = $request->delivery_address_lat;
        $lng = $request->delivery_address_lng;

        $zones = DB::table('delivery_zones')
            ->where('restaurant_id', $restaurant->id)
            ->get();

        // ✅ Debug: Check zones
        \Log::info("🔍 Checking zones for restaurant #{$restaurant->id}: " . $zones->count() . " zone(s)");
        foreach ($zones as $zone) {
            \Log::info("📍 Zone: type={$zone->type}, radius={$zone->radius_km}, center=({$zone->center_lat}, {$zone->center_lng})");
        }

        $isValid = false;

        foreach ($zones as $zone) {
            if ($zone->type === 'radius' && $zone->radius_km) {
                try {
                    $distanceInKm = DB::selectOne("
                        SELECT ST_Distance_Sphere(POINT(?, ?), POINT(?, ?)) / 1000 as distance
                    ", [
                        $zone->center_lng ?? 0,
                        $zone->center_lat ?? 0,
                        $lng,
                        $lat
                    ])->distance;

                    \Log::info("📏 Distance: {$distanceInKm} km | Radius: {$zone->radius_km} km");

                    if ($distanceInKm <= $zone->radius_km) {
                        $isValid = true;
                        break;
                    }
                } catch (\Exception $e) {
                    \Log::error("❌ Distance calc failed: " . $e->getMessage());
                }
            }
        }

        if (!$isValid) {
            return response()->json([
                'message' => 'Delivery address is outside the service area.'
            ], 400);
        }

        // ✅ Create order
        $orderId = DB::table('orders')->insertGetId([
            'restaurant_id' => $restaurant->id,
            'delivery_address' => DB::raw("POINT($lng, $lat)"),
            'delivery_lat' => $lat,
            'delivery_lng' => $lng,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //  Load order and assign delivery person
        $order = Order::findOrFail($orderId);
        (new AssignmentService())->assignToNearest($order);

        return response()->json([
            'message' => 'Order placed successfully.',
            'order_id' => $orderId
        ], 201);
    }
}