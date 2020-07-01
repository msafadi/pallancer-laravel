<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Order;
use App\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = Cart::with('product')->where('user_id', Auth::id())->get();
        return view('checkout', [
            'cart' => $cart,
        ]);
    }

    public function checkout(Request $request)
    {
        $user = $request->user(); // Auth::user()

        DB::beginTransaction();
        try {
            $order = $user->orders()->create([
                'status' => 'pending',
            ]); /*Order::create([
                'user_id' => Auth::id(),
                'status' => 'pending',
            ]);*/

            $cart = Cart::where('user_id', Auth::id())->get();
            foreach ($cart as $item) {
                $order->orderProducts()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
                /*OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);*/
            }

            //Cart::where('user_id', Auth::id())->delete();
            $user->cart()->delete();

            DB::commit();

            return redirect()
                ->route('orders')
                ->with('success', __('Order #:id created', ['id' => $order->id]));

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
