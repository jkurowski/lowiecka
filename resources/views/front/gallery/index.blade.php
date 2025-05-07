@extends('layouts.page')

@section('meta_title', 'Kontakt')
@isset($page->meta_title) @section('seo_title', $page->meta_title) @endisset
@isset($page->meta_description) @section('seo_description', $page->meta_description) @endisset
@section('content')
    <div class="breadcrumbs">
        <div class="container">
            <div class="d-flex flex-wrap gap-1 row-gap-2">
                <a href="/" class="breadcrumbs-link">Strona główna</a>
                <span class="breadcrumbs-separator">/</span>
                <span class="breadcrumbs-current">Galeria</span>
            </div>
        </div>
    </div>

    <main>
        <section class="pb-0">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title mb-4">
                            <div class="sub-section-title">
                                <span>LOWIECKA 100</span>
                            </div>
                            <h2 class="mb-4">Galeria</h2>
                        </div>
                    </div>
                </div>
                <div id="galleryList" class="mt-4 row justify-content-center">
                    @foreach($galleries as $gallery)
                        <div class="@if($galleries->count() >= 3) col-4 @else col-6 @endif">
                            <a href="{{ route('front.gallery.show', [$gallery, Str::slug($gallery->name)]) }}">
                                <div class="gallery-item-list">
                                    <div class="golden-lines">
                                        <div class="golden-line-4">
                                            <img src="http://lowiecka.test/images/zote-linie-4.png" alt="">
                                        </div>
                                        <div class="golden-line-3">
                                            <img src="http://lowiecka.test/images/zote-linie-3-white.png" alt="">
                                        </div>
                                        <div class="gallery-item-img">
                                            <img src="{{ asset('../uploads/gallery/'.$gallery->file) }}" alt="Galeria {{ $gallery->name }}">
                                        </div>
                                    </div>
                                    <h3>{{ $gallery->name }}</h3>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
@endsection

