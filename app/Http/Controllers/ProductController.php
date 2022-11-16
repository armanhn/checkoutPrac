<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();
        return view('product.index', compact('products'));
    }

    public function checkout()
    {

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $products = Product::all();
        $total_price = 0;
        $lineItems = [];
        foreach ($products as $product) {
            $total_price += $product->price;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->name,
                        'images' => [$product->image]

                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => 1,
            ];
        }

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel', [], true),
        ]);

        $order = new Order();
        $order->status = 'unpaid';
        $order->total_price = $total_price;
        $order->session_id = $checkout_session->id;
        $order->save();

        return redirect($checkout_session->url);
    }

    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $sessionId = $request->get('session_id');

        try {
            $session = \Stripe\Checkout\Session::retrieve($request->get('session_id'));
            if (!$session) {
                throw new NotFoundHttpException;
            }
            $customer = \Stripe\Customer::retrieve($session->customer);

            $order = Order::where('session_id', $session->id)->first();

            if (!$order) {
                throw new NotFoundHttpException;
            }
            if ($order->status === 'unpaid') {
                $order->status = 'paid';
                $order->save();
            }

            return view('product.success', compact('customer'));
        } catch (\Exception $e) {
            throw new NotFoundHttpException;
        }
    }

    public function cancel()
    {
    }


    public function webhook()
    {


        // This is your Stripe CLI webhook secret for testing your endpoint locally.
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response('', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response('', 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $session = $event->data->object;
                $sessionId = $session->id;
                $order = Order::where('session_id', $session->id)->first();

                if ($order && $order->status === 'unpaid') {
                    $order->status = 'paid';
                    $order->save();
                    //send email to customer
                };

            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('');
    }
}
