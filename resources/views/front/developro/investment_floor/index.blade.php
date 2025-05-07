@extends('layouts.page', ['body_class' => 'investments'])

{{--@section('meta_title', $investment->floor->name)--}}
{{--@section('seo_title', $page->meta_title.' - '.$investment->floor->name)--}}
{{--@section('seo_description', $page->meta_description)--}}

@section('content')
<section>
    <div class="container">
        <div id="planNav" class="row mb-5">
            <div class="col-12 col-sm-4 d-flex justify-content-start">@if($prev_floor) <a href="{{route('front.developro.floor', [$prev_floor, Str::slug($prev_floor->name)])}}" class="bttn bttn-sm">{{$prev_floor->name}}</a> @endif</div>
            <div class="col-12 col-sm-4 d-flex justify-content-center">
                <a href="{{route('front.developro.show')}}" class="bttn bttn-sm">Plan budynku</a>
            </div>
            <div class="col-12 col-sm-4 d-flex justify-content-end">@if($next_floor) <a href="{{route('front.developro.floor', [$next_floor, Str::slug($next_floor->name)]}}" class="bttn bttn-sm">{{$next_floor->name}}</a> @endif</div>
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
                    <div class="row">
                        <div class="col dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Powierzchnia</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="col dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Liczba pokoi</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="col dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Status</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="col">
                            <button href="" class="bttn w-100">SZUKAJ <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('front.developro.investment_shared.list', ['investment' => $investment])
</section>
@endsection
@push('scripts')
    <script src="{{ asset('/js/plan/imagemapster.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/plan/tip.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/plan/floor.js') }}" charset="utf-8"></script>
@endpush
