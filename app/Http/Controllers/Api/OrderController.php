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

        $orders = $user->orders;

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
        if ( ! $user = Auth::guard('api')->user())
        {
            return response()->json([
                'message' => 'Invalid token provided',
            ], 300);
        }

        $billingData = collect($request->only('billing.street', 'billing.city', 'billing.state', 'billing.postal_code'));

        $billingAddress = Address::create($billingData['billing']);

        $user->addresses()->attach($billingAddress->id);

        $order = new Order($request->only('type', 'status'));
        $order->billing_id = $billingAddress->id;

        $order = $user->orders()->save($order);

        if ($order->type == 'delivery')
        {
            $deliverAddress = collect($request->only('delivery.street', 'delivery.city', 'delivery.state', 'delivery.postal_code'));
            $address = Address::create($deliverAddress['delivery']);

            $location = new Location($request->only('owner', 'name', 'phone'));
            $location->address_id = $address->id;
            $location->save();
            // $location->address()->associate($address);

            $delivery = new Delivery($request->only('attendance'));
            $delivery->order_id = $order->id;
            $delivery->location_id = $location->id;
            $delivery->save();

            return [
                'order' => $order,
                'location' => $location,
                'address' => $address,
                'delivery' => $delivery,
                'billing' => $billingAddress,
            ];
        }

        return [
            'order' => $order,
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
            'count' => $order->menuItems->count(),
            'menu_items' => $order->menuItems,
            'order' => $order,
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
        //
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
