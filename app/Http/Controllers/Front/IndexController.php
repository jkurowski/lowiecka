<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

// CMS
use App\Models\Gallery;
use App\Models\RodoRules;
use Illuminate\Support\Facades\Cookie;

class IndexController extends Controller
{
    public function index()
    {
        $popup = 0;
        if(settings()->get("popup_status") == "1") {
            if(settings()->get("popup_mode") == "1") {
                Cookie::queue('popup', null);
                $popup = 1;
            } else {
                if(Cookie::get('popup') == null){
                    $popup = 1;
                    Cookie::queue('popup', true);
                }
            }
        } else {
            Cookie::queue('popup', null);
        }
        $rules = RodoRules::orderBy('sort')->whereActive(1)->get();
        $isAdmin = auth()->check();
        $images = Gallery::find(2)->photos()->get();
        return view('front.homepage.index', compact(
            'popup',
            'isAdmin',
            'images',
            'rules'
        ));
    }
}
