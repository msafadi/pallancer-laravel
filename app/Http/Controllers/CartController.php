<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class CartController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();

        $cart = Cart::with('product')
            ->where('id', $this->getCartId())
            ->when($user_id, function($query, $user_id) {
                $query->where('user_id', $user_id)->orWhereNull('user_id');
            })
            ->get();
        return view('cart', [
            'cart' => $cart,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|int|exists:products,id',
            'quantity' => 'int|min:1',
        ]);
        $product = Product::findOrFail($request->post('product_id'));
        $quantity = $request->post('quantity', 1);

        /*$cart = Cart::where([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ])->first();

        if ($cart) {
            //$cart->increment('quantity', $quantity);
            Cart::where([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ])->increment('quantity', $quantity);

        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'price' => $product->price,
                'quantity' => $quantity,
            ]);
        }*/

        Cart::updateOrCreate([
            'id' => $this->getCartId(),
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ], [
            'price' => $product->price,
            'quantity' => DB::raw("quantity + $quantity"),
        ]);

        return redirect()
            ->route('cart')
            ->with('success', __('Product :name added to cart!', [
                'name' => $product->name,
            ]));
    }

    protected function getCartId()
    {
        $request = request();
        $id = $request->cookie('cart_id');
        if (!$id) {
            $uuid = Uuid::uuid1();
            $id = $uuid->toString();
            Cookie::queue(Cookie::make('cart_id', $id, 43800));
        }
        
        return $id;
    }
}
