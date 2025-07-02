<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Delivery;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $customerIds = Customer::where('user_id', $user->id)->pluck('id');

        $orderIds = Order::whereIn('customer_id', $customerIds)->pluck('id');

        $deliveries = Delivery::with(['carrier'])
                        ->whereIn('order_id', $orderIds)
                        ->orderByDesc('created_at')
                        ->paginate(10);

        return view('distributionandlogistics.users.dashboard', compact('deliveries'));
    }

    // You may remove this method if it's redundant
    public function deliveries()
    {
        return $this->index(); // Optional redirect to avoid duplication
    }
}
