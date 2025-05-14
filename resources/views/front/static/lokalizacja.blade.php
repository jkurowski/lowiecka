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
                <span class="breadcrumbs-current">Lokalizacja</span>
            </div>
        </div>
    </div>

    <main>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title mb-4">
                            <div class="sub-section-title">
                                <span>ŁOWICKA 100</span>
                            </div>
                            <h2 class="mb-4">Lokalizacja</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="position-relative split-bg">
                <div class="split">
                    <div class="split-left"></div>
                    <div class="split-right"></div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-7">
                            <img src="{{ asset('./images/mapa.jpg') }}" alt="Mapa okolicy inwestycji" width="960" height="852">
                        </div>
                        <div class="col-5 d-flex align-items-center">
                            <div class="ps-5">
                                <div class="section-title justify-content-start mb-4">
                                    <div class="sub-section-title">
                                        <span>LOKALIZACJA</span>
                                    </div>
                                    <h2 class="text-start">Wszystko w zasięgu ręki</h2>
                                </div>
                                <div class="section-text">
                                    <p>Łowicka 100 to inwestycja w spokojnej, a jednocześnie świetnie skomunikowanej części Skierniewic. W pobliżu znajdują się sklepy, markety, apteka, poczta, kościół, a także siłownia i plac zabaw. Miłośnicy rekreacji docenią bliskość parku miejskiego, skate parku oraz malowniczego Bolimowskiego Parku Krajobrazowego.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container pt-4">
                <div class="row justify-content-center">
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/walking.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">120 m</b><p>Sklep spożywczy</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/walking.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">120 m</b><p>Siłownia</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/walking.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">150 m</b><p>Plac zabaw</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/walking.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">500 m</b><p>Kościół</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/walking.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">900 m</b><p>Skate park</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/walking.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">900 m</b><p>Market</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/walking.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">900 m</b><p>Apteka</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/walking.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">1000 m</b><p>Park miejski</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/walking.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">1,3 km</b><p>Poczta</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/walking.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">1,7 km</b><p>Dworzec PKP</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/walking.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">2 km</b><p>Bolimowski Park Krajobrazowy</p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center mt-5">
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/car.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">1,8 km</b><p>Dworzec PKP i PKS</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/car.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">10 km</b><p>Autostrada A2</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/car.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">25 km</b><p>Droga S8</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-3">
                        <div class="text-center jak-daleko">
                            <img src="{{ asset('images/car.svg') }}" alt="Ikonka roweru" width="41" height="41" loading="lazy">
                            <b class="d-block w-100">80 km</b><p>Warszawa</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="p-0 d-none">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title">
                            <div class="sub-section-title">
                                <span>GALERIA</span>
                            </div>
                            <h2>Zdjęcia z okolicy</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid" id="slick-fluid">
                <div class="row">
                    <div class="col-4">
                        <div class="slick-image">
                            <img src="https://placehold.co/910x580" alt="">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="slick-image">
                            <img src="https://placehold.co/910x580" alt="">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="slick-image">
                            <img src="https://placehold.co/910x580" alt="">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="slick-image">
                            <img src="https://placehold.co/910x580" alt="">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="slick-image">
                            <img src="https://placehold.co/910x580" alt="">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="slick-image">
                            <img src="https://placehold.co/910x580" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-section pt-0">
            <div class="container">
                <div class="row">
                    <div class="col-4">
                        <div class="pe-5 me-5">
                            <div class="section-title justify-content-start">
                                <div class="sub-section-title">
                                    <span>KONTAKT</span>
                                </div>
                                <h2 class="text-start mb-4">Zapytaj o ofertę!</h2>
                                <p>Nasz zespół jest do Twojej dyspozycji, aby udzielić wszelkich informacji i odpowiedzieć na Twoje pytania.</p>
                                <ul class="mb-0 contact-data list-unstyled mt-4">
                                    <li class="contact-data-phone"><a href="tel:+48690256457">690-256-457</a></li>
                                    <li class="contact-data-mail"><a href="mailto:biuro@epsdevelopment.pl">biuro@epsdevelopment.pl</a></li>
                                    <li class="contact-data-location">
                                        <p>EPS Development Sp. z o.o.</p>
                                        <p>Kozietulskiego 14</p>
                                        <p>96-100 Skierniewice</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        @include('components.contact-form', ['page' => '', 'back' => ''])
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#slick-fluid .row").slick({
                centerMode: true,
                slidesToShow: 3,
            });
        });
    </script>
@endpush

