<?php

namespace App\Http\Controllers\Order;

use App\Models\Order;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if (!$user) {  
            return redirect()->route('login')->with('error', 'Please log in to view your orders.');
        }

        if (!$user->hasRole('customer')) {  
            return redirect()->route('home')->with('error', 'Access denied. Customer role required.');
        }

        $customer = $user->customer;
        
        if (!$customer) {  
            return redirect()->route('home')->with('error', 'No customer profile found.');
        }

        $orders = $customer->orders()->with('fulfillment')->withCount('items')->latest()->get();
        
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        //Eager load
         $order->load([
            'items.product', 
            'customer',
            'fulfillment.updatedBy'
        ]);

        return view('orders.show', compact('order'));
    }
}
