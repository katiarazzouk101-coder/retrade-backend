<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $user = auth()->user();

        $cart = $user->cart()->firstOrCreate([]);

        $item = $cart->items()->where('product_id', $request->product_id)->first();

        if ($item) {
            $item->increment('quantity', $request->quantity);   
        } else {
            $item = $cart->items()->create([
                'product_id' => $request->product_id,
                'quantity'   => $request->quantity,
            ]);
        }

        return response()->json(['message' => 'Added to cart', 'item' => $item]);
    }

    public function viewCart()
    {
        $cart = auth()->user()->cart()->with('items.product')->first();

        return response()->json([
            'cart' => $cart
        ]);
    }
    public function removeFromCart($productId)
    {
        $cart = auth()->user()->cart;

        $cart->items()->where('product_id', $productId)->delete();

        return response()->json(['message' => 'Removed from cart']);
    }
}
