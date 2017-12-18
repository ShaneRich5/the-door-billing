<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    public function catering()
    {
        return [
            'categories' => Category::with('menuItems')->get(),
        ];
    }
}
