<?php

namespace App\Http\Controllers\Api;

use Log;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Tag;
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

    public function test()
    {
        // limits the quantity of menu items available on each item
        // based on the catering limit stipulated

        // $categories = Category::all();

        // $category_limits = $categories->mapWithKeys(function($category) {
        //     return [$category->name => $category->catering_limit];
        // });

        // return MenuItem::all()
        // ->map(function($item) {
        //     return $item->category;
        // })
        // ->flatten()
        // ->groupBy('name')
        // ->map(function($item, $key) {
        //     return collect($item)->count();
        // })->map(function($amount, $key) use ($category_limits) {
        //     $limit = $category_limits[$key];
        //     if ($limit <= 0) return true;
        //     return $category_limits[$key] > $amount;
        // });

        // categories

        // return MenuItem::all()
        // ->map(function($item) {
        //     return $item->category;
        // })
        // ->flatten()
        // ->groupBy('name')
        // ->map(function($item, $key) {
        //     return collect($item)->count();
        // });

        // tags

        $tags = Tag::all();

        $tag_limits = $tags->mapWithKeys(function($tag) {
            return [$tag->name => $tag->catering_limit];
        });

        // return $tag_limits->has('meat');

        return MenuItem::all()

        ->map(function($item) {
            return $item->tags;
        })
        ->flatten()
        ->groupBy('name')
        ->map(function($item, $key) {
            return collect($item)->count();
        })
        ->map(function($amount, $key) use ($tag_limits) {
            if ($tag_limits->has($key) && $tag_limits->get($key) > 0) {
                $limit = $tag_limits->get($key);
                return $amount >= $limit;
            } else {
                return false;
            }
        })->reduce(function($carry, $item) {
            return $carry || $item;
        }, false) ? 'true' : 'false';
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
