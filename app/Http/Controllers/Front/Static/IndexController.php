<?php

namespace App\Http\Controllers\Front\Static;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// CMS
use App\Models\Investment;
use App\Models\RodoRules;
use App\Repositories\Client\ClientRepository;

class IndexController extends Controller
{
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }
    public function testPage()
    {
        $investment = Investment::find(1);
        return view('test-page', [
            'rules' => RodoRules::orderBy('sort')->whereActive(1)->get()
        ]);
    }

    public function pages($page)
    {
        $allowedPages = ['polityka-prywatnosci', 'regulamin', 'kontakt'];

        if (in_array($page, $allowedPages)) {
            return view('front.static.'.$page);
        }

        abort(404); // Return a 404 error if the page is not allowed
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|email|max:255'
            ]);

            $client = $this->clientRepository->createClient(
                [
                    'name' => $request->name,
                    'lastname' => $request->surname,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'ip' => $request->ip()
                ],null, 1, 'iframe'
            );
        } catch (\Throwable $exception) {

        }
        return redirect()->back()->with('success', 'Dziękujemy za rejestracje w systemie!');
    }
}
