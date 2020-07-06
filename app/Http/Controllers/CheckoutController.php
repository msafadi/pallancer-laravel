<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Events\OrderCompleted;
use App\Events\OrderCreated;
use App\Notifications\OrderCreatedNotification;
use App\Order;
use App\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Throwable;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
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
            $total = 0;
            foreach ($cart as $item) {
                $order->orderProducts()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
                $total += $item->quantity * $item->price;
                /*OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);*/
            }

            //Cart::where('user_id', Auth::id())->delete();
            $user->cart()->delete();
            Cookie::queue(Cookie::make('cart_id', '', -60));

            DB::commit();

            $user->notify(new OrderCreatedNotification($order));

            event(new OrderCreated($order)); // Trigger for event OrderCreated

            //return $this->paypal($order, $total);

            return redirect()
                    ->route('orders')
                    ->with('success', __('Order #:id created and completed', ['id' => $order->id]));

        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    protected function paypal(Order $order, $total)
    {
        $client = $this->payaplClient();

        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => $order->id,
                "amount" => [
                    "value" => $total,
                    "currency_code" => "USD"
                ]
            ]],
            "application_context" => [
                 "cancel_url" => url(route('paypal.cancel')),
                 "return_url" => url(route('paypal.return'))
            ] 
        ];

        try {
            $response = $client->execute($request);
            if ($response->statusCode == 201) {
                session()->put('paypal_order_id', $response->result->id);
                session()->put('order_id', $order->id);
                foreach ($response->result->links as $link) {
                    if ($link->rel == 'approve') {
                        return redirect()->away($link->href);
                    }
                }
            }

        } catch (Throwable $e) {
            return $e->getMessage();
        }

        return 'Unknown Error! ' . $response->statusCode;

    }

    protected function payaplClient()
    {
        $config = config('services.paypal');
        $env = new SandboxEnvironment($config['client_id'], $config['client_secret']);
        $client = new PayPalHttpClient($env);

        return $client;
    }

    public function paypalReturn()
    {
        $paypal_order_id = session()->get('paypal_order_id');
        $request = new OrdersCaptureRequest($paypal_order_id);
        $request->prefer('return=representation');
        try {
            $response = $this->payaplClient()->execute($request);
            //dd($response);
            if ($response->statusCode == 201) {
                if (strtoupper($response->result->status) == 'COMPLETED') {
                    $id = session()->get('order_id');
                    $order = Order::findOrFail($id);
                    $order->status = 'completed';
                    $order->save();

                    session()->forget(['order_id', 'paypal_order_id']);
                    
                    event(new OrderCompleted());

                    return redirect()
                        ->route('orders')
                        ->with('success', __('Order #:id created and completed', ['id' => $order->id]));
                }
            }
        } catch (Throwable $e) {
            return $e->getMessage();
        }

    }

    public function paypalCancel()
    {
        $id = session()->get('order_id');
        $order = Order::findOrFail($id);
        $order->status = 'canceled';
        $order->save();

        return redirect()
                ->route('orders')
                ->with('success', __('Order #:id created and pending payment', ['id' => $order->id]));
    }
}
