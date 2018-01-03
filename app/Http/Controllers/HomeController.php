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
        $data = [
            'menu_items' => [
                [ 'name' => 'Item A' ],
                [ 'name' => 'Item B' ],
            ],
            'invoice_number' => '00001',
            'user' => [
                'first_name' => 'Shane',
                'last_name' => 'Richards',
                'email' => 'shane.richards121@gmail.com',
                'phone' => '347-471-5812'
            ],
            'order' => [
                'id' => 1,
                'user_id' => 1,
                'delivery_id' => 1,
                'billing_address_id' => 1,
                'status' => 'paid',
                'type' => 'delivery',
                'note' => 'Please pack lots of plates',
                'subtotal' => 462.50,
                'tax' => 8.875,
                'total' => 538.54,
            ],
            'billing_address' => [
                'id' => 1,
                'street' => '1A Road',
                'city' => 'Queens',
                'state' => 'New York'
            ],
            'delivery' => [
                'id' => 1,
                'location_id' => 1,
                'attendance' => 10,
                'delivery_by' => '2018/1/20 12:30',
                'cost' => 35.00
            ],
            'delivery_address' => [
                'id' => 2,
                'street' => '2C Baisley Street',
                'city' => 'New York',
                'state' => 'New York'
            ],
            'delivery_location' => [
                'id' => 1,
                'address_id' => 2,
                'owner' => 'Dr. Lowe',
                'name' => "Dr. Lowe's Office",
                'phone' => '555-555-5555'
            ]
        ];
        // return view('pdf.invoice', $data);
        $pdf = PDF::loadView('pdf.invoice', $data);
        return $pdf->stream('invoice.pdf');
    }
}
