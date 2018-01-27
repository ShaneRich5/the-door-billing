<?php

namespace App\Http\Controllers\Api;

use Log;
use PDF;
use JWTAuth;
use Setting;
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

        $delivery_cost = (float) Setting::get('delivery_cost', '35.00');
        $per_person_cost = (float) Setting::get('per_person_regular_cost', '18.50');
        $tax = (float) Setting::get('tax', '8.875');

        $order->subtotal = $per_person_cost * $attendance;
        $order->tax = $order->subtotal * $tax / 100;
        $order->total = $order->subtotal + $order->tax + $delivery_cost;
        $order->save();


        $deliveryOptions['deliver_by'] = Carbon::parse($deliveryOptions['deliver_by'])->toDateTimeString();

        $delivery = new Delivery($deliveryOptions);
        $delivery->cost = $delivery_cost;
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


        // Get nested data
        $billingData = $request->only('billing.street', 'billing.city', 'billing.state', 'billing.postal_code')['billing'];
        $locationData = collect($request->only('location.owner', 'location.name', 'location.phone'))['location'];
        $deliverAddressData = collect($request->only('delivery.street', 'delivery.city', 'delivery.state', 'delivery.postal_code'))['delivery'];
        $deliveryOptions = $request->only('details.attendance', 'details.cost', 'details.deliver_by')['details'];

        $billingAddress = $order->billing;

        if (empty($billingAddress)) {
            $billingAddress = $order->billing()->create(new Address);
        }

        // Extract nested values and create address
        $billingAddress =  $billingAddress->update($billingData);
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

        $deliverAddress = $location->address()->update($deliverAddressData);

        // recalculate delivery and order costs

        $attendance = $deliveryOptions['attendance'];

        $delivery_cost = (float) Setting::get('delivery_cost', '35.00');
        $per_person_cost = (float) Setting::get('per_person_regular_cost', '18.50');
        $tax = (float) Setting::get('tax', '8.875');

        $order->subtotal = $per_person_cost * $attendance;
        $order->tax = $order->subtotal * $tax / 100;
        $order->total = $order->subtotal + $order->tax + $delivery_cost;
        $order->save();

        $delivery->attendance = $deliveryOptions['attendance'];
        $delivery->deliver_by = Carbon::parse($deliveryOptions['deliver_by'])->toDateTimeString();
        $delivery->cost = $delivery_cost;
        $delivery->order_id = $order->id;
        $delivery->location_id = $location->id;
        $delivery->save();


        return [
            'order' => $order,
            'delivery' => $delivery,
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
