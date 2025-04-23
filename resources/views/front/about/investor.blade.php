@extends('layouts.page')

@section('meta_title', 'Kontakt')
@isset($page->meta_title) @section('seo_title', $page->meta_title) @endisset
@isset($page->meta_description) @section('seo_description', $page->meta_description) @endisset
@section('content')
    <div class="breadcrumbs">
        <div class="container">
            <div class="d-flex flex-wrap gap-2 row-gap-2">
                <a href="/" class="breadcrumbs-link">Strona główna</a>
                <span class="breadcrumbs-separator">|</span>
                <span class="breadcrumbs-current">Inwestor</span>
            </div>
        </div>
    </div>

    <main>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-6">
                        <div class="section-title justify-content-start">
                            <div class="sub-section-title">
                                <span>EPS DEVELOPMENT</span>
                            </div>
                            <h2 class="text-start">Zaufany deweloper <br>- gwarancja solidnej inwestycji</h2>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mt-4">
                            <p>Spółka powstała w 2021 roku z wizją realizacji szeregu budynków mieszkalnych na terenie całej Polski. Udziałowcami spółki EPS Development są osoby mające bogate i ponad 30 letnie doświadczenie w branży.</p>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-4">
                        <div class="icon-title-text-box text-center">
                            <img src="https://placehold.co/75" alt="">
                            <h3>Kompleksowość</h3>
                            <p>Mauris malesuada, dolor a cursus pretium, est orci ultrices ante, ac tempor arcu tellus sollicitudin risus. Maecenas egestas congue accumsan. Nullam ut arcu pellentesque, viverra turpis at.</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="icon-title-text-box text-center">
                            <img src="https://placehold.co/75" alt="">
                            <h3>Partnerstwo</h3>
                            <p>Mauris malesuada, dolor a cursus pretium, est orci ultrices ante, ac tempor arcu tellus sollicitudin risus. Maecenas egestas congue accumsan. Nullam ut arcu pellentesque, viverra turpis at.</p>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="icon-title-text-box text-center">
                            <img src="https://placehold.co/75" alt="">
                            <h3>Jakość</h3>
                            <p>Mauris malesuada, dolor a cursus pretium, est orci ultrices ante, ac tempor arcu tellus sollicitudin risus. Maecenas egestas congue accumsan. Nullam ut arcu pellentesque, viverra turpis at.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
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
                                    <span>DOŚWIADCZENIE</span>
                                </div>
                                <h2 class="text-start">Tworzymy miejsca do życia, nie tylko do mieszkania</h2>
                            </div>
                            <p><b>Nasza firma ma wieloletnie doświadczenie w tworzeniu wyjątkowych przestrzeni, które spełniają najwyższe standardy jakości i komfortu</b></p>
                            <p>&nbsp;</p>
                            <p>Cechuje nas indywidualne podejście do każdego projektu. Stale poszukujemy nowych, innowacyjnych rozwiązań, aby nasze budynki były nie tylko funkcjonalne, ale również estetycznie doskonałe. Nasze doświadczenie to gwarancja Twojej satysfakcji i pewność, że Twój nowy apartament będzie miejscem pełnym komfortu i elegancji.</p>
                            <a href="" class="bttn mt-5">DOSTĘPNE MIESZKANIA <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></a>
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

