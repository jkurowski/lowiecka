<?php

namespace App\Http\Controllers\Front\Media;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        return view('front.media.index');
    }
}
