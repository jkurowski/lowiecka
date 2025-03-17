<?php

namespace App\Http\Controllers\Api\Forms;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApiStoreLeadsRequest;
use App\Repositories\Client\ClientRepository;
use Illuminate\Http\Request;

class LeadsController extends Controller
{
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function store(ApiStoreLeadsRequest $request)
    {

        $validated = $request->validated();

        \Log::info('creating client' . json_encode($validated));
        $this->clientRepository->createClient($validated, null, 1, $validated['source']);
        \Log::info('client created');
        
        return response()->json([
            'message' => 'Created',
        ], 201);
    }
}
