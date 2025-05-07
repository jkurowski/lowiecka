<?php

namespace App\Http\Controllers\Front\Gallery;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Repositories\GalleryRepository;
use App\Services\GalleryService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    private GalleryRepository $repository;
    public function __construct(GalleryRepository $repository){
        $this->repository = $repository;
    }

    public function index()
    {
        $galleries = $this->repository->allSort('ASC');
        return view('front.gallery.index', compact('galleries'));
    }

    public function show(Gallery $gallery, $gallerySlug)
    {
        if ($gallery->status == 0 || $gallerySlug != $gallery->slug) {
            return redirect('/');
        }

        $images = $gallery->photos()->get();
        return view('front.gallery.show', compact('gallery', 'images'));
    }
}
