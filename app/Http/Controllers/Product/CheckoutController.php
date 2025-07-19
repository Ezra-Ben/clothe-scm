<?php

namespace App\Http\Controllers\Product;

use App\Models\Role;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    public function create()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();

        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

        return view('products.checkout.create', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'billing_address' => 'required',
            'billing_city' => 'required',
            'billing_state' => 'required',
            'billing_zip' => 'required',
            'payment_method' => 'required|in:card,momo',
        ]);


        $user = Auth::user();

        $customer = Customer::firstOrNew(['user_id' => $user->id]);
        $customer->phone = $request->phone;
        $customer->billing_address = $request->billing_address;
        $customer->billing_city = $request->billing_city;
        $customer->billing_state = $request->billing_state;
        $customer->billing_zip = $request->billing_zip;

        if ($request->has('same_as_billing')) {
            $customer->shipping_address = $request->billing_address;
            $customer->shipping_city = $request->billing_city;
            $customer->shipping_state = $request->billing_state;
            $customer->shipping_zip = $request->billing_zip;
        } else {
            $customer->shipping_address = $request->shipping_address;
            $customer->shipping_city = $request->shipping_city;
            $customer->shipping_state = $request->shipping_state;
            $customer->shipping_zip = $request->shipping_zip;
        }

        $customer->save();

        if ($user->role_id !== null) {
            $customerRole = Role::where('name', 'customer')->first();
            if ($customerRole && $user->role_id !== $customerRole->id) {
                $user->role_id = $customerRole->id;
                $user->save();
            }
        }

        $cartItems = $user->cartItems()->with('product')->get();

        $subtotal = $cartItems->sum(fn ($item) => $item->product->price * $item->quantity);

        $taxRate = 0.05;
        $tax = $subtotal * $taxRate;

        $shippingFee = 10000;

        $total = $subtotal + $tax + $shippingFee;

        $order = Order::create([
            'user_id' => $user->id,
            'customer_id' => $customer->id,
            'subtotal' => $subtotal,
            'total' => $total,
            'tax' => $tax,
            'shipping' => $shippingFee,
            'status' => 'pending_payment',
            'payment_method' => $request->payment_method, 
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
        }

        $user->cartItems()->delete();

        return redirect()->route('pay', $order->id);
    }
}
