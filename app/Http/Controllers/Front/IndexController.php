<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

// CMS
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

        $isAdmin = auth()->check();

        return view('front.homepage.index', compact(
            'popup',
            'isAdmin'
        ));
    }
}
