<?php

namespace App\Http\Controllers\Front\About;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $galleries = Gallery::where('status', 1)->get();
        return view('front.about.index', compact('galleries'));
    }
}
