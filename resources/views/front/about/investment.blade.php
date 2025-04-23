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
        <section class="mt-0">
            <div class="container container-left-after">
                <div class="row flex-row-reverse">
                    <div class="col-7 d-flex justify-content-end">
                        <div class="golden-lines">
                            <div class="golden-line-4">
                                <img src="{{ asset('images/zote-linie-4-gold.png') }}" alt="">
                            </div>
                            <div class="golden-line-3">
                                <img src="{{ asset('images/zote-linie-3-white.png') }}" alt="">
                            </div>
                            <img src="https://placehold.co/740x530" alt="">
                        </div>
                    </div>
                    <div class="col-5 d-flex align-items-center">
                        <div class="section-text">
                            <div class="section-title justify-content-start mb-4">
                                <div class="sub-section-title">
                                    <span>O INWESTYCJI</span>
                                </div>
                                <h2 class="text-start">Kameralna inwestycja</h2>
                            </div>
                            <p>Łowicka 100 to kameralna inwestycja położona na osiedlu Widok w Skierniewicach. Doskonała lokalizacja zapewnia idealne skomunikowanie z resztą miasta, oferuje bogatą infrastrukturę oraz mnóstwo zieleni w okolicy.Inwestycja obejmuje 66 mieszkań o przemyślanych układach, które spełnią potrzeby zarówno rodzin, jak i singli.</p>
                            <p>&nbsp;</p>
                            <p>Każde mieszkanie zostało rzetelnie przeanalizowane pod kątem funkcjonalnej aranżacji i komfortu codziennego życia. Lokale na parterze zostały wyposażone w prywatne ogródki, idealne na odpoczynek lub wspólne chwile z bliskimi, a mieszkania na wszystkich wyższych piętrach posiadają duże, przestronne balkony.
                            </p>
                            <a href="" class="bttn mt-5">DOSTĘPNE MIESZKANIA <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></a>
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
                    <div class="col-2">
                        <div class="blue-number-box">
                            <strong>66</strong>
                            <p>apartamentów</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="blue-number-box">
                            <strong>5</strong>
                            <p>kondygnacji</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="blue-number-box">
                            <strong>2</strong>
                            <p>windy</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="blue-number-box">
                            <strong>10</strong>
                            <p>mieszkań <br>z ogródkami</p>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="blue-number-box">
                            <strong>13</strong>
                            <p>miejsc <br>postojowych</p>
                        </div>
                    </div>
                    <div class="col-2">
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
                    <div class="col-6">
                        <div class="section-title justify-content-start">
                            <div class="sub-section-title">
                                <span>TYPY MIESZKAŃ</span>
                            </div>
                            <h2 class="text-start">Przykładowe mieszkania</h2>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="nav nav-rooms mt-4" id="roomsTab" role="group">
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
                                    <div class="col-7">
                                        <div class="rooms-tab-img pe-5">
                                            <img src="https://placehold.co/700x650" alt="">
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="rooms-tab-detail">
                                            <h3>64,21 <small>m<sup>2</sup></small></h3>
                                            <p class="mt-4 mb-3">Zobaczysz u nas mieszkania 3-pokojowe, które są przestronne i gotowe na wszystkie Twoje pomysły. Wszystkie dostępne lokale są widoczne na liście!</p>
                                            <ul class="mb-0 list-unstyled">
                                                <li>Pokoje<span>3</span></li>
                                                <li>Sypialnie<span>2</span></li>
                                                <li>Pokój + Aneks<span>26,59 m<sup>2</sup></span></li>
                                                <li>Dostępny balkon lub ogródek</li>
                                            </ul>
                                            <a href="" class="bttn mt-5">ZOBACZ MIESZKANIA <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-2-pane" role="tabpanel" aria-labelledby="tab-2" tabindex="0">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="rooms-tab-img pe-5">
                                            <img src="https://placehold.co/700x650" alt="">
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="rooms-tab-detail">
                                            <h3>64,21 <small>m<sup>2</sup></small></h3>
                                            <p class="mt-4 mb-3">Zobaczysz u nas mieszkania 3-pokojowe, które są przestronne i gotowe na wszystkie Twoje pomysły. Wszystkie dostępne lokale są widoczne na liście!</p>
                                            <ul class="mb-0 list-unstyled">
                                                <li>Pokoje<span>3</span></li>
                                                <li>Sypialnie<span>2</span></li>
                                                <li>Pokój + Aneks<span>26,59 m<sup>2</sup></span></li>
                                                <li>Dostępny balkon lub ogródek</li>
                                            </ul>
                                            <a href="" class="bttn mt-5">ZOBACZ MIESZKANIA <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-3-pane" role="tabpanel" aria-labelledby="tab-3" tabindex="0">
                                <div class="row">
                                    <div class="col-7">
                                        <div class="rooms-tab-img pe-5">
                                            <img src="https://placehold.co/700x650" alt="">
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="rooms-tab-detail">
                                            <h3>64,21 <small>m<sup>2</sup></small></h3>
                                            <p class="mt-4 mb-3">Zobaczysz u nas mieszkania 3-pokojowe, które są przestronne i gotowe na wszystkie Twoje pomysły. Wszystkie dostępne lokale są widoczne na liście!</p>
                                            <ul class="mb-0 list-unstyled">
                                                <li>Pokoje<span>3</span></li>
                                                <li>Sypialnie<span>2</span></li>
                                                <li>Pokój + Aneks<span>26,59 m<sup>2</sup></span></li>
                                                <li>Dostępny balkon lub ogródek</li>
                                            </ul>
                                            <a href="" class="bttn mt-5">ZOBACZ MIESZKANIA <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></a>
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
                    <div class="col-4 d-flex align-items-center">
                        <div class="section-title justify-content-start mb-0">
                            <div class="sub-section-title">
                                <span>ATUTY</span>
                            </div>
                            <h2 class="text-start">Dlaczego warto zamieszkać na Łowicka <span class="text-gold">100</span>?</h2>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="atut">
                            <img src="https://placehold.co/65" alt="">
                            <h3>Doskonała lokalizacja</h3>
                            <p>Blisko centrum, sklepów, szkół i komunikacji miejskiej. Sąsiedztwo terenów zielonych i parku sprzyja relaksowi i aktywnemu wypoczynkowi.</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="atut">
                            <img src="https://placehold.co/65" alt="">
                            <h3>Komfortowe mieszkania</h3>
                            <p>Przemyślane układy, prywatne ogródki na parterze i przestronne balkony na wyższych piętrach. Idealna przestrzeń do życia i relaksu.</p>
                        </div>
                    </div>
                    <div class="col-4 mt-4">
                        <div class="atut">
                            <img src="https://placehold.co/65" alt="">
                            <h3>Ekologiczne rozwiązania</h3>
                            <p>Fotowoltaika, filtry antysmogowe i smart home zmniejszają koszty energii i zapewniają zdrowe, ekologiczne warunki życia.</p>
                        </div>
                    </div>
                    <div class="col-4 mt-4">
                        <div class="atut">
                            <img src="https://placehold.co/65" alt="">
                            <h3>Nowoczesna infrastruktura</h3>
                            <p>Podziemna hala garażowa, ładowarki do aut elektrycznych, rowerownia i windy zapewniają wygodę i funkcjonalność na co dzień.</p>
                        </div>
                    </div>
                    <div class="col-4 mt-4">
                        <div class="atut">
                            <img src="https://placehold.co/65" alt="">
                            <h3>Bezpieczeństwo i wspólnota</h3>
                            <p>Zamknięte osiedle, monitoring, wideodomofony i przestrzenie wspólne sprzyjają spokojowi oraz budowaniu sąsiedzkich relacji.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title">
                            <div class="sub-section-title">
                                <span>GALERIA</span>
                            </div>
                            <h2>Zobacz, jak będzie wyglądać Twój dom</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid overflow-hidden" id="slick-fluid">
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

        <section class="mt-section">
            <div class="container">
                <div class="row">
                    <div class="col-4">
                        <div class="pe-5 me-5">
                            <div class="section-title justify-content-start">
                                <div class="sub-section-title">
                                    <span>KONTAKT</span>
                                </div>
                                <h2 class="text-start mb-4">Zapytaj o ofertę!</h2>
                                <p>Nasz zespół jest do Twojej dyspozycji, aby udzielić wszelkich informacji i odpowiedzieć na Twoje pytania. </p>
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

