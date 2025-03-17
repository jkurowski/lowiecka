<?php

namespace App\Http\Controllers\Admin\Rodo;

use App\Http\Controllers\Controller;

// CMS
use App\Models\Client;

class ClientController extends Controller
{

    public function index()
    {
        return response()->json(Client::latest()->get([
            'name',
            'lastname',
            'id',
            'mail',
            'phone',
            'nip',
            'pesel',
            'id_number',
            'post_code',
            'city',
            'street',
            'house_number',
            'apartment_number'
        ]));
    }

    public function clientModal()
    {
        return view('admin.crm.client.modal.client-data');
    }
}
