<?php

namespace App\Http\Controllers\Api;

use Log;
use PDF;
use JWTAuth;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Address;
use App\Models\Location;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    protected $order;

    public function __contruct(Order $order)
    {
        $this->middleware('auth:api');
        $this->middleware('jwt.auth');

        $this->order = $order;
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = $this->guard()->user();
        // $token = JWTAuth::fromUser($user);

        $orders = $user->orders->map(function($order) {
            return [
                'order' => $order,
                'delivery' => $order->delivery,
            ];
        });

        return $orders;
    }

    public function invoice($id)
    {
        return response()->file('invoices/' . $id . '.pdf');
        // $user = Auth::guard('api')->user();
        // $order = Order::find($id);
        // $billingAddress = $order->billing;
        // $delivery = $order->delivery;
        // $deliveryLocation = $delivery->location;
        // $deliveryAddress = $deliveryLocation->address;
        // $menuItems = $order->menuItems;

        // $invoiceNumber = (string) $order->id;

        // $data = [
        //     'invoice_number' => $invoiceNumber,
        //     'user' => $user,
        //     'order' => $order,
        //     'menu_items' => $menuItems,
        //     'billing_address' => $billingAddress,
        //     'delivery' => $delivery,
        //     'delivery_location' => $deliveryLocation,
        //     'delivery_address' => $deliveryAddress,
        // ];

        // return PDF::loadView('pdf.invoice', $data)
        //     ->save('invoices/' . $order->id . '.pdf')
        //     ->stream($order->id . '.pdf');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::guard('api')->user();

        // Get nested billing address
        $billingData = collect($request->only('billing.street', 'billing.city', 'billing.state', 'billing.postal_code'));

        // Extract nested values and create address
        $billingAddress = Address::create($billingData['billing']);

        // Attach billing address to user
        $user->addresses()->attach($billingAddress->id);

        // Create order with defaults
        $order = new Order(['type' => 'delivery', 'status' => 'draft']);
        $order->billing_address_id = $billingAddress->id;
        $order = $user->orders()->save($order);

        $deliverAddressData = collect($request->only('delivery.street', 'delivery.city', 'delivery.state', 'delivery.postal_code'));
        $deliverAddress = Address::create($deliverAddressData['delivery']);

        $location = new Location($request->only('location.owner', 'location.name', 'location.phone')['location']);
        $location->address_id = $deliverAddress->id;
        $location->save();

        $deliveryOptions = $request->only('details.attendance', 'details.cost', 'details.deliver_by')['details'];

        $attendance = $deliveryOptions['attendance'];

        $order->subtotal = 18.50 * $attendance;
        $order->tax = $order->subtotal * 8.875 / 100;
        $order->total = $order->subtotal + $order->tax + $deliveryOptions['cost'];
        $order->save();

        Log::info('user deliver_by before formatting: ' . $deliveryOptions['deliver_by']);

        $deliveryOptions['deliver_by'] = Carbon::parse($deliveryOptions['deliver_by'])->toDateTimeString();

        Log::info('user deliver_by after formatting: ' . $deliveryOptions['deliver_by']);

        $delivery = new Delivery($deliveryOptions);
        $delivery->order_id = $order->id;
        $delivery->location_id = $location->id;
        $delivery->save();

        $data = [
            'order' => $order,
            'location' => $location,
            'deliver_address' => $deliverAddress,
            'delivery' => $delivery,
            'billing' => $billingAddress
        ];



        return $data;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $delivery = $order->delivery;

        return [
            'menu_items' => $order->menuItems,
            'order' => $order,
            'delivery' => $delivery,
        ];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $user = Auth::guard('api')->user();

        $billingAddress = $order->billing;

        if (empty($billingAddress)) {
            $billingAddress = $order->billing()->create(new Address);
        }

        // Get nested billing address
        $billingData = $request->only('billing.street', 'billing.city', 'billing.state', 'billing.postal_code');

        // Extract nested values and create address
        $billingAddress =  $billingAddress->update($billingData['billing']);

        // Attach billing address to user
        // $user->addresses()->attach($billingAddress->id);

        $locationData = collect($request->only('location.owner', 'location.name', 'location.phone'))['location'];
        $deliverAddressData = collect($request->only('delivery.street', 'delivery.city', 'delivery.state', 'delivery.postal_code'))['delivery'];

        $delivery = $order->delivery;
        $location = $delivery->location->first();

        if (empty($delivery)) {
            $delivery = new Delivery;
        }

        $location->update($locationData);

        if (empty($location)) {
            $location = Location::create($locationData);
        } else {
            $location->fill($locationData)->save();
        }
        $delivery->order_id = $order->id;
        $delivery->location_id = $location->id;
        $delivery->save($deliverAddressData);

        $deliverAddress = $location->address()->update($deliverAddressData);

        return [
            'order' => $order,
            'location' => $location,
            'deliver_address' => $deliverAddress,
            'delivery' => $delivery,
            'billing' => $billingAddress,
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
