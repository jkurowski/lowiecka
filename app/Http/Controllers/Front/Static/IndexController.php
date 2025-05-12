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
        $allowedPages = ['polityka-prywatnosci', 'regulamin', 'kontakt', 'finansowanie', 'lokalizacja'];

        if (in_array($page, $allowedPages)) {
            return view('front.static.'.$page);
        }

        abort(404); // Return a 404 error if the page is not allowed
    }
}
