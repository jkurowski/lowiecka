@extends('layouts.page', ['body_class' => 'media'])

@section('meta_title', 'Pakiety rabatowe')
{{--@section('seo_title', $page->meta_title)--}}
{{--@section('seo_description', $page->meta_description)--}}

@section('content')
    <div class="breadcrumbs breadcrumbs-news">
        <div class="container">
            <div class="d-flex flex-wrap gap-1 row-gap-2">
                <a href="/" class="breadcrumbs-link">Strona główna</a>
                <span class="breadcrumbs-separator">/</span>
                <span class="breadcrumbs-current">Pakiety rabatowe</span>
            </div>
        </div>
    </div>
    <main>
        <section id="inwestycje" class="pt-0 text-md-start">
            <div class="container">
                <div class="row row-gap-50px">
                    <div class="col-12 col-md-6 col-lg-5">
                        <h1 class="h3 mb-40px">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">Poznaj nasze</span>
                            <span class="d-block text-uppercase" data-aos="fade-up">PAKIETY RABATOWE</span>
                        </h1>
                        <div class="text-content fw-light" data-aos="fade">
                            <p class="mb-30px">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
                            <p>Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-6 offset-lg-1 text-center">
                        <img src="{{ asset('images/pakiety_rabatowe_hero.jpg') }}" alt="Pakiety rabatowe" class="img-fluid" data-aos="fade" width="672" height="527">
                    </div>
                </div>
            </div>
        </section>

        <section class="gradient-bg-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="h3 mb-30px text-center">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">Pakiety zniżek</span>
                            <span class="d-block text-uppercase" data-aos="fade-up">PRZY ODBIORZE KLUCZY</span>
                        </h2>
                        <div class="col-12 col-md-6 col-lg-4 offset-md-3 offset-lg-4 mb-50px text-center" data-aos="fade-up">
                            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores.</p>
                        </div>
                    </div>
                </div>
                <div class="row row-gap-30px justify-content-center pt-20px pt-md-75px">
                    <x-discount-package></x-discount-package>
                </div>
            </div>
        </section>
        <div class="bg-custom-gray">
            <x-cta></x-cta>
        </div>
    </main>
@endsection
