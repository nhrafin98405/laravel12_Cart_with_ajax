<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index()

    {

        $products = Product::all();

        return view('products', compact('products'));
    }




    public function cart()

    {

        return view('cart');
    }




    public function addToCart($id)

    {

        $product = Product::findOrFail($id);



        $cart = session()->get('cart', []);



        if (isset($cart[$id])) {

            $cart[$id]['quantity']++;
        } else {

            $cart[$id] = [

                "id" => $product->id,

                "name" => $product->name,

                "quantity" => 1,

                "price" => $product->price,

                "image" => $product->image

            ];
        }



        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }


    public function update(Request $request)

    {

        if ($request->id && $request->quantity) {

            $cart = session()->get('cart');

            $cart[$request->id]["quantity"] = $request->quantity;

            session()->put('cart', $cart);

            session()->flash('success', 'Cart updated successfully');
        }
    }




    public function remove(Request $request)

    {

        if ($request->id) {

            $cart = session()->get('cart');

            if (isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }

            session()->flash('success', 'Product removed successfully');
        }
    }

    public function checkout()
    {




        return view('checkout');
    }

    public function order()
    {

        $carts = session()->get('cart');

        // dd($carts);

        foreach ($carts as $cart) {

            DB::table('orders')->insert([
                'product_id' => $cart['id'],
                'product_name' => $cart['name'],
                'product_quantity' => $cart['quantity'],
                'product_price' => $cart['price'],
                'sub_total' => $cart['quantity'] * $cart['price'],
            ]);
        }






        return view('orderDone');
    }
}
