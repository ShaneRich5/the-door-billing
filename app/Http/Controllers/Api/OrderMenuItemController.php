<?php

namespace App\Http\Controllers\Api;

use Log;
use Setting;
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
        return Setting::get('name', 'Shane');
        // limits the quantity of menu items available on each item
        // based on the catering limit stipulated

        $categories = Category::all();

        $category_limits = $categories->mapWithKeys(function($category) {
            return [$category->name => $category->catering_limit];
        });

        return MenuItem::all()
        ->map(function($item) {
            return $item->category;
        })
        ->flatten()
        ->groupBy('name')
        ->map(function($item, $key) {
            return collect($item)->count();
        })
        ->filter(function($value, $key) use ($category_limits) {
            $limit = $category_limits[$key];
            if ($limit == 0) {
                return false;
            }
            return $value > $limit;
        });

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

        $menuItems = MenuItem::find($ids);

        $itemsAboveCategoryLimit = $this->menuItemsMeetCategoryLimits($menuItems);

        if ( ! $itemsAboveCategoryLimit->isEmpty())
        {
            return response()->json([
                'message' => 'Too many items selected in some categories',
                'data' => $itemsAboveCategoryLimit
            ], 400);
        }

        $itemsAboveTagLimits = $this->menuItemsMeetTagCateringLimits($menuItems);

        if ( $itemsAboveTagLimits->isEmpty() ) {
            $per_person_cost = (float) Setting::get('per_person_regular_cost', '18.50');
        } else {
            $per_person_cost = (float) Setting::get('per_person_high_cost', '20.00');
        }

        $delivery = $order->delivery;
        $tax = (float) Setting::get('tax', '8.875');

        // recalculate order and deliver costs
        $order->subtotal = $per_person_cost * $delivery->attendance;
        $order->tax = $order->subtotal * $tax / 100;
        $order->total = $order->subtotal + $order->tax + $delivery->cost;
        $order->save();

        $order->menuItems()->sync($ids);

        return [
            'menu_items' => $order->menuItems()->get(),
            'order' => $order
        ];
    }

    private function menuItemsMeetTagCateringLimits($menuItems)
    {
        $tags = Tag::all();

        $tag_limits = $tags->mapWithKeys(function($tag) {
            return [$tag->name => $tag->catering_limit];
        });

        return $menuItems
        ->map(function($item) {
            return $item->tags;
        })
        ->flatten()
        ->groupBy('name')
        ->map(function($item, $key) {
            return collect($item)->count();
        })
        ->filter(function($amount, $key) use ($tag_limits) {
            if ($tag_limits->has($key) && $tag_limits->get($key) > 0) {
                $limit = $tag_limits->get($key);
                return $amount >= $limit;
            } else {
                return false;
            }
        });
    }

    private function menuItemsMeetCategoryLimits($menuItems)
    {
        // limits the quantity of menu items available on each item
        // based on the catering limit stipulated

        $categories = Category::all();

        $category_limits = $categories->mapWithKeys(function($category) {
            return [$category->name => $category->catering_limit];
        });

        return $menuItems
        ->map(function($item) {
            return $item->category;
        })
        ->flatten()
        ->groupBy('name')
        ->map(function($item, $key) {
            return collect($item)->count();
        })
        ->filter(function($value, $key) use ($category_limits) {
            $limit = $category_limits[$key];
            if ($limit == 0) {
                return false;
            }
            return $value > $limit;
        });
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
