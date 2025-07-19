<?php

namespace App\Http\Controllers\Order;

use App\Models\Order;
use App\Models\OrderFulfillment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\OrderProcessingService;


class PaymentController extends Controller
{
    public function redirectToGateway(Order $order)
    {
        $txRef = uniqid('tx_');

        // Saving the tx_ref to the order
        $order->tx_ref = $txRef;
        $order->save();

        $paymentData = [
            'tx_ref' => $txRef,
            'amount' => $order->total,
            'currency' => 'UGX',
            'redirect_url' => route('payment.callback'),
            'payment_options' => 'card,mobilemoneyuganda',
            'customer' => [
                'email' => $order->user->email,
                'name' => $order->user->name,
            ],
            'customizations' => [
                'title' => 'J-Clothes Store',
                'description' => 'Payment for Order #' . $order->id,
            ],
        ];

        $response = Http::withToken(env('FLW_SECRET_KEY'))
            ->post('https://api.flutterwave.com/v3/payments', $paymentData);

        $res = $response->json();

        if (isset($res['data']['link'])) {
            return redirect($res['data']['link']);
        } else {
            return back()->with('error', 'Payment link could not be created.');
        }
    }

    public function handleCallback(Request $request)
    {
        $transactionId = $request->get('transaction_id');

        if (!$transactionId) {
            return redirect()->route('home')->with('error', 'No transaction ID.');
        }

        $response = Http::withToken(env('FLW_SECRET_KEY'))
            ->get("https://api.flutterwave.com/v3/transactions/{$transactionId}/verify");

        $res = $response->json();

        if ($res['status'] === 'success' && $res['data']['status'] === 'successful') {
            $txRef = $res['data']['tx_ref'];

            $order = Order::where('tx_ref', $txRef)->first();

            if ($order) {
                
                if ($order->status === 'paid') {
                    return redirect()->route('orders.show', $order->id)
                            ->with('success', 'Payment already processed!');
                }

                $order->status = 'paid';
                $order->save();
               
                app(OrderProcessingService::class)->processPaidOrder($order);

                return redirect()->route('orders.show', $order->id)
                    ->with('success', 'Payment successful!');
            } else {
                return redirect()->route('home')->with('error', 'Order not found.');
            }

        } else {
            $order->status = 'payment failed';
            $order->save();
            return redirect()->route('home')->with('error', 'Payment failed or could not be verified.');
        }
    }
}
