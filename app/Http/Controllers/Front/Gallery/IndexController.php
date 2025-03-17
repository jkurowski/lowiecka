<?php

namespace App\Http\Controllers\Front\Gallery;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return redirect('/');
    }

    public function show(Gallery $gallery, $gallerySlug)
    {
        if ($gallery->status == 0 || $gallerySlug != $gallery->slug) {
            return redirect('/');
        }
        return view('front.gallery.show', compact('gallery'));
    }
}
