<?php

namespace App\Http\Controllers\Admin\Crm\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientPreferenceFormRequest;
use App\Models\Client;
use App\Models\ClientPreference;
use App\Models\Investment;
use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function index(Client $client)
    {
        $client = $client->load('preferences');

        return view('admin.crm.client.preferences.index', [
            'client' => $client
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Client $client)
    {
        return view('admin.crm.client.preferences.form', [
            'cardTitle' => 'Dodaj zainteresowanie',
            'backButton' => route('admin.crm.clients.preferences.index', $client->id),
            'client' => $client,
            'investments' => Investment::all()->pluck('name', 'id')->prepend('Brak', null)
        ])->with('entry', ClientPreference::make());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientPreferenceFormRequest $request, Client $client)
    {
        $validatedData = $request->validated();
        $client->preferences()->create($validatedData);

        return redirect(route('admin.crm.clients.preferences.index', $client->id))->with('success', 'Nowy wpis dodany');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client, ClientPreference $preference)
    {
        return view('admin.crm.client.preferences.form', [
            'entry' => $preference,
            'cardTitle' => 'Edytuj zainteresowanie',
            'client' => $client,
            'investments' => Investment::all()->pluck('name', 'id')->prepend('Brak', null),
            'backButton' => route('admin.crm.clients.preferences.index', $client->id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientPreferenceFormRequest $request, Client $client, ClientPreference $preference)
    {
        $validatedData = $request->validated();
        $preference->update($validatedData);

        return redirect()
            ->route('admin.crm.clients.preferences.index', $client->id)
            ->with('success', 'Preferencja klienta została pomyślnie zaktualizowana.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client, ClientPreference $preference)
    {
        $preference->delete();
        return response()->json('Deleted');
    }
}
