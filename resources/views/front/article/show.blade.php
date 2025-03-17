@extends('layouts.page')

{{--@section('meta_title', $article->title)--}}
{{--@section('seo_title', $article->meta_title)--}}
{{--@section('seo_description', $article->meta_description)--}}
{{--@section('seo_robots', $article->meta_robots)--}}

@section('content')
    <div class="breadcrumbs breadcrumbs-news">
        <div class="container">
            <div class="d-flex flex-wrap gap-1 row-gap-2">
                <a href="/" class="breadcrumbs-link">Strona główna</a>
                <span class="breadcrumbs-separator">/</span>
                <a href="/aktualnosci/" class="breadcrumbs-link">Aktualności</a>
                <span class="breadcrumbs-separator">/</span>
                <span class="breadcrumbs-current">Rozpoczynamy sprzedaż na inwestycji Poligonowa</span>
            </div>
        </div>
    </div>

    <main>
        <section class="section-first section-padding-bottom-medium">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="h3 mb-50px text-center">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">Nasze</span>
                            <span class="d-block text-uppercase" data-aos="fade-up"> aktualności</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-10 col-lg-8 col-xl-6 offset-md-1 offset-lg-2 offset-xl-3">
                        <p class="fs-5 mb-3">{{ $article->posted_at }}</p>
                        <h1 class="h5 mb-3">{{ $article->title }}</h1>

                        <picture>
                            <source type="image/webp" srcset="{{asset('uploads/articles/webp/'.$article->file_webp) }}">
                            <source type="image/jpeg" srcset="{{asset('uploads/articles/'.$article->file) }}">
                            <img src="{{asset('uploads/articles/'.$article->file) }}" alt="{{ $article->title }}" width="660" height="371" class="img-fluid">
                        </picture>

                        <div class="text-content mt-30px">
                            {!! parse_text($article->content) !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="d-none">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="h3 mb-50px text-center">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">Podobne</span>
                            <span class="d-block text-uppercase" data-aos="fade-up">Artykuły</span>
                        </h2>

                    </div>
                </div>
            </div>
            <div class="pb-md-75px">
                <div class="container">
                    <div class="row justify-content-center row-cols-1 row-cols-md-2 row-cols-lg-3 row-gap-30px">
                        <div class="col" data-aos="fade">
{{--                            <x-news-list-item></x-news-list-item>--}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
