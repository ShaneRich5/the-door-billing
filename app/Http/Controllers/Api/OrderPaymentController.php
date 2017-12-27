<?php

namespace App\Http\Controllers\Api;

use Log;
use Braintree_ClientToken;
use Braintree_Transaction;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Controllers\Controller;

class OrderPaymentController extends Controller
{
    public function generatePaymentToken()
    {
        return [
            'nonce' => Braintree_ClientToken::generate()
        ];
    }

    public function pay($id, Request $request)
    {
        $order = Order::findOrFail($id);
        $delivery = $order->delivery;

        $attendance = $delivery->attendance;

        $nonce = $request->input('nonce');

        $menuItemCount = $order->menuItems->count();
        $itemCost = 18.50;
        $subtotal = $itemCost * $attendance;
        $tax = 0.08875;

        $payment = $subtotal + ($subtotal * $tax);

        $payment = ceil($payment * 100) / 100;

        $transaction = [
            'amount' => $payment,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => True
            ],
        ];

        $result = Braintree_Transaction::sale($transaction);

        Log::info($result);


        return [
            'menu_count' => $menuItemCount,
            'item_cost' => $itemCost,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'payment' => $payment,
            'result' => $result,
            'order' => $order->total,
            'nonce' => $nonce,
        ];
    }
}
