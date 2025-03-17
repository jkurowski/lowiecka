@extends('layouts.page', ['body_class' => 'news'])

@section('meta_title', 'Aktualności')
{{--@section('seo_title', $page->meta_title)--}}
{{--@section('seo_description', $page->meta_description)--}}

@section('content')
    <div class="breadcrumbs breadcrumbs-news">
        <div class="container">
            <div class="d-flex flex-wrap gap-1 row-gap-2">
                <a href="/" class="breadcrumbs-link">Strona główna</a>
                <span class="breadcrumbs-separator">/</span>
                <a href="{{ route('front.developro.show', $investment->slug) }}" class="breadcrumbs-link">{{ $investment->name }}</a>
                <span class="breadcrumbs-separator">/</span>
                <span class="breadcrumbs-current">Dziennik budowy</span>
            </div>
        </div>
    </div>
    <main>
        <section class="py-3 bg-white sticky-top investment-navbar-wrapper">
            <nav id="investment-navbar" class="navbar">
                <div class="container justify-content-center">
                    <div class="navbar-nav flex-wrap flex-row gap-3 justify-content-center">
                        <a class="nav-link nav-link-black" href="{{ route('front.developro.show', $investment->slug) }}#apartamenty">Apartamenty</a>
                        <a class="nav-link nav-link-black" href="{{ route('front.developro.show', $investment->slug) }}#mieszkania">Mieszkania</a>
                        <a class="nav-link nav-link-black" href="{{ route('front.developro.show', $investment->slug) }}#atuty">Atuty</a>
                        <a class="nav-link nav-link-black d-none" href="{{ route('front.developro.show', $investment->slug) }}#wizualizacje">Wizualizacje</a>
                        <a class="nav-link nav-link-black" href="{{ route('front.developro.investment.news', $investment->slug) }}">Dziennik budowy</a>
                        <a class="nav-link nav-link-black" href="{{ route('front.developro.show', $investment->slug) }}#lokalizacja">Lokalizacja</a>
                        <a class="nav-link nav-link-black" href="{{ route('front.developro.show', $investment->slug) }}#kontakt">Kontakt</a>
                    </div>
                </div>
            </nav>
        </section>
        @if($investment_page)
            <section class="section-first @if(!$investment_page->contact_form) section-padding-bottom-large @endif">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h1 class="h3 mb-50px text-center">
                                <span class="fw-thin d-block fs-4" data-aos="fade-up">Dziennik</span>
                                <span class="d-block text-uppercase" data-aos="fade-up"> budowy</span>
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            {!! parse_text($investment_page->content) !!}
                        </div>
                    </div>
                </div>
            </section>

            @if($investment_page->contact_form)
            <div id="kontakt">
                <x-cta :investmentName="$investment->name"  :investmentId="$investment->id" pageTitle="Dziennik budowy" back="true"></x-cta>
            </div>
            @endif
        @endif
    </main>
@endsection
