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
                <span class="breadcrumbs-current">Finansowanie</span>
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
                            <h2 class="mb-4">Kredyt hipoteczny z mFinanse</h2>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 col-xl-8">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="atut">
                                    <img src="{{ asset('images/konsultacje-icon.png') }}" alt="Konsultacje ikonka">
                                    <p class="mt-4">Konsultacje i wsparcie eksperta <br>w całym procesie kredytowym</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mt-4 mt-md-0">
                                <div class="atut">
                                    <img src="{{ asset('images/wybor-oferty.png') }}" alt="Wybór oferty ikonka">
                                    <p class="mt-4">Wybór najkorzystniejszej oferty <br>spośród wielu banków</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mt-4">
                                <div class="atut">
                                    <img src="{{ asset('images/zdolnosc-kredytowa.png') }}" alt="Zdolnosc kredytowa ikonka">
                                    <p class="mt-4">Zbadanie zdolności kredytowej <br>i pomoc w analizie Twoich finansów</p>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 mt-4">
                                <div class="atut">
                                    <img src="{{ asset('images/kontakt-onliune.png') }}" alt="Kontakt online ikonka">
                                    <p class="mt-4">Mobilność i elastyczność ekspertów <br>w całej Polsce, dogodne formy komunikacji</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="pt-0">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title mb-4">
                            <div class="sub-section-title">
                                <span><i>m</i>FINANSE</span>
                            </div>
                            <h2 class="mb-4">Umów się na konsultację <br>i uzyskaj wsparcie eksperta kredytowego</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 d-flex justify-content-center align-items-center">
                        <a href="https://mfinanse.pl/oferta-indywidualna/kredyt-hipoteczny/" target="_blank" rel="nofollow"><img src="{{ asset('../images/mBank_logo_finanse.jpg') }}" alt="Logotyp mBank finanse"></a>
                    </div>
                    <div class="col-12 text-center mt-5">
                        <h3>Ekspert: Tomasz Zientała</h3>
                        <p>telefon: <a href="tel:+48533600233">533600233</a></p>
                        <p>e-mail: <a href="mailto:tomasz.zientala@mfinanse.pl">tomasz.zientala@mfinanse.pl</a></p>
                    </div>
                    <div class="col-12 text-center">
                        <a href="https://mfinanse.pl/formularz-biznespartner/?fn=eps&f=FBP-0685&e=108882" class="bttn mt-5" target="_blank">FORMULARZ KONTAKTOWY <img src="{{ asset('../images/bttn_arrow.svg') }}" alt=""></a>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection

