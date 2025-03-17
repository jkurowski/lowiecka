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
                <a href="/o-firmie/" class="breadcrumbs-link">O firmie</a>
                <span class="breadcrumbs-separator">/</span>
                <span class="breadcrumbs-current">Galeria {{ $gallery->name }}</span>
            </div>
        </div>
    </div>

    <main>
        <section class="section-first section-padding-bottom-medium">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="h3 mb-50px text-center">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">Galeria</span>
                            <span class="d-block text-uppercase" data-aos="fade-up"> {{ $gallery->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="text-center">
                            <a href="/o-firmie/#gallery" class="custom-btn custom-btn-secondary">Wróć do listy</a>
                        </div>
                        <div class="text-content mt-30px">
                            {!! parse_text($gallery->text) !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
