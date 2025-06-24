<?php

namespace App\Http\Controllers\Front\About;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\RodoRules;

class IndexController extends Controller
{
    public function index()
    {
        $images = Gallery::find(1)->photos()->get();
        $rules = RodoRules::orderBy('sort')->whereActive(1)->get();
        return view('front.about.investor', compact('images', 'rules'));
    }
    public function investment()
    {
        $images = Gallery::find(2)->photos()->get();
        $rules = RodoRules::orderBy('sort')->whereActive(1)->get();
        return view('front.about.investment', compact('images', 'rules'));
    }
}
