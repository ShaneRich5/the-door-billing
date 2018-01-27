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

class OrderNoteController extends Controller
{
    public function store($id, Request $request)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'paid') {
            return response()->json([
                'message' => 'cannot make changes to paid order'
            ], 400);
        }

        if ($note = $request->input('note'))
        {
            $order->note = $note;
            $order->save();

            return response()->json([
                'note' => $note
            ]);
        } else {
            return response()->json([
                'message' => 'failed to add note'
            ], 400);
        }
    }
}