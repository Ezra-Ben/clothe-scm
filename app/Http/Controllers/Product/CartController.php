<?php

namespace App\Http\Controllers\Product;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->cartItems()->with('product')->get();

        return view('products.cart.index', compact('cartItems'));
    }

    public function add(Request $request, Product $product)
    {
        $cartItem = CartItem::firstOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $product->id],
            ['quantity' => 0]
        );

        $cartItem->increment('quantity');

        return redirect()->route('home')->with('success', 'Added to cart!');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Cart updated!');
    }

    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();

        return back()->with('success', 'Item removed!');
    }
}
