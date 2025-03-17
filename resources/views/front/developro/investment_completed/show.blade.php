@extends('layouts.page', ['body_class' => 'completed-single'])

@section('meta_title', 'Inwestycje zrealizowane - ')
{{--@section('seo_title', $page->meta_title)--}}
{{--@section('seo_description', $page->meta_description)--}}

@section('content')
    <div class="breadcrumbs breadcrumbs-news">
        <div class="container">
            <div class="d-flex flex-wrap gap-1 row-gap-2">
                <a href="/" class="breadcrumbs-link">Strona główna</a>
                <span class="breadcrumbs-separator">/</span>
                <a href="/inwestycje-zrealizowane/" class="breadcrumbs-link">Inwestycje zrealizowane</a>
                <span class="breadcrumbs-separator">/</span>
                <span class="breadcrumbs-current">{{ $investment->name }}</span>

            </div>
        </div>
    </div>

    <main>
        <section class="section-first">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="h3 mb-50px text-center">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">Inwestycja zrealizowana</span>
                            <span class="d-block text-uppercase" data-aos="fade-up">{{ $investment->name }}</span>
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        {!! parse_text($investment->end_content) !!}
                    </div>
                </div>
            </div>
        </section>

        <div class="pb-md-20px">
            <x-cta></x-cta>
        </div>
    </main>
@endsection
