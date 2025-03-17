<?php

namespace App\Http\Controllers\Admin\Crm\Client;

use App\Http\Controllers\Controller;

// CMS
use App\Http\Requests\ClientPropertyFormRequest;
use App\Models\Investment;
use App\Models\Client;
use App\Models\Property;

class PropertyController extends Controller
{
    public function create()
    {

        return view('admin.crm.client.payments.modal.new-property', [
            'investments' => Investment::select(['id', 'name'])->get(),
        ]);
    }

    public function store(ClientPropertyFormRequest $request, Client $client)
    {
        $property = Property::find($request->validated('property_id'));

        $status = $request->validated('status');

        $property->status = $status;
        $property->client_id = $client->id;

        if ($status == 2) {
            $property->reservation_until = $request->validated('reservation_date');
        }

        if ($status == 3) {
            $property->saled_at = $request->validated('saled_at');
        }

        $property->save();

        return response()->json([
            'status' => 'success'
        ], 200);
    }

    public function destroy(string $id)
    {

    }
}
