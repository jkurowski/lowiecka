<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GusNipRequest;
use GusApi\Exception\NotFoundException;
use GusApi\GusApi;
use Illuminate\Http\Request;

class NipController extends Controller
{
    private $apiKey;

    public function __construct()
    {

        $this->apiKey = config('app.gus_api_key', 'abcde12345abcde12345');
    }
    public function index(GusNipRequest $request)
    {   
        $gus = new GusApi($this->apiKey);

        try {
            $gus->login();
            $result = $gus->getByNip($request->nip);

            return response()->json([
                'status' => 'success',
                'data' => $result,
            ]);
        } catch (\GusApi\Exception\InvalidUserKeyException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Błąd logowania do API GUS',
            ], 500);
        } catch (NotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nie znaleziono podmiotu',
            ], 404);
        }
    }
}
