<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Product List
     */
    public function index()
    {
        $products = Product::all();

        return view('products', compact('products'));
    }

    /**
     * Cart Page
     */
    public function cart()
    {
        return view('cart');
    }

    /**
     * Add Product To Cart
     */
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {

            $cart[$id]['quantity']++;

        } else {

            $cart[$id] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'quantity' => 1,
                'price'    => $product->price,
                'image'    => $product->image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update Cart
     */
    public function update(Request $request)
    {
        $request->validate([
            'id'       => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {

            $cart[$request->id]['quantity'] = $request->quantity;

            session()->put('cart', $cart);

            session()->flash('success', 'Cart updated successfully.');
        }
    }

    /**
     * Remove Product From Cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->id])) {

            unset($cart[$request->id]);

            session()->put('cart', $cart);
        }

        session()->flash('success', 'Product removed successfully.');
    }

    /**
     * Checkout Page
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {

            return redirect('/')
                ->with('error', 'Your cart is empty.');
        }

        $total = 0;

        foreach ($cart as $item) {

            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout', compact('total'));
    }

    /**
     * Order Success Page
     */
    public function order()
    {
        return view('orderDone');
    }
}