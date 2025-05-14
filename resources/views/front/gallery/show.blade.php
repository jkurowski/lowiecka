@extends('layouts.page')

@section('meta_title', 'Galeria - '.$gallery->name)
{{--@section('seo_title', $article->meta_title)--}}
{{--@section('seo_description', $article->meta_description)--}}
{{--@section('seo_robots', $article->meta_robots)--}}

@section('content')
    <div class="breadcrumbs breadcrumbs-news">
        <div class="container">
            <div class="d-flex flex-wrap gap-1 row-gap-2">
                <a href="/" class="breadcrumbs-link">Strona główna</a>
                <span class="breadcrumbs-separator">/</span>
                <a href="{{ route('front.gallery.index') }}" class="breadcrumbs-link">Galeria</a>
                <span class="breadcrumbs-separator">/</span>
                <span class="breadcrumbs-current">{{ $gallery->name }}</span>
            </div>
        </div>
    </div>

    <main>
        <section >
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title mb-4">
                            <div class="sub-section-title">
                                <span>ŁOWICKA 100</span>
                            </div>
                            <h2 class="mb-4">{{ $gallery->name }}</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @include('front.parse.gallery', ['list' => $images])
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="text-center">
                            <a href="{{ route('front.gallery.index') }}" class="bttn">Wróć do listy <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
