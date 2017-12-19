<?php

namespace App\Http\Controllers\Api;

use JWTAuth;
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

        return [
            'orders' => $orders,
        ];
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
        $order->billing_id = $billingAddress->id;
        $order = $user->orders()->save($order);

        $deliverAddressData = collect($request->only('delivery.street', 'delivery.city', 'delivery.state', 'delivery.postal_code'));
        $deliverAddress = Address::create($deliverAddressData['delivery']);

        $location = new Location($request->only('location.owner', 'location.name', 'location.phone')['location']);
        $location->address_id = $address->id;
        $location->save();

        $delivery = new Delivery($request->only('details.attendance', 'details.cost', 'details.deliver_by')['details']);
        $delivery->order_id = $order->id;
        $delivery->location_id = $location->id;
        $delivery->save();

        return [
            'order' => $order,
            'location' => $location,
            'deliver_address' => $deliverAddress,
            'delivery' => $delivery,
            'billing' => $billingAddress,
        ];

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
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
        $billingData = collect($request->only('billing.street', 'billing.city', 'billing.state', 'billing.postal_code'));

        // Extract nested values and create address
        $billingAddress->save($billingData['billing']);

        // Attach billing address to user
        $user->addresses()->attach($billingAddress->id);

        $locationData = collect($request->only('location.owner', 'location.name', 'location.phone'))['location'];
        $deliverAddressData = collect($request->only('delivery.street', 'delivery.city', 'delivery.state', 'delivery.postal_code'))['delivery'];

        $delivery = $order->delivery;
        $location = $delivery->location;

        if (empty($delivery)) {
            $delivery = new Delivery;
        }

        if (empty($location)) {
            $location = new Location;
        }

        $location = $location->save($locationData);

        $delivery->order_id = $order->id;
        $delivery->location_id = $location->id;
        $delivery->save($deliverAddressData);

        $deliverAddress = $location->address()->save($deliverAddressData);

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
