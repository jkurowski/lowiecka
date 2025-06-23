@extends('layouts.page')

@section('meta_title', 'Kontakt')
@isset($page->meta_title) @section('seo_title', $page->meta_title) @endisset
@isset($page->meta_description) @section('seo_description', $page->meta_description) @endisset
@section('content')
    <div class="breadcrumbs">
        <div class="container container-left-after">
            <div class="d-flex flex-wrap gap-2 row-gap-2">
                <a href="/" class="breadcrumbs-link">Strona główna</a>
                <span class="breadcrumbs-separator">|</span>
                <span class="breadcrumbs-current">O inwestycji</span>
            </div>
        </div>
    </div>

    <main>
        <section class="mt-0 pt-first-lg">
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
                            <img src="{{ asset('../images/inwestycja-1.jpg') }}" alt="" width="740" height="530">
                        </div>
                    </div>
                    <div class="col-12 col-xl-5 d-flex align-items-center mt-4 mt-md-5 mt-xl-0">
                        <div class="section-text">
                            <div class="section-title justify-content-start mb-4">
                                <div class="sub-section-title">
                                    <span>O INWESTYCJI</span>
                                </div>
                                <h2 class="text-start">Kameralna inwestycja</h2>
                            </div>
                            <p>Łowicka 100 to kameralna inwestycja położona na osiedlu Widok w Skierniewicach. Doskonała lokalizacja zapewnia idealne skomunikowanie z resztą miasta, oferuje bogatą infrastrukturę oraz mnóstwo zieleni w okolicy. Inwestycja obejmuje 66 mieszkań o przemyślanych układach, które spełnią potrzeby zarówno rodzin, jak i singli.</p>
                            <p>&nbsp;</p>
                            <p>Każde mieszkanie zostało rzetelnie przeanalizowane pod kątem funkcjonalnej aranżacji i komfortu codziennego życia. Lokale na parterze zostały wyposażone w prywatne ogródki, idealne na odpoczynek lub wspólne chwile z bliskimi, a mieszkania na wszystkich wyższych piętrach posiadają duże, przestronne balkony.
                            </p>
                            <a href="{{ route('front.developro.show') }}" class="bttn mt-3 mt-md-5">DOSTĘPNE MIESZKANIA <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="blue-mozaik">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title">
                            <div class="sub-section-title">
                                <span>O INWESTYCJI</span>
                            </div>
                            <h2>Łowicka 100 w liczbach</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-sm-4 col-xl-2 mb-4 mb-xl-0">
                        <div class="blue-number-box">
                            <strong>66</strong>
                            <p>apartamentów</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-xl-2 mb-4 mb-xl-0">
                        <div class="blue-number-box">
                            <strong>5</strong>
                            <p>kondygnacji</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-xl-2 mb-4 mb-xl-0">
                        <div class="blue-number-box">
                            <strong>2</strong>
                            <p>windy</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-xl-2 mb-4 mb-sm-0">
                        <div class="blue-number-box">
                            <strong>10</strong>
                            <p>mieszkań <br>z ogródkami</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-xl-2">
                        <div class="blue-number-box">
                            <strong>13</strong>
                            <p>miejsc <br>postojowych</p>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-xl-2">
                        <div class="blue-number-box">
                            <strong>54</strong>
                            <p>stanowiska <br>w hali garażowej</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pb-0">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-xl-6">
                        <div class="section-title justify-content-start section-title-xl">
                            <div class="sub-section-title">
                                <span>TYPY MIESZKAŃ</span>
                            </div>
                            <h2 class="text-start">Przykładowe mieszkania</h2>
                        </div>
                    </div>
                    <div class="col-12 col-xl-6">
                        <div class="nav nav-rooms mb-4 mb-xl-0 mt-0 mt-xl-4" id="roomsTab" role="group">
                            <button class="nav-link active btn btn-outline-primary" id="tab-1" data-bs-toggle="tab" data-bs-target="#tab-1-pane" type="button" role="tab" aria-controls="tab-1-pane" aria-selected="true">2-pokojowe</button>
                            <button class="nav-link btn btn-outline-primary" id="tab-2" data-bs-toggle="tab" data-bs-target="#tab-2-pane" type="button" role="tab" aria-controls="tab-2-pane" aria-selected="false">3-pokojowe</button>
                            <button class="nav-link btn btn-outline-primary" id="tab-3" data-bs-toggle="tab" data-bs-target="#tab-3-pane" type="button" role="tab" aria-controls="tab-3-pane" aria-selected="false">4-pokojowe</button>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="tab-content" id="roomsTabContent">
                            <div class="tab-pane fade show active" id="tab-1-pane" role="tabpanel" aria-labelledby="tab-1" tabindex="0">
                                <div class="row">
                                    <div class="col-12 col-lg-7">
                                        <div class="rooms-tab-img pe-5">
                                            <img src="{{ asset('images/przykladowe-2-pokoje.jpg') }}" alt="Przykładowy plan mieszkania 2-pokojowego">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-5">
                                        <div class="rooms-tab-detail">
                                            <h3>43.04 <small>m<sup>2</sup></small></h3>
                                            <p class="mt-4 mb-3">Zobaczysz u nas mieszkania 2-pokojowe, które są przestronne i gotowe na wszystkie Twoje pomysły. Wszystkie dostępne lokale są widoczne na liście!</p>
                                            <ul class="mb-0 list-unstyled">
                                                <li>Pokoje<span>2</span></li>
                                                <li>Sypialnie<span>1</span></li>
                                                <li>Pokój + Aneks<span>22,77 m<sup>2</sup></span></li>
                                                <li>Balkon<span>10.35 m<sup>2</sup></span></li>
                                            </ul>
                                            <a href="/mieszkania/pietro/2,pietro-1/m/20,mieszkanie-m27" class="bttn mt-3 mt-md-5">ZOBACZ MIESZKANIE <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-2-pane" role="tabpanel" aria-labelledby="tab-2" tabindex="0">
                                <div class="row">
                                    <div class="col-12 col-lg-7">
                                        <div class="rooms-tab-img pe-5">
                                            <img src="{{ asset('images/przykladowe-3-pokoje.jpg') }}" alt="Przykładowy plan mieszkania 3-pokojowego">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-5">
                                        <div class="rooms-tab-detail">
                                            <h3>59.26 <small>m<sup>2</sup></small></h3>
                                            <p class="mt-4 mb-3">Zobaczysz u nas mieszkania 3-pokojowe, które są przestronne i gotowe na wszystkie Twoje pomysły. Wszystkie dostępne lokale są widoczne na liście!</p>
                                            <ul class="mb-0 list-unstyled">
                                                <li>Pokoje<span>3</span></li>
                                                <li>Sypialnie<span>2</span></li>
                                                <li>Pokój + Aneks<span>24,78 m<sup>2</sup></span></li>
                                                <li>Ogródek<span>83.79 m<sup>2</sup></span></li>
                                            </ul>
                                            <a href="/mieszkania/pietro/1,parter/m/13,mieszkanie-m16" class="bttn mt-3 mt-md-5">ZOBACZ MIESZKANIA <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-3-pane" role="tabpanel" aria-labelledby="tab-3" tabindex="0">
                                <div class="row">
                                    <div class="col-12 col-lg-7">
                                        <div class="rooms-tab-img pe-5">
                                            <img src="{{ asset('images/przykladowe-4-pokoje.jpg') }}" alt="Przykładowy plan mieszkania 4-pokojowego">
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-5">
                                        <div class="rooms-tab-detail">
                                            <h3>78.01 <small>m<sup>2</sup></small></h3>
                                            <p class="mt-4 mb-3">Zobaczysz u nas mieszkania 4-pokojowe, które są przestronne i gotowe na wszystkie Twoje pomysły. Wszystkie dostępne lokale są widoczne na liście!</p>
                                            <ul class="mb-0 list-unstyled">
                                                <li>Pokoje<span>4</span></li>
                                                <li>Sypialnie<span>3</span></li>
                                                <li>Pokój + Aneks<span>32.81 m<sup>2</sup></span></li>
                                                <li>Balkon<span>11.16 m<sup>2</sup></span></li>
                                            </ul>
                                            <a href="/mieszkania/pietro/5,pietro-3/m/41,mieszkanie-m44" class="bttn mt-3 mt-md-5">ZOBACZ MIESZKANIA <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

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
                    <div class="col-12 col-mb-4 mt-4 mt-mb-0">
                        <div class="atut">
                            <img src="{{ asset('images/doskonala-lokalizacja.png') }}" alt="">
                            <h3>Doskonała lokalizacja</h3>
                            <p>Blisko centrum, sklepów, szkół i komunikacji miejskiej. Sąsiedztwo terenów zielonych i parku sprzyja relaksowi i aktywnemu wypoczynkowi.</p>
                        </div>
                    </div>
                    <div class="col-12 col-mb-4 mt-4 mt-mb-0">
                        <div class="atut">
                            <img src="{{ asset('images/komfortowe-mieszkania.png') }}" alt="">
                            <h3>Komfortowe mieszkania</h3>
                            <p>Przemyślane układy, prywatne ogródki na parterze i przestronne balkony na wyższych piętrach. Idealna przestrzeń do życia i odpoczynku.</p>
                        </div>
                    </div>
                    <div class="col-12 col-mb-4 mt-4 mt-mb-0">
                        <div class="atut">
                            <img src="{{ asset('images/ekologiczne-rozwiazania.png') }}" alt="">
                            <h3>Ekologiczne rozwiązania</h3>
                            <p>Fotowoltaika, filtry antysmogowe i smart home zmniejszają koszty energii i zapewniają zdrowe, ekologiczne warunki życia.</p>
                        </div>
                    </div>
                    <div class="col-12 col-mb-4 mt-4 mt-mb-0">
                        <div class="atut">
                            <img src="{{ asset('images/nowoczesna-infrastruktura.png') }}" alt="">
                            <h3>Nowoczesna infrastruktura</h3>
                            <p>Podziemna hala garażowa, ładowarki do aut elektrycznych, rowerownia i windy zapewniają wygodę i funkcjonalność na co dzień.</p>
                        </div>
                    </div>
                    <div class="col-12 col-mb-4 mt-4 mt-mb-0">
                        <div class="atut">
                            <img src="{{ asset('images/bezpieczenstwo-i-wspolnota.png') }}" alt="">
                            <h3>Bezpieczeństwo i wspólnota</h3>
                            <p>Zamknięte osiedle, monitoring, wideodomofony i przestrzenie wspólne sprzyjają spokojowi oraz budowaniu sąsiedzkich relacji.</p>
                        </div>
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

        <section class="mt-section @if($images->count() == 0) pt-0 @endif">
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

