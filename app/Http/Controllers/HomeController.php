<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth')->except('test');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function test()
    {
        // Make this a job
        $user = \App\Models\User::first();
        $order = \App\Models\Order::first();
        $billingAddress = $order->billing;
        $delivery = $order->delivery;
        $deliveryLocation = $delivery->location;
        $deliveryAddress = $deliveryLocation->address;
        $menuItems = $order->menuItems;

        return \Carbon\Carbon::parse($delivery['delivery_by'])->format('M j, Y g:ia e');
        // return $delivery->deliver_by;
        // return \Carbon\Carbon::parse($delivery['delivery_by'])->setTimezone('America/New_York')->format('M j, Y g:ia e');

        $data = [
            'user' => $user,
            'order' => $order,
            'menu_items' => $menuItems,
            'billing_address' => $billingAddress,
            'delivery' => $delivery,
            'delivery_location' => $deliveryLocation,
            'delivery_address' => $deliveryAddress,
        ];

        // return view('pdf.invoice', $data);
        $pdf = PDF::loadView('pdf.invoice', $data);
        return $pdf->stream('invoice.pdf');
    }
}
