@extends('layouts.page', ['body_class' => 'about'])

@section('meta_title', 'O firmie')
{{-- @section('seo_title', $page->meta_title) --}}
{{-- @section('seo_description', $page->meta_description) --}}

@section('content')
    <div class="breadcrumbs breadcrumbs-news">
        <div class="container">
            <div class="d-flex flex-wrap gap-1 row-gap-2">
                <a href="/" class="breadcrumbs-link">Strona główna</a>
                <span class="breadcrumbs-separator">/</span>
                <span class="breadcrumbs-current">O firmie</span>
            </div>
        </div>
    </div>

    <main>
        <section class="section-first padding-bottom-medium">
            <div class="container">
                <div class="row row-gap-50px">
                    <div class="col-12 col-md-6 col-lg-5 col-xxl-4">
                        <h1 class="h3 mb-30px text-center text-md-start">
                            <span class="d-block text-uppercase" data-aos="fade-up">RWP</span>
                        </h1>
                        <div class="col-12 col-md-9 mb-50px text-center text-md-start" data-aos="fade">
                            <p>
                                RWP to deweloper specjalizujący się w realizacji projektów mieszkaniowych, którego
                                doświadczenie sięga 2014 r. Początkowo działalność RWP koncentrowała się na rynku lubelskim,
                                następnie została rozszerzona o Warszawę i województwo mazowieckie, by finalnie objąć
                                planami inwestycyjnymi całą Polskę.
                            </p>
                            <p>
                                RWP realizuje unikatowe inwestycje mieszkaniowe – wyróżniające się architekturą i designem,
                                stawiając na lokalizacje w otoczeniu zieleni, tworząc jakość na lata.
                            </p>
                        </div>
                        <div class="d-flex align-items-center justify-content-between flex-column gap-30px">
                            <div class="d-invest-grid" data-aos="fade-up">
                                <p class="d-invest-grid-number">
                                    15
                                </p>
                                <p class="d-invest-grid-text">
                                    Lat<br>doświadczenia
                                </p>
                            </div>
                            <div class="d-invest-grid" data-aos="fade-up">
                                <p class="d-invest-grid-number">
                                    250
                                </p>
                                <p class="d-invest-grid-text">
                                    Wybudowanych<br>mieszkań
                                </p>
                            </div>
                            <div class="d-invest-grid" data-aos="fade-up">
                                <p class="d-invest-grid-number">
                                    10
                                </p>
                                <p class="d-invest-grid-text">
                                    Planowanych<br>projektów
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 offset-lg-1 offset-xxl-2 text-center">
                        <img src="{{ asset('images/o_firmie_hero.png') }}" width="692" height="547" loading="eager"
                            alt="" class="img-fluid" data-aos="fade">
                    </div>
                </div>
            </div>
        </section>

        <section class="overflow-hidden padding-block-medium position-relative grid-container  text-center text-md-start">
            <div class="container">
                <div class="row align-items-center row-gap-50px">
                    <div class="col-12 col-md-6 col-xl-5">
                        <h2 class="h3 mb-40px">
                            <span class="d-block text-uppercase" data-aos="fade-up">Współpraca</span>
                        </h2>
                        <div class="text-content fw-light" data-aos="fade">
                            <p>Współpracujemy z pracowniami architektonicznymi m.in. Biuro Architektoniczne Plewa Sp. z o.o. Sp. k., Exterio Sp. z o.o., a wykonanie naszych projektów powierzamy doświadczonym podwykonawcom.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-xl-6 offset-xl-1">
                        <div class="flex-grid" data-aos="fade">
                            <div class="flex-grow-1" data-aos="fade">
                                <img src="{{ asset('images/partner_logo_4.png') }}"
                                     width="204"
                                     height="127"
                                     loading="lazy"
                                     alt=""
                                     class="img-fluid">
                            </div>
                            <div class="flex-grow-1" data-aos="fade">
                                <img src="{{ asset('images/partner_logo_3.png') }}"
                                     width="204"
                                     height="127"
                                     loading="lazy"
                                     alt=""
                                     class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pb-md-50px padding-top-medium gradient-bg-wrapper" id="gallery">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="h3 mb-50px text-center">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">Galeria</span>
                            <span class="d-block text-uppercase" data-aos="fade-up">inwestycji</span>
                        </h2>
                    </div>
                </div>
                <div class="row row-gap-30px">
                    <div class="col-12 text-center">
                        <div id="photos-list" class="container">
                            <div class="row justify-content-center">
                                @foreach ($galleries as $gallery)
                                    <div class="col-12 col-sm-6 col-md-4 p-2">
                                        <div class="overflow-hidden position-relative" style="height: 16rem">
                                            <a href="{{ route('front.gallery.show', [$gallery, $gallery->slug]) }}" title="{{ $gallery->name}}">
                                                <picture>
                                                    <source type="image/webp" srcset="{{asset('uploads/gallery/webp/'.$gallery->file_webp) }}">
                                                    <source type="image/jpeg" srcset="{{asset('uploads/gallery/'.$gallery->file) }}">
                                                    <img src="{{asset('uploads/gallery/'.$gallery->file) }}" alt="{{ $gallery->name }}" width="660" height="371" class="img-fluid object-fit-cover h-100 rounded" loading="lazy" decoding="async">
                                                </picture>

                                                <span class="custom-btn custom-btn-secondary position-absolute" style="right:10px;bottom:10px">Zobacz więcej</span>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="pb-md-20px bg-custom-gray">
            <x-cta pageTitle="O firmie" back="true"></x-cta>
        </div>
    </main>
@endsection
