@extends('layouts.page')

@section('meta_title', $page->title)
@isset($page->meta_title) @section('seo_title', $page->meta_title) @endisset
@isset($page->meta_description) @section('seo_description', $page->meta_description) @endisset
@section('content')
    <div class="breadcrumbs">
        <div class="container">
            <div class="d-flex flex-wrap gap-1 row-gap-2">
                <a href="/" class="breadcrumbs-link">Strona główna</a>
                <span class="breadcrumbs-separator">/</span>
                <span class="breadcrumbs-current">{{ $page->title }}</span>
            </div>
        </div>
    </div>

    <main>
        <section class="section-first padding-bottom-large">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12">
                        <h1 class="h3 mb-40px text-center">
                            <span class="fw-thin d-block fs-4 aos-init aos-animate" data-aos="fade-up">{{ $page->title }}</span>
                        </h1>
                        {!! parse_text($page->content) !!}
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

