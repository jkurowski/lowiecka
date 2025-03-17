<?php

namespace App\Http\Controllers\Front\Client\Property;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $client = Auth::guard('client')->user();

        // Eager load properties and payments
        $clientWithProperties = $client->load(['properties.payments' => function ($query) {
            $query->latest();
        }]);

        // Process the latest payment for each property
        foreach ($clientWithProperties->properties as $property) {
            $property->latestPayment = $property->nextPaymentAfterToday();
        }

        //dd($clientWithProperties);

        return view('front.auth.client.property.index', [
            'client' => $clientWithProperties
        ]);
    }

    public function show(Property $property)
    {
        $client = Auth::guard('client')->user();
        $latestPayment = null;
        if ($property) {
            $latestPayment = $property->payments()->first();
        }

        return view('front.auth.client.property.show', [
            'client' => $client,
            'property' => $property,
            'latestPayment' => $latestPayment
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
