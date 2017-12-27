<?php

namespace App\Http\Controllers\Api;

use Log;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderMenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function index(Order $order)
    {
        return [
            'order' => $order,
            'menu_items' => $order->menuItems,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Order $order)
    {

        $items = $request->only('menu_items');
        $ids = $items['menu_items'];

        Log::info('menu items: ' . implode(', ', $ids));

        $menuItems = MenuItem::find($ids);

        $order->menuItems()->sync($ids);

        return [
            'menu_items' => $order->menuItems()->get(),
            'order' => $order
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order, MenuItem $menuItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @param  \App\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order, MenuItem $menuItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order, MenuItem $menuItem)
    {
        $result = $order->menuItems()->detach($menuItem->id);
        return [
            'order' => $order,
            'menu_item' => $menuItem,
            'result' => $result,
        ];
    }
}
