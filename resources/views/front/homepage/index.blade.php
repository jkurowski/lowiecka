@extends('layouts.homepage')

@section('content')
    <main>
        <div id="slider">
            <div class="container h-100">
                <div class="row h-100">
                    <div class="col-12 col-sm-7 col-md-5">
                        <div class="slider-apla">
                            <span class="sub-header">Skierniewice</span>
                            <h1>Łowicka <span class="text-gold">100</span></h1>
                            <h2>nowoczesne miejsce do życia</h2>
                            <a href="" class="bttn mt-3 mt-lg-5">SPRAWDŹ <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></a>
                        </div>
                    </div>
                </div>
            </div>
            <img src="{{ asset('images/slider.jpg') }}" alt="">
        </div>

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div id="main-search">
                        <div class="row">
                            <div class="col-12 mb-3 mb-lg-4">
                                <h3>Znajdź wymarzone mieszkanie</h3>
                            </div>
                        </div>
                        <form class="row" method="get" action="{{ route('front.developro.show') }}">
                            <div class="col-6 col-lg dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Powierzchnia</a>
                                <ul class="dropdown-menu">
                                    <li data-value=""><a class="dropdown-item" href="#">Wszystkie</a></li>
                                    <li data-value="29-40"><a class="dropdown-item" href="#">29-40 m<sup>2</sup></a></li>
                                    <li data-value="41-60"><a class="dropdown-item" href="#">41-60 m<sup>2</sup></a></li>
                                    <li data-value="61-80"><a class="dropdown-item" href="#">61-80 m<sup>2</sup></a></li>
                                    <li data-value="81-110"><a class="dropdown-item" href="#">81-110 m<sup>2</sup></a></li>
                                </ul>
                                <input type="hidden" name="area" value="">
                            </div>
                            <div class="col-6 col-lg dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Piętro</a>
                                <ul class="dropdown-menu">
                                    <li data-value=""><a class="dropdown-item" href="#">Wszystkie</a></li>
                                    <li data-value="1"><a class="dropdown-item" href="#">Parter</a></li>
                                    <li data-value="2"><a class="dropdown-item" href="#">Piętro 1</a></li>
                                    <li data-value="3"><a class="dropdown-item" href="#">Piętro 2</a></li>
                                    <li data-value="4"><a class="dropdown-item" href="#">Piętro 3</a></li>
                                    <li data-value="5"><a class="dropdown-item" href="#">Piętro 4</a></li>
                                    <li data-value="6"><a class="dropdown-item" href="#">Piętro 5</a></li>
                                </ul>
                                <input type="hidden" name="floor" value="">
                            </div>
                            <div class="col-6 col-lg dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Pokoje</a>
                                <ul class="dropdown-menu">
                                    <li data-value=""><a class="dropdown-item" href="#">Wszystkie</a></li>
                                    <li data-value="1"><a class="dropdown-item" href="#">1</a></li>
                                    <li data-value="2"><a class="dropdown-item" href="#">2</a></li>
                                    <li data-value="3"><a class="dropdown-item" href="#">3</a></li>
                                    <li data-value="4"><a class="dropdown-item" href="#">4</a></li>
                                    <li data-value="5"><a class="dropdown-item" href="#">5</a></li>
                                </ul>
                                <input type="hidden" name="rooms" value="">
                            </div>
                            <div class="col-6 col-lg dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Status</a>
                                <ul class="dropdown-menu">
                                    <li data-value=""><a class="dropdown-item" href="#">Wszystkie</a></li>
                                    <li data-value="1"><a class="dropdown-item" href="#">Dostępne</a></li>
                                    <li data-value="2"><a class="dropdown-item" href="#">Rezerwacja</a></li>
                                    <li data-value="3"><a class="dropdown-item" href="#">Sprzedane</a></li>
                                </ul>
                                <input type="hidden" name="status" value="">
                            </div>
                            <div class="col-12 col-lg">
                                <button type="submit" class="bttn w-100">SZUKAJ <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <section>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-mb-4 d-flex align-items-center">
                        <div class="section-title justify-content-start mb-0">
                            <div class="sub-section-title">
                                <span>ATUTY</span>
                            </div>
                            <h2 class="text-start">Dlaczego warto zamieszkać <br>w Łowicka <span class="text-gold">100</span>?</h2>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mt-4 mt-mb-0">
                        <div class="atut">
                            <img src="{{ asset('images/doskonala-lokalizacja.png') }}" alt="">
                            <h3>Doskonała lokalizacja</h3>
                            <p>Blisko centrum, sklepów, szkół i komunikacji miejskiej. Sąsiedztwo terenów zielonych i parku sprzyja relaksowi i aktywnemu wypoczynkowi.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mt-4 mt-mb-0">
                        <div class="atut">
                            <img src="{{ asset('images/komfortowe-mieszkania.png') }}" alt="">
                            <h3>Komfortowe mieszkania</h3>
                            <p>Przemyślane układy, prywatne ogródki na parterze i przestronne balkony na wyższych piętrach. Idealna przestrzeń do życia i odpoczynku.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mt-4 mt-mb-0">
                        <div class="atut">
                            <img src="{{ asset('images/ekologiczne-rozwiazania.png') }}" alt="">
                            <h3>Ekologiczne rozwiązania</h3>
                            <p>Fotowoltaika, filtry antysmogowe i smart home zmniejszają koszty energii i zapewniają zdrowe, ekologiczne warunki życia.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mt-4 mt-mb-0">
                        <div class="atut">
                            <img src="{{ asset('images/nowoczesna-infrastruktura.png') }}" alt="">
                            <h3>Nowoczesna infrastruktura</h3>
                            <p>Podziemna hala garażowa, ładowarki do aut elektrycznych, rowerownia i windy zapewniają wygodę i funkcjonalność na co dzień.</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mt-4 mt-mb-0">
                        <div class="atut">
                            <img src="{{ asset('images/bezpieczenstwo-i-wspolnota.png') }}" alt="">
                            <h3>Bezpieczeństwo i wspólnota</h3>
                            <p>Zamknięte osiedle, monitoring, wideodomofony i przestrzenie wspólne sprzyjają spokojowi oraz budowaniu sąsiedzkich relacji.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pb-0 pt-at-lg">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-xl-6">
                        <div class="section-title justify-content-start section-title-xl">
                            <div class="sub-section-title">
                                <span>O APARTAMENTACH</span>
                            </div>
                            <h2 class="text-start">Nowoczesne apartamenty stworzone z myślą o komforcie</h2>
                        </div>
                    </div>
                    <div class="col-12 col-xl-6">
                        <div class="stats-container">
                            <div class="number-box">
                                <h3><span class="number">66</span> apartamentów</h3>
                            </div>
                            <div class="number-box">
                                <h3><span class="number">5</span> kondygnacji</h3>
                            </div>
                            <div class="number-box">
                                <h3><span class="number">2</span> windy</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-0 mt-xl-4">
            <div class="container container-right-after">
                <div class="row">
                    <div class="col-12 col-xl-7">
                        <div class="golden-lines">
                            <div class="golden-line-4">
                                <img src="{{ asset('images/zote-linie-4.png') }}" alt="">
                            </div>
                            <div class="golden-line-3">
                                <img src="{{ asset('images/zote-linie-3.png') }}" alt="">
                            </div>
                            <img src="{{ asset('images/inwestycja-2.jpg') }}" alt="" width="740" height="530">
                        </div>
                    </div>
                    <div class="col-12 col-xl-5 d-flex align-items-center">
                        <div class="section-text mt-5 mt-xl-0">
                            <p>Łowicka 100 to nowoczesna inwestycja, która łączy komfort z ekologicznymi rozwiązaniami. Obejmuje 66 mieszkań o przemyślanych układach, dostosowanych do potrzeb singli i rodzin. Lokale na parterze posiadają prywatne ogródki, a na wyższych piętrach znajdują się przestronne balkony – idealne do relaksu i spędzania czasu na świeżym powietrzu.</p>
                            <p>&nbsp;</p>
                            <p>Budynek został wyposażony w innowacyjne technologie zwiększające wygodę i oszczędność. Panele fotowoltaiczne obniżają koszty energii, filtry antysmogowe dbają o jakość powietrza, a inteligentny system smart home umożliwia łatwe zarządzanie domem i optymalizację zużycia mediów. Łowicka 100 to przestrzeń stworzona z myślą o wygodnym, nowoczesnym i zdrowym stylu życia.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-5">
                        <div class="section-title justify-content-start">
                            <div class="sub-section-title">
                                <span>INFRASTRUKTURA</span>
                            </div>
                            <h2 class="text-start">Funkcjonalna infrastruktura dla Twojej wygody</h2>
                        </div>
                        <div class="section-text">
                            <p>W budynku znajduje się podziemna, przestronna hala garażowa na 54 stanowiska, zapewniająca bezpieczeństwo pojazdów mieszkańców, a na ogrodzonym parkingu naziemnym - zasilanie do podłączenia ładowarki samochodów elektrycznych, które wspierają ekologiczny transport.</p>
                            <p>&nbsp;</p>
                            <p>Do dyspozycji mieszkańców znajduje się także rowerownia, wózkownia i łatwo dostępne komórki lokatorskie, zlokalizowane na tej samej kondygnacji co mieszkanie, w ilości odpowiadającej liczbie mieszkań.</p>
                            <p>&nbsp;</p>
                            <p>Dostępne liczne udogodnienia m.in. dwie windy, zewnętrzne miejsca postojowe, wysoki standard wykończenia balkonów - oświetlenie zewnętrzne, gniazdo elektryczne i wykończona posadzka, a także indywidualny smart home wewnątrz mieszkań.</p>
                        </div>
                    </div>
                    <div class="col-12 col-lg"></div>
                    <div class="col-12 col-lg-6">
                        <div class="iconbox-container">
                            <div class="icon-box">
                                <img src="{{ asset('images/car-parking@2x.png') }}" alt="" width="77" height="77">
                                <h3>54 miejsca w parkingu wewnętrznym</h3>
                            </div>
                            <div class="icon-box">
                                <img src="{{ asset('images/parking-1@2x.png') }}" alt="" width="77" height="77">
                                <h3>13 zewnętrznych miejsc postojowych</h3>
                            </div>
                            <div class="icon-box">
                                <img src="{{ asset('images/electric-vehicle@2x.png') }}" alt="" width="77" height="77">
                                <h3>Zasilanie do podłączenia ładowarki samochodów elektrycznych</h3>
                            </div>
                            <div class="icon-box">
                                <img src="{{ asset('images/balcony@2x.png') }}" alt="" width="77" height="77">
                                <h3>Balkon z oświetleniem zewnętrznym, gniazdem elektrycznym i posadzką</h3>
                            </div>
                            <div class="icon-box">
                                <img src="{{ asset('images/locker@2x.png') }}" alt="" width="77" height="77">
                                <h3>Komórki lokatorskie</h3>
                            </div>
                            <div class="icon-box">
                                <img src="{{ asset('images/parking-1@2x.png') }}" alt="" width="77" height="77">
                                <h3>Rowerownia, wózkownia</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title section-title-xl">
                            <div class="sub-section-title">
                                <span>TYPY MIESZKAŃ</span>
                            </div>
                            <h2>Wybierz apartament dla siebie!</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div id="showrooms" class="container-fluid container-1730">
                <div class="row">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <a href="{{ route('front.developro.show') }}?rooms=1">
                            <div class="apartament-type apartament-type-1">
                                <span class="area"><b>28 m<sup>2</sup></b></span>

                                <div class="apartament-type-footer">
                                    <h2><span>Apartament</span> 1-pokojowy</h2>
                                    <span class="bttn bttn-border-blue">SPRAWDŹ <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></span>
                                </div>
                                <span class="number">1</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <a href="{{ route('front.developro.show') }}?rooms=2">
                            <div class="apartament-type apartament-type-2">
                                <span class="area"><b>43 - 50 m<sup>2</sup></b></span>

                                <div class="apartament-type-footer">
                                    <h2><span>Apartament</span> 2-pokojowy</h2>
                                    <span class="bttn bttn-border-blue">SPRAWDŹ <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></span>
                                </div>
                                <span class="number">2</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <a href="{{ route('front.developro.show') }}?rooms=3">
                            <div class="apartament-type apartament-type-3">
                                <span class="area"><b>55 - 64 m<sup>2</sup></b></span>

                                <div class="apartament-type-footer">
                                    <h2><span>Apartament</span> 3-pokojowy</h2>
                                    <span class="bttn bttn-border-blue">SPRAWDŹ <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></span>
                                </div>
                                <span class="number">3</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <a href="{{ route('front.developro.show') }}?rooms=4">
                            <div class="apartament-type apartament-type-4">
                                <span class="area"><b>78 m<sup>2</sup></b></span>

                                <div class="apartament-type-footer">
                                    <h2><span>Apartament</span> 4-pokojowy</h2>
                                    <span class="bttn bttn-border-blue">SPRAWDŹ <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></span>
                                </div>
                                <span class="number">4</span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <a href="{{ route('front.developro.show') }}" class="bttn mt-5">ZOBACZ WSZYSTKIE <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></a>
                    </div>
                </div>
            </div>
        </section>

        @if($images->count() > 0)
            <section class="pb-0">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="section-title mb-0">
                                <div class="sub-section-title">
                                    <span>GALERIA</span>
                                </div>
                                <h2>Zobacz, jak będzie wyglądać Twój dom</h2>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container">
                    <div class="row mt-5">
                        <div class="col-12">
                            @include('front.parse.gallery', ['list' => $images])
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <section class="pb-0 pt-at-lg">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="section-title justify-content-start section-title-xl">
                            <div class="sub-section-title">
                                <span>EPS DEVELOPMENT</span>
                            </div>
                            <h2 class="text-start">Zaufany deweloper <br>- gwarancja solidnej inwestycji</h2>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 d-flex align-items-start justify-content-center justify-content-md-end mb-4 mb-lg-0">
                        <img src="{{ asset('../images/eps-dev-logo.jpg') }}" alt="Logo EPS Development" width="245" height="135">
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-0 mt-xl-4 pt-at-lg">
            <div class="container container-left-after">
                <div class="row flex-row-reverse">
                    <div class="col-12 col-xl-7 d-flex justify-content-end">
                        <div class="golden-lines">
                            <div class="golden-line-4">
                                <img src="{{ asset('images/zote-linie-4-gold.png') }}" alt="">
                            </div>
                            <div class="golden-line-3">
                                <img src="{{ asset('images/zote-linie-3-white.png') }}" alt="">
                            </div>
                            <img src="{{ asset('images/rodzina-w-nowym-mieszkaniu.jpg') }}" alt="" width="740" height="530">
                        </div>
                    </div>
                    <div class="col-12 col-xl-5 d-flex align-items-center">
                        <div class="section-text mt-5 mt-xl-0">
                            <p>Spółka powstała w 2021 roku z wizją realizacji szeregu budynków mieszkalnych na terenie całej Polski. Udziałowcami spółki EPS Development są osoby mające bogate i ponad 30 letnie doświadczenie w branży budowlanej, w tym Członkowie Zarządu spółki EL-INWEST ze Skierniewic działającej w całej Polsce od ponad 30 lat.</p>
                            <p>&nbsp;</p>
                            <p>Pomysł stworzenia spółki EPS Development narodził się w momencie, kiedy okazało się, że na skierniewickim rynku mieszkań brakuje małych, kameralnych inwestycji, a lokalni deweloperzy nastawieni są na realizacje wysokokubaturowych apartamentowców przeznaczonych do zamieszania przez znaczną liczbę mieszkańców.</p>
                            <a href="{{ route('front.investor') }}" class="bttn mt-5">DOWIEDZ SIĘ WIĘCEJ <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-section">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-xl-4">
                        <div class="pe-0 pe-xl-5 me-0 me-xl-5">
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
                    <div class="col-12 col-xl-8">
                        @include('components.contact-form', ['page' => '', 'back' => ''])
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownItems = document.querySelectorAll('.dropdown-menu .dropdown-item');

            dropdownItems.forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();

                    const li = this.closest('li');
                    const dropdown = this.closest('.dropdown');
                    const toggleButton = dropdown.querySelector('.dropdown-toggle');
                    const hiddenInput = dropdown.querySelector('input[type="hidden"]');

                    const selectedValue = li.getAttribute('data-value');
                    const selectedText = this.innerHTML.trim();

                    if (toggleButton) {
                        toggleButton.innerHTML = selectedText;
                    }

                    if (hiddenInput) {
                        hiddenInput.value = selectedValue;
                    }
                });
            });
        });
    </script>
@endpush

