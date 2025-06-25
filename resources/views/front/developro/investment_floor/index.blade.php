@extends('layouts.page', ['body_class' => 'investments'])

{{--@section('meta_title', $investment->floor->name)--}}
{{--@section('seo_title', $page->meta_title.' - '.$investment->floor->name)--}}
{{--@section('seo_description', $page->meta_description)--}}

@section('content')
<main>
    <section>
        <div class="container">
            <div id="planNav" class="row mb-5">
                <div class="col-12 col-sm-4 d-flex justify-content-start">@if($prev_floor) <a href="{{route('front.developro.floor', [$prev_floor, Str::slug($prev_floor->name)])}}" class="bttn bttn-sm">{{$prev_floor->name}}</a> @endif</div>
                <div class="col-12 col-sm-4 d-flex justify-content-center">
                    <a href="{{route('front.developro.show')}}" class="bttn bttn-sm">Plan budynku</a>
                </div>
                <div class="col-12 col-sm-4 d-flex justify-content-end">@if($next_floor) <a href="{{route('front.developro.floor', [$next_floor, Str::slug($next_floor->name)])}}" class="bttn bttn-sm">{{$next_floor->name}}</a> @endif</div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="section-title justify-content-center mb-5">
                        <div class="sub-section-title">
                            <span>Łowicka 100</span>
                        </div>
                        <h2 class="text-center">{{$investment->floor->name}}</h2>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @if($investment->floor->file)
                        <div id="plan-holder">
                            <img src="{{ asset('/investment/floor/webp/'.$investment->floor->file_webp) }}" alt="{{$investment->floor->name}}" id="invesmentplan" usemap="#invesmentplan">
                            <map name="invesmentplan">
                                @if($properties)
                                    @foreach($properties as $r)
                                        @if($r->html)
                                            <area
                                                shape="poly"
                                                href="{{route('front.developro.property', [$investment->floor, Str::slug($investment->floor->name), $r, Str::slug($r->name) ])}}"
                                                data-item="{{$r->id}}"
                                                title="{{$r->name}}<br>Powierzchnia: <b class=fr>{{$r->area}} m<sup>2</sup></b><br />Pokoje: <b class=fr>{{$r->rooms}}</b><br><b>{{ roomStatus($r->status) }}</b>"
                                                alt="{{$r->slug}}"
                                                data-roomnumber="{{$r->number}}"
                                                data-roomtype="{{$r->typ}}"
                                                data-roomstatus="{{$r->status}}"
                                                coords="{{cords($r->html)}}"
                                                class="inline status-{{$r->status}}"
                                            >
                                        @endif
                                    @endforeach
                                @endif
                            </map>
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
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Powierzchnia</a>
                                <ul class="dropdown-menu">
                                    <li data-value=""><a class="dropdown-item" href="#">Wszystkie</a></li>
                                    <li data-value="29-40"><a class="dropdown-item" href="#">29 - 40 m<sup>2</sup></a></li>
                                    <li data-value="41-60"><a class="dropdown-item" href="#">41 - 60 m<sup>2</sup></a></li>
                                    <li data-value="61-80"><a class="dropdown-item" href="#">61 - 80 m<sup>2</sup></a></li>
                                    <li data-value="81-110"><a class="dropdown-item" href="#">81 - 110 m<sup>2</sup></a></li>
                                </ul>
                                <input type="hidden" name="area" value="">

                                {!! area2Select($area_range) !!}
                            </div>
                            <div class="col-6 col-lg dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Pokoje</a>
                                <ul class="dropdown-menu">
                                    <li data-value=""><a class="dropdown-item" href="#">Wszystkie</a></li>
                                    @foreach($uniqueRooms as $room)
                                        <li data-value="{{ $room }}"><a class="dropdown-item" href="#">{{ $room }}</a></li>
                                    @endforeach
                                </ul>
                                <input type="hidden" name="rooms" value="">
                            </div>
                            <div class="col-12 col-lg dropdown">
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

        @include('front.developro.investment_shared.list', ['investment' => $investment])
    </section>
</main>
@endsection
@push('scripts')
    <script src="{{ asset('/js/plan/imagemapster.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/plan/tip.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/plan/floor.js') }}" charset="utf-8"></script>
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
