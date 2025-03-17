<?php

namespace App\Http\Controllers\Admin\Crm\Client;

use App\Http\Controllers\Controller;

//CMS
use App\Models\Client;

class IssueController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load('issues');

        return view('admin.crm.client.issue.index', [
            'client' => $client
        ]);
    }
}
