@extends('layouts.page', ['body_class' => 'investment'])

@section('meta_title', $investment->name)
{{-- @section('seo_title', $page->meta_title) --}}
{{-- @section('seo_description', $page->meta_description) --}}

@section('content')
    <main>

        <?php
        $slides = [
            [
                'title' => 'Apartamenty Poligonowa Etap 5',
                'subtitle' => 'Elegancja i komfort',
                'text' => 'Etap 5 inwestycji Apartamenty Poligonowa to nowoczesny budynek w spokojnej dzielnicy Sławin w Lublinie. Oferuje 110 komfortowych mieszkań i 7 lokali usługowych. Przestronne, funkcjonalne wnętrza z dużymi przeszkleniami zapewniają komfort życia. Inwestycja łączy nowoczesną architekturę z atrakcyjną lokalizacją w pobliżu terenów zielonych i usług. Zakończenie budowy planowane jest na IV kwartał 2026 roku..',
                'button' => [
                    'text' => 'Apartamenty',
                    'link' => '#mieszkania',
                ],
                'image_url' => asset('images/w-sprzedazy/poligonowa-5/wiz-1.jpg'),
            ],
        ];
        
        $slider_config = [
            'dots' => false,
            'prevArrow' => '[data-hero-slider-controls] .slick-prev',
            'nextArrow' => '[data-hero-slider-controls] .slick-next',
            'adaptiveHeight' => true,
        ];
        
        ?>
        <section class="section-hero">
            <div class="hero-slider-container">
                <div class="hero-slider" data-slick='<?= json_encode($slider_config) ?>'>
                    <?php foreach ($slides as $index => $slide) : ?>
                    <div>
                        <div class="hero-slide text-center text-lg-start">
                            <img src="<?= $slide['image_url'] ?>" alt="<?= $slide['title'] ?>"
                                class="w-100 h-100 object-fit-cover " width="1920" height="1080" loading="eager">
                            <div class="hero-slider-content">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-12 col-lg-8">
                                            <?php if ($index === 0) : ?>
                                            <h1 class="">
                                                <span class="fw-thin fs-2 d-block mb-3"
                                                    data-aos="fade-up"><?= $slide['title'] ?></span>
                                                <span class="fw-bold text-uppercase"
                                                    data-aos="fade-up"><?= $slide['subtitle'] ?></span>
                                            </h1>
                                            <?php else : ?>
                                            <h2 class="h1">
                                                <span class="fw-thin fs-2 d-block"
                                                    data-aos="fade-up"><?= $slide['title'] ?></span>
                                                <span class="fw-bold text-uppercase"
                                                    data-aos="fade-up"><?= $slide['subtitle'] ?></span>
                                            </h2>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="text-content mb-30px mt-3"><?= $slide['text'] ?></div>
                                            <a href="<?= $slide['button']['link'] ?>"
                                                class="custom-btn custom-btn-primary"><?= $slide['button']['text'] ?></a>
                                        </div>
                                        <div class="col-12 col-lg-8 pt-30px mt-md-40px">
                                            <x-search-form-investment></x-search-form-investment>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="slick-controls slick-controls-white" data-hero-slider-controls>
                    <button class="slick-prev"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                            viewBox="0 0 48 48">
                            <g id="Group_1395" data-name="Group 1395" transform="translate(0.068)">
                                <path id="chevron_right_24dp_FILL0_wght100_GRAD0_opsz24"
                                    d="M1.371,6.99l6.3-6.3L6.99,0,0,6.99l6.99,6.99.685-.685Z"
                                    transform="translate(20.162 17.01)" />
                                <g id="Rectangle_701" data-name="Rectangle 701" transform="translate(-0.068)" fill="none"
                                    stroke="#000" stroke-width="1">
                                    <rect width="48" height="48" rx="24" stroke="none" />
                                    <rect x="0.5" y="0.5" width="47" height="47" rx="23.5" fill="none" />
                                </g>
                            </g>
                        </svg></button><button class="slick-next"><svg xmlns="http://www.w3.org/2000/svg" width="48"
                            height="48" viewBox="0 0 48 48">
                            <g id="Group_1395" data-name="Group 1395" transform="translate(0.068)">
                                <path id="chevron_right_24dp_FILL0_wght100_GRAD0_opsz24"
                                    d="M1.371,6.99l6.3-6.3L6.99,0,0,6.99l6.99,6.99.685-.685Z"
                                    transform="translate(20.162 17.01)" />
                                <g id="Rectangle_701" data-name="Rectangle 701" transform="translate(-0.068)" fill="none"
                                    stroke="#000" stroke-width="1">
                                    <rect width="48" height="48" rx="24" stroke="none" />
                                    <rect x="0.5" y="0.5" width="47" height="47" rx="23.5" fill="none" />
                                </g>
                            </g>
                        </svg></button>
                </div>
                <div class="position-absolute scroll-down-wrapper d-none d-lg-block">
                    <div class="container">
                        <div class="scroll-down">
                            <a href="#apartamenty" class="scroll-down-link text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="7.675" height="13.98"
                                    viewBox="0 0 7.675 13.98">
                                    <path id="chevron_right_24dp_FILL0_wght100_GRAD0_opsz24"
                                        d="M1.371,6.99l6.3-6.3L6.99,0,0,6.99l6.99,6.99.685-.685Z" fill="#fff" />
                                </svg>
                                <span>Zjedź niżej</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="investment-hero-label">
                <img src="{{ asset('images/investment-label-logo.svg') }}" alt="Apartamenty Poligonowa Etap 4"
                    class="img-fluid" width="205" height="62" loading="eager">
            </div>
        </section>

        <section class="py-3 bg-white  sticky-top investment-navbar-wrapper">
            <nav id="investment-navbar" class="navbar">
                <div class="container justify-content-center">
                    <div class="navbar-nav flex-wrap flex-row gap-3 justify-content-center">
                        <a class="nav-link nav-link-black" href="#apartamenty">Apartamenty</a>
                        <a class="nav-link nav-link-black" href="#mieszkania">Mieszkania</a>
                        <a class="nav-link nav-link-black" href="#atuty">Atuty</a>
                        <a class="nav-link nav-link-black d-none" href="#wizualizacje">Wizualizacje</a>
                        <a class="nav-link nav-link-black" href="{{ route('front.developro.investment.news', $investment->slug) }}">Dziennik budowy</a>
                        <a class="nav-link nav-link-black" href="#lokalizacja">Lokalizacja</a>
                        <a class="nav-link nav-link-black" href="#kontakt">Kontakt</a>
                    </div>
                </div>
            </nav>
        </section>

        <div data-bs-spy="scroll" data-bs-target="#investment-navbar">
            <section class="overflow-hidden position-relative grid-container pb-md-0  text-center text-md-start"
                id="apartamenty">
                <div class="container pt-md-50px pb-md-75px">
                    <div class="row align-items-center row-gap-50px">
                        <div class="col-12 col-md-6 col-lg-5">
                            <h2 class="h3 mb-40px">
                                <span class="fw-thin d-block fs-4" data-aos="fade-up">Apartamenty</span>
                                <span class="d-block text-uppercase" data-aos="fade-up">Poligonowa Etap 5</span>
                            </h2>
                            <div class="text-content fw-light" data-aos="fade">
                                <p>
                                    Etap 5 inwestycji Apartamenty Poligonowa to nowoczesny budynek w spokojnej dzielnicy
                                    Sławin w Lublinie. Oferuje 110 komfortowych mieszkań i 7 lokali usługowych. Przestronne,
                                    funkcjonalne wnętrza z dużymi przeszkleniami zapewniają komfort życia. Inwestycja łączy
                                    nowoczesną architekturę z atrakcyjną lokalizacją w pobliżu terenów zielonych i usług.
                                </p>
                                <p>
                                    <strong>
                                        Zakończenie budowy: IV kwartał 2026 r.
                                    </strong>
                                </p>
                            </div>
                            <div class="text-center text-md-start">
                                <a href="#mieszkania" class="custom-btn custom-btn-primary mt-4 mt-md-5">Sprawdź</a>
                            </div>
                        </div>
                        <?php
                        $slider_config = [
                            'mobileFirst' => true,
                            'slidesToScroll' => 1,
                            'dots' => false,
                            'infinite' => false,
                            'prevArrow' => '.slick-controls.slick-controls-interior .slick-prev',
                            'nextArrow' => '.slick-controls.slick-controls-interior .slick-next',
                            'responsive' => [
                                [
                                    'breakpoint' => 768,
                                    'settings' => [
                                        'slidesToShow' => 1.25,
                                    ],
                                ],
                                [
                                    'breakpoint' => 992,
                                    'settings' => [
                                        'slidesToShow' => 1.5,
                                    ],
                                ],
                            ],
                        ];
                        ?>
                        <div class="col-12 col-md-6 col-lg-5 offset-lg-2 min-h-600px">
                            <div class="slick-absolute-wrapper slick-interior-wrapper">
                                <div class="slick-controls slick-controls-interior">
                                    <button class="slick-prev"><svg xmlns="http://www.w3.org/2000/svg" width="48"
                                            height="48" viewBox="0 0 48 48">
                                            <g id="Group_1395" data-name="Group 1395" transform="translate(0.068)">
                                                <path id="chevron_right_24dp_FILL0_wght100_GRAD0_opsz24"
                                                    d="M1.371,6.99l6.3-6.3L6.99,0,0,6.99l6.99,6.99.685-.685Z"
                                                    transform="translate(20.162 17.01)" />
                                                <g id="Rectangle_701" data-name="Rectangle 701"
                                                    transform="translate(-0.068)" fill="none" stroke="#000"
                                                    stroke-width="1">
                                                    <rect width="48" height="48" rx="24" stroke="none" />
                                                    <rect x="0.5" y="0.5" width="47" height="47" rx="23.5"
                                                        fill="none" />
                                                </g>
                                            </g>
                                        </svg></button><button class="slick-next"><svg xmlns="http://www.w3.org/2000/svg"
                                            width="48" height="48" viewBox="0 0 48 48">
                                            <g id="Group_1395" data-name="Group 1395" transform="translate(0.068)">
                                                <path id="chevron_right_24dp_FILL0_wght100_GRAD0_opsz24"
                                                    d="M1.371,6.99l6.3-6.3L6.99,0,0,6.99l6.99,6.99.685-.685Z"
                                                    transform="translate(20.162 17.01)" />
                                                <g id="Rectangle_701" data-name="Rectangle 701"
                                                    transform="translate(-0.068)" fill="none" stroke="#000"
                                                    stroke-width="1">
                                                    <rect width="48" height="48" rx="24" stroke="none" />
                                                    <rect x="0.5" y="0.5" width="47" height="47" rx="23.5"
                                                        fill="none" />
                                                </g>
                                            </g>
                                        </svg></button>
                                </div>
                                <div class="slick-slider with-blurred-inactive "
                                    data-slick='<?= json_encode($slider_config) ?>'>
                                    <div class="pb-2">
                                        <div class="rounded-1 overflow-hidden">
                                            <a href="{{ asset('images/w-sprzedazy/poligonowa-5/slider-wiz-full-1.jpg') }}"
                                                class='glightbox'>
                                                <img src="{{ asset('images/w-sprzedazy/poligonowa-5/slider-wiz-1.jpg') }}"
                                                    alt="" width="555" height="512" loading="lazy">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="pb-2">
                                        <div class="rounded-1 overflow-hidden">
                                            <a href="{{ asset('images/w-sprzedazy/poligonowa-5/slider-wiz-full-2.jpg') }}"
                                                class='glightbox'>
                                                <img src="{{ asset('images/w-sprzedazy/poligonowa-5/slider-wiz-2.jpg') }}"
                                                    alt="" width="555" height="512" loading="lazy">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="pb-2">
                                        <div class="rounded-1 overflow-hidden">
                                            <a href="{{ asset('images/w-sprzedazy/poligonowa-5/slider-wiz-full-3.jpg') }}"
                                                class='glightbox'>
                                                <img src="{{ asset('images/w-sprzedazy/poligonowa-5/slider-wiz-3.jpg') }}"
                                                    alt="" width="555" height="512" loading="lazy">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="pb-2">
                                        <div class="rounded-1 overflow-hidden">
                                            <a href="{{ asset('images/w-sprzedazy/poligonowa-5/slider-wiz-full-4.jpg') }}"
                                                class='glightbox'>
                                                <img src="{{ asset('images/w-sprzedazy/poligonowa-5/slider-wiz-4.jpg') }}"
                                                    alt="" width="555" height="512" loading="lazy">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-50px py-md-60px">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-10 offset-md-1">
                            <div class="d-flex flex-column flex-lg-row gap-30px row-gap-30px row-gap-lg-75px">
                                <div data-aos="fade"
                                    class="d-flex flex-column text-center text-sm-start text-md-center text-lg-start justify-content-center align-items-center flex-sm-row flex-md-column flex-lg-row gap-30px">
                                    <div class="circle-icon-wrapper">
                                        <img src="{{ asset('images/komfort.svg') }}" alt="Ikona komfortu" width="65"
                                            height="70" class="img-fluid" loading="lazy">
                                    </div>
                                    <div class="text-content">
                                        <p class="fs-5 fw-normal mb-2">Komfort<br> i wygoda mieszkańców</p>
                                        <p>
                                            Inwestycja jest idealnym miejscem do wypoczynku i relaksu - tworzona dla osób
                                            ceniących wygodę i spokój.
                                        </p>
                                    </div>
                                </div>
                                <div data-aos="fade"
                                    class="d-flex flex-column text-center text-sm-start text-md-center text-lg-start justify-content-center align-items-center flex-sm-row flex-md-column flex-lg-row gap-30px">
                                    <div class="circle-icon-wrapper">
                                        <img src="{{ asset('images/architekt.svg') }}" alt="Ikona architekta"
                                            width="62" height="62" class="img-fluid" loading="lazy">
                                    </div>
                                    <div class="text-content">
                                        <p class="fs-5 fw-normal mb-2">Kameralny<br> 4-kondygnacyjny budynek</p>
                                        <p>
                                            Wielorodzinny budynek mieszkalny o oryginalnej architekturze i wyjątkowej
                                            kompozycji przestrzennej.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class=" padding-bottom-medium" id="mieszkania">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="h3 mb-40px text-center pb-md-30px">
                                <span class="fw-thin d-block fs-4" data-aos="fade-up">Znajdź wymarzone</span>
                                <span class="d-block text-uppercase" data-aos="fade-up">Mieszkanie</span>
                            </h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-10 offset-lg-1">
                            <div class="stacking-content-md">
                                <div class="position-relative" data-relative>
                                    <div id="investment-plan">
                                        <img src="{{ asset('/investment/plan/'.$investment->plan->file) }}"
                                             alt="Plan inwestycji {{$investment->name}}"
                                             class="w-100 h-100 object-fit-cover"
                                             loading="lazy"
                                             usemap="#invesmentplan"
                                             id="invesmentplan">
                                        <map name="invesmentplan">
                                            @foreach($investment->floors as $floor)
                                                @if($floor->html)
                                                    <area
                                                            shape="poly"
                                                            href="{{ route('front.developro.building-floor', ['slug' => $investment->slug, 'building_slug' => Str::slug($floor->building->name), 'floor' => $floor, 'floor_slug' => Str::slug($floor->name)]) }}"
                                                            title="{{$floor->name}}"
                                                            alt="floor-{{$floor->id}}"
                                                            data-item="{{$floor->id}}"
                                                            data-floornumber="{{$floor->id}}"
                                                            data-floortype="{{$floor->type}}"
                                                            coords="@if($floor->html) {{cords($floor->html)}} @endif">
                                                @endif
                                            @endforeach
                                        </map>
                                    </div>
                                </div>
                                <div
                                    class="text-white col-12 col-lg-10 col-xl-9 align-content-end mx-auto mb-3 mb-lg-4 bg-black bg-md-transparent">
                                    <x-search-form-investment></x-search-form-investment>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="properties">
                        <div class="col-12 col-lg-10 offset-lg-1">
                            <div class="text-end py-4 d-none">
                                <button data-layout-btn="layout-grid" class="layout-switcher-btn active"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                        viewBox="0 0 19 19">
                                        <g id="Group_1719" data-name="Group 1719" transform="translate(-1346 -1105)">
                                            <g id="Rectangle_871" data-name="Rectangle 871"
                                                transform="translate(1357 1116)" fill="none" stroke="#000"
                                                stroke-width="1">
                                                <rect width="8" height="8" stroke="none" />
                                                <rect x="0.5" y="0.5" width="7" height="7" fill="none" />
                                            </g>
                                            <g id="Rectangle_876" data-name="Rectangle 876"
                                                transform="translate(1357 1105)" fill="none" stroke="#000"
                                                stroke-width="1">
                                                <rect width="8" height="8" stroke="none" />
                                                <rect x="0.5" y="0.5" width="7" height="7" fill="none" />
                                            </g>
                                            <g id="Rectangle_877" data-name="Rectangle 877"
                                                transform="translate(1346 1116)" fill="none" stroke="#000"
                                                stroke-width="1">
                                                <rect width="8" height="8" stroke="none" />
                                                <rect x="0.5" y="0.5" width="7" height="7" fill="none" />
                                            </g>
                                            <g id="Rectangle_878" data-name="Rectangle 878"
                                                transform="translate(1346 1105)" fill="none" stroke="#000"
                                                stroke-width="1">
                                                <rect width="8" height="8" stroke="none" />
                                                <rect x="0.5" y="0.5" width="7" height="7" fill="none" />
                                            </g>
                                        </g>
                                    </svg></button><button data-layout-btn="layout-list" class="layout-switcher-btn"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="19" height="19"
                                        viewBox="0 0 19 19">
                                        <g id="Group_1718" data-name="Group 1718" transform="translate(-1373 -1105)">
                                            <g id="Rectangle_873" data-name="Rectangle 873"
                                                transform="translate(1373 1105)" fill="none" stroke="#000"
                                                stroke-width="1">
                                                <rect width="19" height="5" stroke="none" />
                                                <rect x="0.5" y="0.5" width="18" height="4" fill="none" />
                                            </g>
                                            <g id="Rectangle_874" data-name="Rectangle 874"
                                                transform="translate(1373 1112)" fill="none" stroke="#000"
                                                stroke-width="1">
                                                <rect width="19" height="5" stroke="none" />
                                                <rect x="0.5" y="0.5" width="18" height="4" fill="none" />
                                            </g>
                                            <g id="Rectangle_875" data-name="Rectangle 875"
                                                transform="translate(1373 1119)" fill="none" stroke="#000"
                                                stroke-width="1">
                                                <rect width="19" height="5" stroke="none" />
                                                <rect x="0.5" y="0.5" width="18" height="4" fill="none" />
                                            </g>
                                        </g>
                                    </svg></button>
                            </div>
                            <div data-layout="layout-list" class="properties-list mt-4">
                                @foreach ($filtered_properties as $p)
                                    @php
                                        $url = route('front.developro.building-property', [
                                            'slug' => $investment->slug,
                                            'building_slug' => Str::slug($p->building->name) ?? 'missing-building',
                                            'floor_slug' => Str::slug($p->floor->name) ?? 'missing-floor',
                                            'property' => $p->id,
                                            'property_slug' => Str::slug($p->name) ?? 'missing-property',
                                        ]);
                                    @endphp
                                    <x-property-list-item :p="$p" :url="$url"></x-property-list-item>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="gradient-bg-wrapper padding-top-medium" id="atuty">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="h3 mb-40px text-center pb-md-30px">
                                <span class="fw-thin d-block fs-4" data-aos="fade-up">Dlaczego warto wybrać</span>
                                <span class="d-block text-uppercase" data-aos="fade-up">NASZE INWESTYCJE?</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="container">
                        <div class="row justify-content-center row-cols-1 row-cols-md-2 row-cols-lg-3 row-gap-50px">
                            <div class="col" data-aos="fade">
                                <div class="why-investment-card d-flex flex-column h-100">
                                    <div class="d-flex gap-40px align-items-center mb-20px">
                                        <span class="fs-1 fw-light text-primary lh-1">01</span>
                                        <p class="fs-5 fw-bold mb-0">
                                            Klimat domu jednorodzinnego
                                        </p>
                                    </div>
                                    <p>
                                        Mieszkania z antresolą są świetną alternatywą dla domu w mieście. Na nasze
                                        dwupoziomowe apartamenty łączą w sobie klimat domu jednorodzinnego z wygodą
                                        standardowego mieszkania. Ponad to ich użytkownicy mogą cieszyć się poczuciem
                                        przestrzeni, jakie nie jest osiągalne w jednopoziomowym mieszkaniu.
                                    </p>
                                    <img src="{{ asset('images/why_investment_card_1.webp') }}"
                                        alt="Apartamenty z antresolą"
                                        class="img-fluid pt-3 pt-md-50px mt-auto d-block mx-auto" width="438"
                                        height="320" loading="lazy">
                                </div>
                            </div>
                            <div class="col" data-aos="fade">
                                <div class="why-investment-card d-flex flex-column h-100">
                                    <div class="d-flex gap-40px align-items-center mb-20px">
                                        <span class="fs-1 fw-light text-primary lh-1">02</span>
                                        <p class="fs-5 fw-bold mb-0">
                                            Dwie oddzielne strefy – część mieszkalna i biurowa
                                        </p>
                                    </div>
                                    <p>
                                        Przyszli właściciele dostają do dyspozycji dwie oddzielne przestrzenie bez skosów –
                                        do wykorzystania jako powierzchnie mieszkalne lub do pracy. Antresolę można bez
                                        trudu zaaranżować w taki sposób, by powstała tu np. pracownia.
                                    </p>
                                    <img src="{{ asset('images/why_investment_card_2.webp') }}"
                                        alt="Apartamenty z antresolą"
                                        class="img-fluid pt-3 pt-md-50px mt-auto d-block mx-auto" width="438"
                                        height="320" loading="lazy">
                                </div>
                            </div>
                            <div class="col" data-aos="fade">
                                <div class="why-investment-card d-flex flex-column h-100">
                                    <div class="d-flex gap-40px align-items-center mb-20px">
                                        <span class="fs-1 fw-light text-primary lh-1">03</span>
                                        <p class="fs-5 fw-bold mb-0">
                                            Niepowtarzalny design<br>i powiew luksusu
                                        </p>
                                    </div>
                                    <p class="">
                                        Tego typu mieszkania to nie tylko moda, lecz także zdecydowanie inne doświadczenie
                                        przestrzeni, która staje się inspirująca, niestandardowa, a jednocześnie pozwala na
                                        wiele niepowtarzalnych możliwości aranżacji.
                                    </p>
                                    <img src="{{ asset('images/why_investment_card_3.webp') }}"
                                        alt="Apartamenty z antresolą"
                                        class="img-fluid pt-3 pt-md-50px mt-auto d-block mx-auto" width="438"
                                        height="320" loading="lazy">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-custom-gray  text-center text-md-start">
                <div class="container pb-md-40px">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-5">
                            <h2 class="h3 mb-40px pb-md-30px">
                                <span class="fw-thin d-block fs-4" data-aos="fade-up">Zalety apartamentów</span>
                                <span class="d-block text-uppercase" data-aos="fade-up">POLIGONOWA</span>
                            </h2>
                        </div>
                    </div>
                    <?php
                    $items = [
                        [
                            'icon' => asset('images/metraz.svg'),
                            'title' => 'Różnorodny metraż',
                            'description' => 'zróżnicowany metraż apartamentów<br>od 34,87 m2 do 70,05 m2',
                        ],
                        [
                            'icon' => asset('images/parking.svg'),
                            'title' => 'Parking',
                            'description' => 'do każdego mieszkania przynależy przynajmniej<br>1 miejsce parkingowe',
                        ],
                        [
                            'icon' => asset('images/security.svg'),
                            'title' => 'Monitoring',
                            'description' => 'całodobowy<br>monitoring osiedla',
                        ],
                        [
                            'icon' => asset('images/village.svg'),
                            'title' => 'Okolica',
                            'description' => 'kameralna<br>i cicha okolica',
                        ],
                        [
                            'icon' => asset('images/plac_zabaw.svg'),
                            'title' => 'Plac zabaw',
                            'description' => 'plac zabaw<br>dla najmłodszych',
                        ],
                        [
                            'icon' => asset('images/branch.svg'),
                            'title' => 'Lokalizacja',
                            'description' => 'idealna lokalizacja łatwy<br>dojazd do centrum',
                        ],
                        [
                            'icon' => asset('images/modern.svg'),
                            'title' => 'Kameralna zabudowa',
                            'description' => 'niska skala zabudowy zapewnia komfort<br>i prywatność',
                        ],
                        [
                            'icon' => asset('images/hands.svg'),
                            'title' => 'Realizacja',
                            'description' => 'termin realizacji<br>to IV kwartał 2026 r.',
                        ],
                    ]; ?>
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xxl-4 row-gap-30px row-gap-lg-50px">
                        <?php foreach ($items as $item) : ?>
                        <div class="col">
                            <div data-aos="fade"
                                class="d-flex flex-column text-center justify-content-center align-items-center gap-4">
                                <div class="circle-icon-wrapper" style="--bg-color:var(--bs-white);">
                                    <img src="<?= $item['icon'] ?>" alt="Ikona komfortu" width="62" height="62"
                                        class="img-fluid" loading="lazy">
                                </div>
                                <div class="text-content">
                                    <p class="fs-5 fw-normal mb-2"><?= $item['title'] ?></p>
                                    <p>
                                        <?= $item['description'] ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <section class="position-relative stacking-content align-items-center py-0 blur-left">
                <img src="{{ asset('images/w-sprzedazy/poligonowa-5/wiz-3.jpg') }}" alt="O firmie"
                    class="w-100 h-100 object-fit-cover " width="1920" height="1080" loading="lazy">
                <div class="container text-white section-padding-block-large">
                    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                        <h2 class="h3 mb-40px">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">Apartamenty</span>
                            <span class="d-block text-uppercase" data-aos="fade-up">POLIGONOWA 5</span>
                        </h2>
                        <div class="text-content fw-light" data-aos="fade">
                            <p>
                                Etap 5 inwestycji Apartamenty Poligonowa to kolejny krok w rozwoju nowoczesnego osiedla,
                                które zyskało uznanie dzięki swojej wyjątkowej lokalizacji w spokojnej dzielnicy Sławin w
                                Lublinie. W ramach tego etapu powstanie czterokondygnacyjny budynek, w którym zaplanowano
                                110 komfortowych mieszkań o zróżnicowanych metrażach oraz 7 lokali usługowych
                                zlokalizowanych na parterze. Mieszkania w tym etapie będą jednopoziomowe, co podkreśla
                                funkcjonalność i wygodę zaprojektowanych przestrzeni.
                            </p>
                            <p>
                                Wszystkie mieszkania zostały zaprojektowane z myślą o praktyczności i estetyce. Przemyślane
                                układy pomieszczeń zapewniają wygodę codziennego życia, a duże przeszklenia gwarantują
                                doskonałe doświetlenie wnętrz. Budynek wyróżnia się nowoczesną architekturą oraz starannie
                                zaaranżowaną przestrzenią wspólną, która zapewnia mieszkańcom komfort i estetyczne
                                otoczenie.
                            </p>
                            <p>
                                Dodatkowym atutem jest obecność lokali usługowych na parterze, które zwiększają wygodę
                                codziennego życia, oferując przyszłym mieszkańcom dostęp do potrzebnych usług w zasięgu
                                ręki.
                            </p>
                            <p>
                                Inwestycja stanowi doskonałą propozycję zarówno dla osób poszukujących swojego wymarzonego
                                mieszkania, jak i dla inwestorów zainteresowanych lokalami w dynamicznie rozwijającej się
                                części miasta.
                            </p>
                            <p>
                                Atrakcyjna lokalizacja inwestycji to idealne miejsce zarówno dla rodzin, jak i osób
                                aktywnych zawodowo. W pobliżu znajdują się tereny zielone, ścieżki rowerowe oraz liczne
                                punkty usługowe, a także szkoły i przedszkola, co dodatkowo podnosi atrakcyjność
                                lokalizacji.
                            </p>
                            <p>
                                <strong>
                                    Termin realizacji tego etapu przewidziano na IV kwartał 2026 roku.
                                </strong>
                            </p>
                            <div class="text-center text-md-start">
                                <a href="#mieszkania" class="custom-btn custom-btn-primary mt-4">Sprawdź</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            @if (false)
                <section
                    class="overflow-hidden position-relative bg-custom-gray grid-container section-padding-top-medium text-center text-md-start"
                    id="wizualizacje">
                    <div class="container">
                        <div class="row align-items-center row-gap-50px">
                            <div class="col-12 col-md-6 col-lg-5">
                                <h2 class="h3 mb-40px">
                                    <span class="fw-thin d-block fs-4" data-aos="fade-up">Wnętrza zaprojektowane</span>
                                    <span class="d-block text-uppercase" data-aos="fade-up">Z PASJĄ DO DESIGNU</span>
                                </h2>
                                <div class="text-content fw-light" data-aos="fade">

                                    <a href="#" class="custom-btn custom-btn-primary mt-4">Zobacz więcej</a>
                                </div>
                            </div>
                            <?php
                            $slider_config = [
                                'mobileFirst' => true,
                                'slidesToScroll' => 1,
                                'dots' => false,
                                'infinite' => false,
                                'prevArrow' => '.slick-controls.slick-controls-interior .slick-prev',
                                'nextArrow' => '.slick-controls.slick-controls-interior .slick-next',
                                'responsive' => [
                                    [
                                        'breakpoint' => 768,
                                        'settings' => [
                                            'slidesToShow' => 1.25,
                                        ],
                                    ],
                                    [
                                        'breakpoint' => 992,
                                        'settings' => [
                                            'slidesToShow' => 1.5,
                                        ],
                                    ],
                                ],
                            ];
                            ?>
                            <div class="col-12 col-md-6 col-lg-5 offset-lg-2 min-h-600px">
                                <div class="slick-absolute-wrapper slick-interior-wrapper">
                                    <div class="slick-controls slick-controls-interior">
                                        <button class="slick-prev"><svg xmlns="http://www.w3.org/2000/svg" width="48"
                                                height="48" viewBox="0 0 48 48">
                                                <g id="Group_1395" data-name="Group 1395" transform="translate(0.068)">
                                                    <path id="chevron_right_24dp_FILL0_wght100_GRAD0_opsz24"
                                                        d="M1.371,6.99l6.3-6.3L6.99,0,0,6.99l6.99,6.99.685-.685Z"
                                                        transform="translate(20.162 17.01)" />
                                                    <g id="Rectangle_701" data-name="Rectangle 701"
                                                        transform="translate(-0.068)" fill="none" stroke="#000"
                                                        stroke-width="1">
                                                        <rect width="48" height="48" rx="24"
                                                            stroke="none" />
                                                        <rect x="0.5" y="0.5" width="47" height="47"
                                                            rx="23.5" fill="none" />
                                                    </g>
                                                </g>
                                            </svg></button><button class="slick-next"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                                viewBox="0 0 48 48">
                                                <g id="Group_1395" data-name="Group 1395" transform="translate(0.068)">
                                                    <path id="chevron_right_24dp_FILL0_wght100_GRAD0_opsz24"
                                                        d="M1.371,6.99l6.3-6.3L6.99,0,0,6.99l6.99,6.99.685-.685Z"
                                                        transform="translate(20.162 17.01)" />
                                                    <g id="Rectangle_701" data-name="Rectangle 701"
                                                        transform="translate(-0.068)" fill="none" stroke="#000"
                                                        stroke-width="1">
                                                        <rect width="48" height="48" rx="24"
                                                            stroke="none" />
                                                        <rect x="0.5" y="0.5" width="47" height="47"
                                                            rx="23.5" fill="none" />
                                                    </g>
                                                </g>
                                            </svg></button>
                                    </div>
                                    <div class="slick-slider with-blurred-inactive "
                                        data-slick='<?= json_encode($slider_config) ?>'>
                                        <div class="pb-2">
                                            <div class="rounded-1 overflow-hidden">
                                                <img src="{{ asset('images/wnetrze_2.jpg') }}" alt=""
                                                    width="555" height="512" loading="lazy">
                                                <div class="py-3">
                                                    <p class="fs-5 fw-bold mb-0">
                                                        Wizualizacja wnętrza
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pb-2">
                                            <div class="rounded-1 overflow-hidden">
                                                <img src="{{ asset('images/wnetrze_2.jpg') }}" alt=""
                                                    width="555" height="512" loading="lazy">
                                                <div class="py-3">
                                                    <p class="fs-5 fw-bold mb-0">
                                                        Wizualizacja wnętrza
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pb-2">
                                            <div class="rounded-1 overflow-hidden">
                                                <img src="{{ asset('images/wnetrze_2.jpg') }}" alt=""
                                                    width="555" height="512" loading="lazy">
                                                <div class="py-3">
                                                    <p class="fs-5 fw-bold mb-0">
                                                        Wizualizacja wnętrza
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pb-2">
                                            <div class="rounded-1 overflow-hidden">
                                                <img src="{{ asset('images/wnetrze_2.jpg') }}" alt=""
                                                    width="555" height="512" loading="lazy">
                                                <div class="py-3">
                                                    <p class="fs-5 fw-bold mb-0">
                                                        Wizualizacja wnętrza
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
            <section class="gradient-bg-wrapper reversed d-none" style="--first-color-ratio: 60%;" id="aktualnosci">
                <div class="container pt-md-30px">
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
                            @foreach ($last_news as $a)
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

            <section
                class="overflow-hidden padding-block-medium position-relative grid-container  text-center text-md-start"
                id="lokalizacja">
                <div class="container">
                    <div class="row align-items-center row-gap-50px">
                        <div class="col-12 col-md-6 col-xl-5">
                            <h2 class="h3 mb-40px">
                                <span class="fw-thin d-block fs-4" data-aos="fade-up">Miejsce dla każdego</span>
                                <span class="d-block text-uppercase" data-aos="fade-up">Lokalizacja</span>
                            </h2>
                            <div class="text-content fw-light" data-aos="fade">
                                <p>Wielorodzinny budynek mieszkalny zlokalizowano w dogodnej odległości od Centrum Lublina,
                                    zapewniając jednocześnie korzystny czas dojazdu oraz przyjazne, ciche otoczenie z dala
                                    od zgiełku miasta.</p>
                                <p>Apartamenty Poligonowa znajdują się blisko głównych arterii komunikacyjnych i przystanków
                                    autobusowych, co umożliwia szybkie i wygodne połączenia z innymi dzielnicami miasta.</p>
                            </div>
                            <div class="d-flex flex-column flex-md-row align-items-center gap-3 mt-30px mt-md-50px">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="30.004"
                                    viewBox="0 0 22 30.004">
                                    <path id="map" d="M16,1A11,11,0,0,0,5,12c0,5.81,9.8,18.1,10.22,18.63a1,1,0,0,0,1.56,0C17.2,30.1,27,17.81,27,12A11,11,0,0,0,16,1Zm0,14a4,4,0,1,1,4-4A4,4,0,0,1,16,15Z" transform="translate(-5 -1)" />
                                </svg>
                                <p class="mb-0 fw-bold">ul. Poligonowa,<br>20-817 Lublin</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-xl-6 offset-xl-1">
                            <div class="investment-map" id="investment-map"></div>
                        </div>
                    </div>
                </div>
            </section>
            <div id="kontakt">
                <x-cta :investmentName="$investment->name" :investmentId="$investment->id" pageTitle="Opis inwestycji - Poligonowa 5" back="true"></x-cta>
            </div>
        </div>
    </main>

    <script src="{{ asset('poligonowa/leaflet.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('poligonowa/leaflet.min.css') }}">
    <script >
        document.addEventListener('DOMContentLoaded', () => {


            const icon = L.icon({
                iconUrl: '{{asset('images/marker-icon.svg')}}',
                iconSize: [46, 66],
                iconAnchor: [23, 66],
                popupAnchor: [0, -66]
            })
            const CORDS = [51.271682, 22.524118];
            const investmentMap = L.map('investment-map', {
                center: CORDS,
                zoom: 17
            });

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(investmentMap);

            L.marker(CORDS, {
                icon: icon
            }).addTo(investmentMap).bindPopup('Apartamenty Poligonowa 5').openPopup();
        })
    </script>
    <script type="module">
        import {generateTooltip, handleInvestmentFloors, handleMouseMove}  from '{{asset('poligonowa/properties-map.js')}}'
       
        const floorsData = [{
                    id: 0,

                    points: "622,254.609375 784,363.609375 992,347.609375 986,371.609375 785,381.609375 620,265.609375 621,251.609375",

                    tooltip: generateTooltip("Budynek B", "Parter"),
                },

                {
                    id: 1,

                    points: "621,239.609375 621,232.609375 783,326.609375 993,313.609375 992,332.609375 786,348.609375 622,242.609375 622,233.609375",

                    tooltip: generateTooltip("Budynek B", "1 piętro"),
                },

                {
                    id: 2,

                    points: "622,221.609375 623,212.609375 786,295.609375 1000,283.609375 1000,298.609375 787,309.609375 623,219.609375 623,211.609375",

                    tooltip: generateTooltip("Budynek B", "2 piętro"),
                },

                {
                    id: 3,

                    points: "623,198.609375 623,190.609375 791,262.609375 1003,250.609375 1002,266.609375 789,277.609375 622,199.609375 623,188.609375",

                    tooltip: generateTooltip("Budynek B", "3 piętro"),
                },
            ];

            handleInvestmentFloors(floorsData);
    </script>
@endsection
@push('scripts')
    <script src="{{ asset('/js/plan/imagemapster.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/plan/plan.js') }}" charset="utf-8"></script>
@endpush