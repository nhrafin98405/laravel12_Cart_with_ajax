<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Stripe\Stripe;
use Stripe\Charge;

class StripePaymentController extends Controller
{
    /**
     * Stripe Checkout Page
     */
    public function stripe(): View
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return view('stripe', ['total' => 0]);
        }

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('stripe', compact('total'));
    }

    /**
     * Stripe Payment
     */
    public function stripePost(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'stripeToken' => 'required'
        ]);

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/')
                ->with('error', 'Your cart is empty.');
        }

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        try {

            Stripe::setApiKey(env('STRIPE_SECRET'));

            $charge = Charge::create([
                "amount" => $total * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Order Payment",
            ]);

            foreach ($cart as $item) {

              DB::table('orders')->insert([
    'product_id'       => $item['id'],
    'product_name'     => $item['name'],
    'product_quantity' => $item['quantity'],
    'product_price'    => $item['price'],
    'sub_total'        => $item['price'] * $item['quantity'],
]);
            }

            session()->forget('cart');

            return redirect()
                ->route('order')
                ->with('success', 'Payment Successful!');

        } catch (\Exception $e) {

            return back()->with('error', $e->getMessage());
        }
    }
}