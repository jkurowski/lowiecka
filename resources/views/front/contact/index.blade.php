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
                <span class="breadcrumbs-current">Kontakt</span>
            </div>
        </div>
    </div>

    <main>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-4">
                        <div class="pe-5 me-5">
                            <div class="section-title justify-content-start">
                                <div class="sub-section-title">
                                    <span>KONTAKT</span>
                                </div>
                                <h2 class="text-start mb-4">Masz pytania? <br>Zadzwoń lub napisz do nas!</h2>
                                <p>Nasz zespół jest do Twojej dyspozycji, aby udzielić wszelkich informacji i odpowiedzieć na Twoje pytania. </p>
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
        <div id="map" class="map-contact"></div>
    </main>
@endsection
@push('scripts')
    <link href="{{ asset('/css/leaflet.min.css') }}" rel="stylesheet" media="print" onload="this.media='all'">
    <script src="{{ asset('/js/leaflet.js') }}" charset="utf-8"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            var map = L.map('map').setView([51.954484502336655, 20.133848175057228], 16);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            L.marker([51.954484502336655, 20.133848175057228]).addTo(map);
        });
    </script>
@endpush

