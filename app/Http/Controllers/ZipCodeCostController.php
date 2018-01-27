<?php

namespace App\Http\Controllers;

use App\Models\ZipCodeCost;
use Illuminate\Http\Request;

class ZipCodeCostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [
            'zipCodeCosts' => ZipCodeCost::all()
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $zip_cost = ZipCodeCost::updateOrCreate(
            ['zip_code' => $request->input('zip_code')],
            ['cost' => $request->input('cost')]);

        if ($zip_cost) {
            return [
                'zipCodeCost' => $zip_cost
            ];
        } else {
            return 'failed';
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ZipCodeCost  $zipCodeCost
     * @return \Illuminate\Http\Response
     */
    public function show(ZipCodeCost $zipCodeCost)
    {
        return [
            'zipCodeCost' => $zipCodeCost
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ZipCodeCost  $zipCodeCost
     * @return \Illuminate\Http\Response
     */
    public function edit(ZipCodeCost $zipCodeCost)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ZipCodeCost  $zipCodeCost
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ZipCodeCost $zipCodeCost)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ZipCodeCost  $zipCodeCost
     * @return \Illuminate\Http\Response
     */
    public function destroy(ZipCodeCost $zipCodeCost)
    {

    }
}
