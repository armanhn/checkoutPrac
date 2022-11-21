<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEnrollmentRequest;
use App\Models\Enrollment;
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
    public function collect_data(Request $request)
    {
        $product_id = $request->input('product_id');
        return view('product.data', compact('product_id'));
    }
    public function checkout(CreateEnrollmentRequest $request)
    {
        Enrollment::insert($request->validated());
        $product_id = $request->input('product_id');
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $product = Product::where('id', $product_id)->first();
        $total_price = $product->price;
        $lineItem = [];
        $lineItem[] = [
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $product->name,
                ],
                'unit_amount' => $product->price * 100,
            ],
            'quantity' => 1,
        ];


        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItem,
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
