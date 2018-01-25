<?php

namespace App\Http\Controllers;

use Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function settings()
    {
        return view('settings.index', [
            'printer_id' => Setting::get('printer_id', '')
        ]);
    }

    public function printer(Request $request)
    {
        if ($request->has('printer_id'))
        {
            Setting::set('printer_id', $request->input('printer_id'));
            Setting::save();

            return [
                'success' => true,
                'printer_id' => Setting::get('printer_id', '')
            ];
        } else {
            return [
                'success' => false
            ];
        }
    }
}
