@extends('layouts.page', ['body_class' => 'media'])

@section('meta_title', 'Media o nas')
{{-- @section('seo_title', $page->meta_title) --}}
{{-- @section('seo_description', $page->meta_description) --}}

@section('content')
    <div class="breadcrumbs breadcrumbs-news">
        <div class="container">
            <div class="d-flex flex-wrap gap-1 row-gap-2">
                <a href="/" class="breadcrumbs-link">Strona główna</a>
                <span class="breadcrumbs-separator">/</span>
                <span class="breadcrumbs-current">Media o nas</span>
            </div>
        </div>
    </div>

    <main>
        @if(false)
        <section class="section-first padding-bottom-medium">
            <div class="container">
                <div class="row row-gap-50px">
                    <div class="col-12 col-md-6 col-lg-5">
                        <h1 class="h3 mb-30px text-center text-md-start">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">Media</span>
                            <span class="d-block text-uppercase" data-aos="fade-up">O nas</span>
                        </h1>

                        {{-- Ukryte dopóki nie dostarczą grafik --}}
                        <p class="fs-5 fw-normal mb-md-40px text-center text-md-start">Nasi partnerzy medialni</p>
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-30px">
                            <img src="{{ asset('images/media_o_nas_logo.png') }}" width="117" height="40"
                                loading="eager" alt="" class="img-fluid">
                            <img src="{{ asset('images/media_o_nas_logo.png') }}" width="117" height="40"
                                loading="eager" alt="" class="img-fluid">
                            <img src="{{ asset('images/media_o_nas_logo.png') }}" width="117" height="40"
                                loading="eager" alt="" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-6 offset-lg-1 text-center">
                        <img src="{{ asset('images/media_o_nas_hero.jpg') }}" width="672" height="527" loading="eager"
                            alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </section>
        @endif

        <section class="section-first overflow-hidden position-relative grid-container text-center text-md-start">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <h2 class="h3 mb-50px text-center">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">RWP</span>
                            <span class="d-block text-uppercase" data-aos="fade-up">Media o nas</span>
                        </h2>
                    </div>

                    <?php
                    $slides = [
                        [
                            'img' => asset('images/media-o-nas/08.01.2024.png'),
                            'date' => '08.01.2024',
                            'title' => 'Apartamenty Poligonowa z kolejnym etapem',
                            'url' => 'https://projektinwestor.pl/aktualnosc/lublin-apartamenty-poligonowa-z-kolejnym-etapem',
                        ],
                        [
                            'img' => asset('images/media-o-nas/15.03.2021.png'),
                            'date' => '15.03.2021',
                            'title' => 'Apartamenty Poligonowa w Lublinie - z akcentem na funkcjonalność',
                            'url' => 'https://www.housemarket.pl/inwestycje/102/apartamenty_poligonowa_w_lublinie_z_akcentem_na_funkcjonalnosc,26796.html',
                        ],
                        [
                            'img' => asset('images/media-o-nas/08.02.2021.png'),
                            'date' => '08.02.2021',
                            'title' => 'Apartamenty Poligonowa pną się w górę',
                            'url' => 'https://www.housemarket.pl/inwestycje/102/apartamenty_poligonowa_pna_sie_w_gore,26388.html',
                        ],
                        [
                            'img' => asset('images/media-o-nas/21.12.2020.png'),
                            'date' => '21.12.2020',
                            'title' => 'Apartamenty Poligonowa: rusza przedsprzedaż kolejnego etapu inwestycji',
                            'url' => 'https://manager24.pl/apartamenty-poligonowa-rusza-przedsprzedaz-kolejnego-etapu-inwestycji/',
                        ],
                        [
                            'img' => asset('images/media-o-nas/21.09.2020.png'),
                            'date' => '21.09.2020',
                            'title' => 'Apartamenty Poligonowa w budowie',
                            'url' => 'https://www.nieruchomosci.biz/artykuly/szczegoly/65123_apartamenty-poligonowa-w-budowie',
                        ],
                        [
                            'img' => asset('images/media-o-nas/18.09.2020.png'),
                            'date' => '18.09.2020',
                            'title' => 'Nowa inwestycja w Lublinie - Apartamenty Poligonowa',
                            'url' => 'https://www.propertyjournal.pl/nowa-inwestycja-w-lublinie-apartamenty-poligonowa/',
                        ],
                        [
                            'img' => asset('images/media-o-nas/03.09.2020.png'),
                            'date' => '03.09.2020',
                            'title' => 'Oferta Apartamenty Poligonowa powiększona o 41 nowych mieszkań',
                            'url' => 'https://www.nieruchomosci.egospodarka.pl/165820,Oferta-Apartamenty-Poligonowa-powiekszona-o-41-nowych-mieszkan,1,80,1.html',
                        ],
                        [
                            'img' => asset('images/media-o-nas/01.09.2020.png'),
                            'date' => '01.09.2020',
                            'title' => 'II etap Apartamentów Poligonowa w Lublinie w budowie',
                            'url' => 'https://inzynierbudownictwa.pl/ii-etap-apartamentow-poligonowa-w-lublinie-w-budowie/',
                        ],
                        [
                            'img' => asset('images/media-o-nas/09.08.2020.png'),
                            'date' => '09.08.2020',
                            'title' => 'Apartamenty Poligonowa mają generalnego wykonawcę',
                            'url' => 'https://www.rp.pl/nieruchomosci/art8855481-apartamenty-poligonowa-maja-generalnego-wykonawce',
                        ],
                        [
                            'img' => asset('images/media-o-nas/07.08.2020.png'),
                            'date' => '07.08.2020',
                            'title' => 'Apartamenty Poligonowa z wykonawcą. Rusza I etap inwestycji',
                            'url' => 'https://www.urbanity.pl/lubelskie/lublin/apartamenty-poligonowa-z-wykonawca-rusza-i-etap-inwestycji,w18728',
                        ],
                        [
                            'img' => asset('images/media-o-nas/13.07.2020.png'),
                            'date' => '13.07.2020',
                            'title' => 'Apartamenty Poligonowa: Inteligentne mieszkania to wyższy poziom nieruchomości',
                            'url' => 'https://manager24.pl/apartamenty-poligonowa-inteligentne-mieszkania-to-wyzszy-poziom-nieruchomosci/',
                        ],
                        [
                            'img' => asset('images/media-o-nas/26.06.2020.png'),
                            'date' => '26.06.2020',
                            'title' => 'Apartamenty Poligonowa w Lublinie',
                            'url' => 'https://inzynierbudownictwa.pl/apartamenty-poligonowa-w-lublinie/',
                        ],
                        [
                            'img' => asset('images/media-o-nas/26.06.2020-2.png'),
                            'date' => '26.06.2020',
                            'title' => 'Apartamenty Poligonowa - wyższa jakość inwestycji w Lublinie',
                            'url' => 'https://www.nieruchomosci.biz/artykuly/szczegoly/64108_apartamenty-poligonowa-wyzsza-jakosc-inwestycji-w-lublinie',
                        ],
                        [
                            'img' => asset('images/media-o-nas/24.06.2020.png'),
                            'date' => '24.06.2020',
                            'title' => 'Apartamenty Poligonowa - nowe mieszkania w Lublinie',
                            'url' => 'https://www.nieruchomosci.egospodarka.pl/164504,Apartamenty-Poligonowa-nowe-mieszkania-w-Lublinie,1,80,1.html',
                        ],
                        [
                            'img' => asset('images/media-o-nas/23.06.2020.png'),
                            'date' => '23.06.2020',
                            'title' => 'Apartamenty Poligonowa rosną w Lublinie',
                            'url' => 'https://www.rp.pl/nieruchomosci/art657221-apartamenty-poligonowa-rosna-w-lublinie',
                        ],
                        [
                            'img' => asset('images/media-o-nas/23.06.2020-2.png'),
                            'date' => '23.06.2020',
                            'title' => 'W Lublinie powstanie inwestycja Apartamenty Poligonowa',
                            'url' => 'https://www.urbanity.pl/lubelskie/lublin/w-lublinie-powstanie-inwestycja-apartamenty-poligonowa,w18574',
                        ],
                        [
                            'img' => asset('images/media-o-nas/23.06.2020-3.png'),
                            'date' => '23.06.2020',
                            'title' => 'RWP Development zabiera się za Apartamenty Poligonowa',
                            'url' => 'https://www.projektinwestor.pl/aktualnosc/lublin-rwp-development-zabiera-sie-za-apartamenty-poligonowa',
                        ],
                        [
                            'img' => asset('images/media-o-nas/23.06.2020-4.png'),
                            'date' => '23.06.2020',
                            'title' => 'Inwestycja Apartamenty Poligonowa Etap III',
                            'url' => 'https://www.infoinwest.pl/inwestycje-budowlane/inwestycja,apartamenty-poligonowa-etap-iii,lublin,605902.html',
                        ],
                        [
                            'img' => asset('images/media-o-nas/23.06.2020-5.png'),
                            'date' => '23.06.2020',
                            'title' => 'Inwestycja Apartamenty Poligonowa Etap II',
                            'url' => 'https://www.infoinwest.pl/inwestycje-budowlane/inwestycja,apartamenty-poligonowa-etap-ii,lublin,605895.html',
                        ],
                    ];
                    // Sort by date
                    usort($slides, function ($a, $b) {
                        return strtotime($b['date']) - strtotime($a['date']);
                    });
                    ?>
                    <div class="col-12 col-md-10 col-xl-7">
                        <ul class="list-unstyled media-list text-start">
                            @foreach ($slides as $row)
                                <li class="mb-2"><a href="{{ $row['url'] }}" target="_blank" class="icon-link"><svg aria-hidden="true" class="bi" viewBox="0 0 320 512" xmlns="http://www.w3.org/2000/svg"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path></svg> {{ $row['title'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section class="padding-block-medium gradient-bg-wrapper" style="--first-color-ratio: 65%;">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="h3 mb-50px text-center">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">Bądź na bieżąco</span>
                            <span class="d-block text-uppercase" data-aos="fade-up">zaobserwuj nas!</span>
                        </h2>
                    </div>
                    <div class="col-12">
                        <div class="row justify-content-center row-gap-30px">
                            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="social-media-card" data-aos="fade">
                                    <p class="title">Facebook</p>
                                    <img src="{{ asset('images/fb_card_img.svg') }}" width="159" height="159" class="img-fluid" alt="">
                                    <p class="subtitle">@ApartamentyPoligonowa</p>
                                    <a href="{{ settings()->get("social_facebook") }}" target="_blank" class="custom-btn custom-btn-secondary">Zaobserwuj</a>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                                <div class="social-media-card" data-aos="fade">
                                    <p class="title">Instagram</p>
                                    <img src="{{ asset('images/ig_card_img.svg') }}" width="159" height="159" class="img-fluid" alt="">
                                    <p class="subtitle">@apartamentypoligonowa</p>
                                    <a href="{{ settings()->get("social_instagram") }}" target="_blank" class="custom-btn custom-btn-secondary">Zaobserwuj</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pb-md-50px padding-top-medium bg-custom-gray">
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
                        <div class="col" data-aos="fade">
                            @foreach($last_news as $a)
                                <div class="col" data-aos="fade">
                                    <x-news-list-item :article="$a"></x-news-list-item>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center mt-30px">
                            <a href="/aktualnosci/" class="custom-btn custom-btn-primary mt-3">Zobacz więcej</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="pb-md-20px bg-custom-gray">
            <x-cta pageTitle="Media o nas" back="true"></x-cta>
        </div>
    </main>

@endsection
