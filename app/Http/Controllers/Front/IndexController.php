<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

// CMS
use App\Models\Gallery;
use App\Models\RodoRules;
use App\Services\Front\PopupBanner;

class IndexController extends Controller
{
    public function index()
    {
        $popupBanner = PopupBanner::global();
        $rules = RodoRules::orderBy('sort')->whereActive(1)->get();
        $isAdmin = auth()->check();
        $images = Gallery::find(2)->photos()->get();
        return view('front.homepage.index', compact(
            'popupBanner',
            'isAdmin',
            'images',
            'rules'
        ));
    }
}
