@extends('layouts.page', ['body_class' => 'completed'])

@section('meta_title', 'Inwestycje zrealizowane')
{{--@section('seo_title', $page->meta_title)--}}
{{--@section('seo_description', $page->meta_description)--}}

@section('content')
    <div class="breadcrumbs breadcrumbs-news">
        <div class="container">
            <div class="d-flex flex-wrap gap-1 row-gap-2">
                <a href="/" class="breadcrumbs-link">Strona główna</a>
                <span class="breadcrumbs-separator">/</span>
                <span class="breadcrumbs-current">Inwestycje zrealizowane</span>
            </div>
        </div>
    </div>

    <main>
        <section class="section-first padding-bottom-medium">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="h3 mb-50px text-center">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">Inwestycje</span>
                            <span class="d-block text-uppercase" data-aos="fade-up">zrealizowane</span>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column gap-50px gap-md-100px">
                @foreach($investments as $i)
                    @php
                    $url = route('front.investment.completed.show', $i->slug ?? '#');
                    @endphp
                    <x-investment-completed
                            link='{{ $url }}'
                            title="{{ $i->name }}"
                            subtitle="{{ $i->subtitle ?? '' }}"
                            text="{{ $i->entry_content ?? '' }}"
                            img_url="{{ $i->file_thumb ? asset('investment/thumbs/'. $i->file_thumb) : asset('default-thumbnail.jpg') }}">
                    </x-investment-completed>
                @endforeach
            </div>
        </section>

        <section class="pb-md-50px padding-top-medium">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="h3 mb-50px text-center">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">Nasze</span>
                            <span class="d-block text-uppercase" data-aos="fade-up"> aktualności</span>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="container">
                    <div class="row justify-content-center row-cols-1 row-cols-md-2 row-cols-lg-3 row-gap-50px">
                        @foreach($last_news as $a)
                            <div class="col" data-aos="fade">
                                <x-news-list-item :article="$a"></x-news-list-item>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-12 text-center mt-30px">
                            <a href="/aktualnosci/" class="custom-btn custom-btn-primary mt-3">Zobacz więcej</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="pb-md-20px">
            <x-cta pageTitle="Inwestycje zrealizowane" back="true"></x-cta>
        </div>
    </main>
@endsection
