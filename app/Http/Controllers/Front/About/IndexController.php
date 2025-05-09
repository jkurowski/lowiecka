<?php

namespace App\Http\Controllers\Front\About;

use App\Http\Controllers\Controller;
use App\Models\Gallery;

class IndexController extends Controller
{
    public function index()
    {
        $images = Gallery::find(1)->photos()->get();
        return view('front.about.investor', compact('images'));
    }
    public function investment()
    {
        return view('front.about.investment');
    }
}
