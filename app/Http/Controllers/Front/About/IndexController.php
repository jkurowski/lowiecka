<?php

namespace App\Http\Controllers\Front\About;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('front.about.investor');
    }
    public function investment()
    {
        return view('front.about.investment');
    }
}
