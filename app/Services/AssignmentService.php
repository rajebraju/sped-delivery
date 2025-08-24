<?php

namespace App\Services;

use App\Models\Order;
use App\Models\DeliveryMan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignmentService
{
    public function assignToNearest(Order $order): ?DeliveryMan
    {
        $maxRadiusMeters = 5000; // 5 km

        // Use simple decimal fields instead of spatial blob
        $lat = $order->delivery_lat;
        $lng = $order->delivery_lng;

        if (!$lat || !$lng) {
            Log::error("Order {$order->id} has no delivery coordinates");
            return null;
        }

        // Find nearest available delivery man
        $deliveryMan = DeliveryMan::selectRaw(
            '*, ST_Distance_Sphere(location, POINT(?, ?)) as distance',
            [$lng, $lat]  // MySQL: POINT(lng, lat)
        )
        ->where('is_available', true)
        ->having('distance', '<=', $maxRadiusMeters)
        ->orderBy('distance')
        ->first();

        if (!$deliveryMan) {
            Log::info("No delivery person found within 5 km for order {$order->id}");
            return null;
        }

        // Assign
        $order->update([
            'delivery_man_id' => $deliveryMan->id,
            'status' => 'assigned'
        ]);

        $this->notifyDeliveryMan($deliveryMan, $order);

        return $deliveryMan;
    }

    private function notifyDeliveryMan(DeliveryMan $man, Order $order)
    {
        Log::info("NOTIFICATION: Delivery person #{$man->id}, order #{$order->id} is assigned.");
        // In real app: Push notification via FCM or Pusher
    }
}