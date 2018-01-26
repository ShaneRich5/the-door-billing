<?php

namespace App\Http\Controllers\Api;

use Log;
use FCM;
use PDF;
use Setting;
use GoogleCloudPrint;
use App\Models\Order;
use Braintree_ClientToken;
use Braintree_Transaction;
use App\Jobs\PrintInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;


use Mike42\Escpos\Printer;
use Mike42\Escpos\ImagickEscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

class OrderPaymentController extends Controller
{
    public function ethernetTest()
    {
        if ($printer_id = Setting::get('printer_id'))
        {
            $printerIp = $printer_id;
            $printerPort = 9100;

            $connector = new NetworkPrintConnector($printerIp, $printerPort);
            $printer = new Printer($connector);

            try {
                $printer->text("Test\n");
                $printer->text("-----------------------\n");
                $printer->text("Body\n");
                $printer->cut();
            } finally {
                $printer -> close();
            }
            return 'done';
        } else {
            return 'no printer id provided';
        }
    }

    public function test()
    {
        $order =  Order::find(1);

        $printer_id = Setting::get('printer_id');
        Log::info('printer_id ' . $printer_id);

        if ($printer_id)
        {
            PrintInvoice::dispatch($order, $printer_id);
            return 'success';
        } else {
            return 'printer_id not provided';
        }
    }

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

            $delivery = $order->delivery;

            // generate invoice at this point
            $this->generateInvoice($order);
            $this->sendInvoiceToPrinter($order);

            return [
                'menu_items' => $order->menuItems,
                'order' => $order,
                'delivery' => $delivery,
            ];
        } else {
            return [
                'success' => false
            ];
        }
    }

    private function sendInvoiceToPrinter($order) {
        $printer_id = Setting::get('printer_id');
        Log::info('printer_id ' . $printer_id);

        if ($printer_id)
        {
            PrintInvoice::dispatch($order, $printer_id);
            return 'success';
        } else {
            return 'printer_id not provided';
        }
    }

    private function generateInvoice($order)
    {
        // Make this a job
        $user = Auth::guard('api')->user();
        $billingAddress = $order->billing;
        $delivery = $order->delivery;
        $deliveryLocation = $delivery->location;
        $deliveryAddress = $deliveryLocation->address;
        $menuItems = $order->menuItems;

        $data = [
            'user' => $user,
            'order' => $order,
            'menu_items' => $menuItems,
            'billing_address' => $billingAddress,
            'delivery' => $delivery,
            'delivery_location' => $deliveryLocation,
            'delivery_address' => $deliveryAddress,
        ];

        PDF::loadView('pdf.invoice', $data)
            ->save('invoices/' . $order->id . '.pdf');
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
