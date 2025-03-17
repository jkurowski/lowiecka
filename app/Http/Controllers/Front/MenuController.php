<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;

class MenuController extends Controller
{
    public function index($uri = null)
    {
        $page = Page::where('uri', $uri)->firstOrFail();
        $parent = Page::ancestorsOf($page->id)->first();

        return view('front.menupage.index')->with(['page' => $page, 'parent' => $parent]);
    }
}
