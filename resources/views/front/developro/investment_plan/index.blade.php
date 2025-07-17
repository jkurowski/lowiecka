@extends('layouts.page', ['body_class' => 'investments'])

@section('meta_title', 'Inwestycje - '.$investment->name)
@if($page)
    @section('seo_title', $page->meta_title)
    @section('seo_description', $page->meta_description)
    @section('pageheader')
        @include('layouts.partials.page-header', ['page' => $page, 'header_file' => 'rooms.jpg', 'heading' => $investment->name])
    @stop
@endif

@section('content')
<main>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title justify-content-center section-title-xl">
                        <div class="sub-section-title">
                            <span>Łowicka 100</span>
                        </div>
                        <h2 class="text-center">Z planu budynku wybierz piętro lub użyj <a href="#main-search" class="scroll-link" data-offset="90"><span class="text-gold">wyszukiwarki</span></a></h2>
                    </div>
                    @if($investment->plan)
                        <div id="plan-holder">
                            <img src="{{ asset('/investment/plan/'.$investment->plan->file) }}" alt="{{$investment->name}}" id="invesmentplan" usemap="#invesmentplan">
                            @if($investment->type == 2)
                                <map name="invesmentplan">
                                    @foreach($investment->floors as $floor)
                                        @if($floor->html)
                                            <area
                                                shape="poly"
                                                href="{{ route('front.developro.floor', [$floor, Str::slug($floor->name)]) }}"
                                                title="{{$floor->name}}"
                                                alt="floor-{{$floor->id}}"
                                                data-item="{{$floor->id}}"
                                                data-floornumber="{{$floor->id}}"
                                                data-floortype="{{$floor->type}}"
                                                coords="{{cords($floor->html)}}">
                                        @endif
                                    @endforeach
                                </map>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div id="main-search" class="mt-0">
                        <form class="row" method="get" action="">
                            <div class="col-6 col-lg dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" data-default="Powierzchnia">Powierzchnia</a>
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
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" data-default="Piętro">Piętro</a>
                                <ul class="dropdown-menu">
                                    <li data-value=""><a class="dropdown-item" href="#">Wszystkie</a></li>
                                    <li data-value="3"><a class="dropdown-item" href="#">Parter</a></li>
                                    <li data-value="4"><a class="dropdown-item" href="#">Piętro 1</a></li>
                                    <li data-value="5"><a class="dropdown-item" href="#">Piętro 2</a></li>
                                    <li data-value="6"><a class="dropdown-item" href="#">Piętro 3</a></li>
                                    <li data-value="7"><a class="dropdown-item" href="#">Piętro 4</a></li>
                                    <li data-value="8"><a class="dropdown-item" href="#">Piętro 5</a></li>
                                </ul>
                                <input type="hidden" name="floor" value="">
                            </div>
                            <div class="col-6 col-lg dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" data-default="Pokoje">Pokoje</a>
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
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" data-default="Status">Status</a>
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

        @include('front.developro.investment_shared.list', ['investment' => $investment])
    </section>
</main>
@endsection
@push('scripts')
    <script src="{{ asset('/js/plan/imagemapster.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/plan/plan.js') }}" charset="utf-8"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownItems = document.querySelectorAll('.dropdown-menu .dropdown-item');

            // Update dropdowns on click
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
                        if (selectedValue === "") {
                            toggleButton.textContent = toggleButton.dataset.default || 'Wszystkie';
                        } else {
                            toggleButton.innerHTML = selectedText;
                        }
                    }

                    if (hiddenInput) {
                        hiddenInput.value = selectedValue;
                    }
                });
            });

            // Restore state from URL on load
            const params = new URLSearchParams(window.location.search);
            const allDropdowns = document.querySelectorAll('.dropdown');

            allDropdowns.forEach(dropdown => {
                const hiddenInput = dropdown.querySelector('input[type="hidden"]');
                const toggleButton = dropdown.querySelector('.dropdown-toggle');
                const name = hiddenInput?.name;

                if (!name) return;

                const valueFromUrl = params.get(name);

                if (valueFromUrl !== null) {
                    hiddenInput.value = valueFromUrl;

                    const matchedItem = dropdown.querySelector(`li[data-value="${valueFromUrl}"] .dropdown-item`);
                    if (matchedItem && toggleButton) {
                        if (valueFromUrl === "") {
                            toggleButton.textContent = toggleButton.dataset.default || 'Wszystkie';
                        } else {
                            toggleButton.innerHTML = matchedItem.innerHTML.trim();
                        }
                    }

                    // Fallback if no item matched
                    if (!matchedItem && toggleButton) {
                        toggleButton.textContent = toggleButton.dataset.default || 'Wszystkie';
                    }
                }
            });
        });
    </script>
@endpush
