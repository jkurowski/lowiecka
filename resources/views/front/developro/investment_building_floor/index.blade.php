@extends('layouts.page', ['body_class' => 'investment'])

@section('meta_title', $investment->name)
{{-- @section('seo_title', $page->meta_title) --}}
{{-- @section('seo_description', $page->meta_description) --}}

@section('content')
    <div class="breadcrumbs pb-4">
        <div class="container">
            <div class="d-flex flex-wrap gap-1 row-gap-2">
                <a href="/" class="breadcrumbs-link">Strona główna</a>
                <span class="breadcrumbs-separator">/</span>
                <a href="{{ route('front.developro.show', $investment->slug) }}" class="breadcrumbs-link">{{ $investment->name }}</a>
                <span class="breadcrumbs-separator">/</span>
                <span class="breadcrumbs-separator">{{$building->name}}</span>
                <span class="breadcrumbs-separator">/</span>
                <span class="breadcrumbs-current">{{$floor->name}}</span>
            </div>
        </div>
    </div>
    <main>
        <section class="py-3 bg-white sticky-top investment-navbar-wrapper">
            <nav id="investment-navbar" class="navbar">
                <div class="container justify-content-center">
                    <div class="navbar-nav flex-wrap flex-row gap-3 justify-content-center">
                        <a class="nav-link nav-link-black" href="{{ route('front.developro.show', $investment->slug) }}#apartamenty">Apartamenty</a>
                        <a class="nav-link nav-link-black" href="{{ route('front.developro.show', $investment->slug) }}#mieszkania">Mieszkania</a>
                        <a class="nav-link nav-link-black" href="{{ route('front.developro.show', $investment->slug) }}#atuty">Atuty</a>
                        <a class="nav-link nav-link-black d-none" href="{{ route('front.developro.show', $investment->slug) }}#wizualizacje">Wizualizacje</a>
                        <a class="nav-link nav-link-black" href="{{ route('front.developro.investment.news', $investment->slug) }}">Dziennik budowy</a>
                        <a class="nav-link nav-link-black" href="{{ route('front.developro.show', $investment->slug) }}#lokalizacja">Lokalizacja</a>
                        <a class="nav-link nav-link-black" href="{{ route('front.developro.show', $investment->slug) }}#kontakt">Kontakt</a>
                    </div>
                </div>
            </nav>
        </section>
        <section class="pt-0">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="h3 mb-50px text-center">
                            <span class="fw-thin d-block fs-4" data-aos="fade-up">{{$building->name}}</span>
                            <span class="d-block text-uppercase" data-aos="fade-up">{{$floor->name}}</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row pb-4">
                    <div class="col-4 text-center">
                        @if($prev_floor)
                            <a href="{{ route('front.developro.building-floor', ['slug' => $investment->slug, 'building_slug' => Str::slug($prev_floor->building->name), 'floor' => $prev_floor, 'floor_slug' => Str::slug($prev_floor->name)]) }}" class="custom-btn custom-btn-primary">{{$prev_floor->name}}</a>
                        @endif
                    </div>
                    <div class="col-4 text-center">
                        <a href="{{ route('front.developro.show', $investment->slug) }}#mieszkania" class="custom-btn custom-btn-primary">Wróć do planu</a>
                    </div>
                    <div class="col-4 text-center">
                        @if($next_floor)
                            <a href="{{ route('front.developro.building-floor', ['slug' => $investment->slug, 'building_slug' => Str::slug($next_floor->building->name), 'floor' => $next_floor, 'floor_slug' => Str::slug($next_floor->name)]) }}" class="custom-btn custom-btn-primary">{{$next_floor->name}}</a>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @if($floor->file)
                            <div id="plan">
                                <div id="plan-holder">
                                    <img src="{{ asset('/investment/floor/'.$floor->file) }}" alt="{{$floor->name}}" id="invesmentplan" usemap="#invesmentplan" class="w-100 h-100 object-fit-cover">
                                </div>
                                <map name="invesmentplan">
                                    @if($filtered_properties)
                                        @foreach($filtered_properties as $r)
                                            @php
                                                $url = route('front.developro.building-property', [
                                                    'slug' => $investment->slug,
                                                    'building_slug' => Str::slug($r->building->name) ?? 'missing-building',
                                                    'floor_slug' => Str::slug($r->floor->name) ?? 'missing-floor',
                                                    'property' => $r->id,
                                                    'property_slug' => Str::slug($r->name) ?? 'missing-property',
                                                ]);
                                            @endphp
                                            <area
                                                shape="poly"
                                                href="{{ $url }}"
                                                title="{{$r->name}}<br>Powierzchnia: <b class=fr>{{$r->area}} m<sup>2</sup></b><br />Pokoje: <b class=fr>{{$r->rooms}}</b><br><b>{{ roomStatus($r->status) }}</b>"
                                                alt="{{$r->slug}}"
                                                data-roomtype="{{$r->typ}}"
                                                data-roomstatus="{{$r->status}}"
                                                class="inline status-{{$r->status}}"
                                                coords="@if($r->html) {{cords($r->html)}} @endif
                                                ">
                                        @endforeach
                                    @endif
                                </map>
                            </div>
                        @endif
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
                                </svg>
                            </button>
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
    </main>
@endsection
@push('scripts')
    <script src="{{ asset('/js/plan/imagemapster.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/plan/floor.js') }}" charset="utf-8"></script>
@endpush

