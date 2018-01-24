<?php

namespace App\Http\Controllers;

use Setting;
use Illuminate\Http\Request;

class PrinterController extends Controller
{
    public function settings()
    {
        return view('printers.settings', [
            'printer_id' => Setting::get('printer_id')
        ]);
    }
}
