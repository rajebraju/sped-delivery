<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeliveryMan;
use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryManController extends Controller
{
    public function respondToOrder(Request $request, $id)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'accept' => 'required|boolean'
        ]);

        $deliveryMan = DeliveryMan::findOrFail($id);
        $order = Order::where('delivery_man_id', $id)
                      ->where('status', 'assigned')
                      ->findOrFail($request->order_id);

        if (!$request->accept) {
            $order->update(['delivery_man_id' => null, 'status' => 'pending']);
            (new \App\Services\AssignmentService())->assignToNearest($order);
            return response()->json(['message' => 'Order rejected. Reassigned.']);
        }

        $order->update(['status' => 'accepted']);
        $deliveryMan->update(['is_available' => false]);

        return response()->json(['message' => 'Order accepted!']);
    }
}