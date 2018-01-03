<?php

namespace App\Http\Controllers\Api;

use Log;
use FCM;
use App\Models\Order;
use Braintree_ClientToken;
use Braintree_Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class OrderPaymentController extends Controller
{
    public function generatePaymentToken()
    {
        return [
            'nonce' => Braintree_ClientToken::generate()
        ];
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    protected function guard()
    {
        return Auth::guard('api');
    }

    public function pay($id, Request $request)
    {
        $user = Auth::guard('api')->user();

        $order = Order::findOrFail($id);

        if ($order->status === 'paid') {
            return [
                'success' => 'order has already been paid for'
            ];
        }

        $delivery = $order->delivery;

        $attendance = $delivery->attendance;

        $nonce = $request->input('nonce');

        $transaction = [
            'amount' => $order->total,
            'paymentMethodNonce' => $nonce,
            'options' => [
                'submitForSettlement' => True
            ],
        ];

        $result = Braintree_Transaction::sale($transaction);

        Log::info('braintree result ' . $result);

        if ($result->success) {
            $order->status = 'paid';
            $order->save();

            // $this->notifyUser($order, $user);

            return [
                'success' => true
            ];
        } else {
            return [
                'success' => false
            ];
        }
    }

    public function notifyUser($order, $user)
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('my title');
        $notificationBuilder->setBody('Hello world')
                            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = "a_registration_from_your_database";

        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        //return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete();

        //return Array (key : oldToken, value : new token - you must change the token in your database )
        $downstreamResponse->tokensToModify();

        //return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();

        // return Array (key:token, value:errror) - in production you should remove from your database the tokens
    }
}
