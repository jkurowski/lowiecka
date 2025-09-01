@extends('layouts.page', ['body_class' => 'property'])

@section('meta_title', $property->investment->name .' - '.$property->floor->name.' - '.$property->name)
@section('seo_title', '')
@section('seo_description', '')

@section('content')
    <main>
        <section>
            <div class="container mb-4 mb-md-5">
                <div id="planNav" class="row">
                    <div class="col-12 col-md-4 d-flex justify-content-start">
                        @if($prev)
                            <a href="{{route('front.developro.property', [$prev->floor, Str::slug($prev->floor->name), $prev, Str::slug($prev->name) ])}}" class="bttn bttn-sm">Poprzednie</a>
                        @endif
                    </div>

                    <div class="col-12 col-md-4 d-flex justify-content-center">
                        <a href="{{route('front.developro.floor', [$floor, Str::slug($floor->name)])}}" class="bttn bttn-sm">Plan piętra</a>
                    </div>

                    <div class="col-12 col-md-4 d-flex justify-content-end">
                        @if($next)
                            <a href="{{route('front.developro.property', [$next->floor, Str::slug($next->floor->name), $next, Str::slug($next->name) ])}}" class="bttn bttn-sm">Następne</a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="container property-card">
                <div class="row">
                    <div class="col-12 col-lg-4">
                        <div class="room-status room-status-{{ $property->status }}">
                            {{ roomStatus($property->status) }}
                        </div>
                        <h1>{{$property->name}}</h1>
                        @auth()
                            <div class="row mb-3">
                                @if($property->price_brutto && $property->status == 1)
                                    <div class="col-12 col-sm-6 @if($property->highlighted) promotion-price order-2 text-center text-sm-end @endif">
                                        <h6 class="fs-3 fw-normal mb-0">@money($property->price_brutto)</h6>
                                        <p>@money(($property->price_brutto / $property->area)) / m<sup>2</sup></p>
                                    </div>
                                @endif
                                @if($property->promotion_price && $property->price_brutto && $property->highlighted)
                                    <div class="col-12 col-sm-6 @if($property->highlighted) order-1 text-center text-sm-start @endif">
                                        <h6 class="fs-3 fw-normal mb-0">@money($property->promotion_price)</h6>
                                        <p>@money(($property->promotion_price / $property->area)) / m<sup>2</sup></p>
                                    </div>
                                @endif
                                <div class="col-12 order-3">
                                    @auth
                                        @if($property->has_price_history)
                                            <a href="#" class="btn bttn bttn-sm btn-history mt-3" data-id="{{ $property->id }}">Pokaż historię ceny</a>
                                            <div id="modalHistory"></div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @endauth
                        <div class="pb-60px">
                            @if($property->type == 1)
                            <div class="apartment-data">
                                <div class="apartment-data-name">Liczba pokoi</div>
                                <div class="apartment-data-number">{{$property->rooms}}</div>
                            </div>
                            @endif
                            <div class="apartment-data">
                                <div class="apartment-data-name">Piętro</div>
                                <div class="apartment-data-number">{{$property->floor->name}}</div>
                            </div>
                            @if($property->area)
                                <div class="apartment-data">
                                    <div class="apartment-data-name">Powierzchnia użytkowa</div>
                                    <div class="apartment-data-number">{{$property->area}} m<sup>2</sup></div>
                                </div>
                            @endif
                            @if($property->area_sales)
                                <div class="apartment-data">
                                    <div class="apartment-data-name">Powierzchnia sprzedażowa</div>
                                    <div class="apartment-data-number">{{$property->area_sales}} m<sup>2</sup></div>
                                </div>
                            @endif
                            @if($property->window)
                                <div class="apartment-data">
                                    <div class="apartment-data-name">Wystawa okna:</div>
                                    <div class="apartment-data-number">
                                        <ul class="mb-0 list-unstyled text-end">
                                            @foreach(explode(', ', window($property->window)) as $direction)
                                                <li>{{ $direction }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            @if($property->garden_area)
                                <div class="apartment-data">
                                    <div class="apartment-data-name">Ogródek</div>
                                    <div class="apartment-data-number">{{$property->garden_area}} m<sup>2</sup></div>
                                </div>
                            @endif
                            @if($property->balcony_area)
                                <div class="apartment-data">
                                    <div class="apartment-data-name">Balkon</div>
                                    <div class="apartment-data-number">{{$property->balcony_area}} m<sup>2</sup></div>
                                </div>
                            @endif
                            @if($property->balcony_area_2)
                                <div class="apartment-data">
                                    <div class="apartment-data-name">Balkon 2</div>
                                    <div class="apartment-data-number">{{$property->balcony_area_2}} m<sup>2</sup></div>
                                </div>
                            @endif
                            @if($property->terrace_area)
                                <div class="apartment-data">
                                    <div class="apartment-data-name">Taras</div>
                                    <div class="apartment-data-number">{{$property->terrace_area}} m<sup>2</sup></div>
                                </div>
                            @endif
                            @if($property->loggia_area)
                                <div class="apartment-data">
                                    <div class="apartment-data-name">Loggia</div>
                                    <div class="apartment-data-number">{{$property->loggia_area}} m<sup>2</sup></div>
                                </div>
                            @endif
                            @if($property->parking_space)
                                <div class="apartment-data">
                                    <div class="apartment-data-name">Miejsce postojowe</div>
                                    <div class="apartment-data-number">{{$property->parking_space}} m<sup>2</sup></div>
                                </div>
                            @endif
                            @if($property->garage)
                                <div class="apartment-data">
                                    <div class="apartment-data-name">Garaż</div>
                                    <div class="apartment-data-number">{{$property->garage}} m<sup>2</sup></div>
                                </div>
                            @endif
                        </div>
                        @auth()
                            <div class="row">
                                <div class="col-12">
                                    @auth
                                        @if ($property->status == 1 && $property->type == 1)
                                            <div class="property-related">
                                                @if($property->relatedProperties->isNotEmpty())
                                                    <h5>Przynależne powierzchnie:</h5>
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>Nazwa</th>
                                                            <th class="text-center">Powierzchnia</th>
                                                            <th class="text-center">Cena</th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @forelse ($property->relatedProperties as $related)
                                                            <tr>
                                                                <td valign="middle">{{ $related->name }}</td>
                                                                <td class="text-center" valign="middle">{{ $related->area }} m<sup>2</sup></td>
                                                                <td class="text-center" valign="middle">
                                                                    @money($related->price_brutto)
                                                                </td>
                                                                <td valign="middle" align="right">
                                                                    @if($related->has_price_history)
                                                                        <a href="#" class="btn-history" data-id="{{ $related->id }}"><svg class="d-block" width="16px" height="16px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><path fill="#000000" d="M10.6972,0.468433 C12.354,1.06178 13.7689,2.18485 14.7228,3.66372 C15.6766,5.14258 16.1163,6.89471 15.9736,8.64872 C15.8309,10.4027 15.1138,12.0607 13.9334,13.366 C12.753,14.6712 11.1752,15.5508 9.4443,15.8685 C7.71342,16.1863 5.92606,15.9244 4.35906,15.1235 C2.79206,14.3226 1.53287,13.0274 0.776508,11.4384 C0.539137,10.9397 0.750962,10.343 1.24963,10.1057 C1.74831,9.86829 2.34499,10.0801 2.58236,10.5788 C3.14963,11.7705 4.09402,12.742 5.26927,13.3426 C6.44452,13.9433 7.78504,14.1397 9.08321,13.9014 C10.3814,13.6631 11.5647,13.0034 12.45,12.0245 C13.3353,11.0456 13.8731,9.80205 13.9801,8.48654 C14.0872,7.17103 13.7574,5.85694 13.042,4.74779 C12.3266,3.63864 11.2655,2.79633 10.0229,2.35133 C8.78032,1.90632 7.42568,1.88344 6.1688,2.28624 C5.34644,2.54978 4.59596,2.98593 3.96459,3.5597 L4.69779,4.29291 C5.32776,4.92287 4.88159,6.00002 3.99069,6.00002 L1.77635684e-15,6.00002 L1.77635684e-15,2.00933 C1.77635684e-15,1.11842 1.07714,0.672258 1.70711,1.30222 L2.54916,2.14428 C3.40537,1.3473 4.43126,0.742882 5.55842,0.381656 C7.23428,-0.155411 9.04046,-0.124911 10.6972,0.468433 Z M8,4 C8.55229,4 9,4.44772 9,5 L9,7.58579 L10.7071,9.29289 C11.0976,9.68342 11.0976,10.3166 10.7071,10.7071 C10.3166,11.0976 9.68342,11.0976 9.29289,10.7071 L7,8.41421 L7,5 C7,4.44772 7.44772,4 8,4 Z"/>
                                                                            </svg></a>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            {{-- Fake data example --}}
                                                            @foreach ([
                                                                ['name' => 'Komórka lokatorska A1', 'area' => 45, 'price' => 250000, 'history' => true],
                                                                ['name' => 'Miejsce postojowe', 'area' => 60, 'price' => 320000, 'history' => false],
                                                                ['name' => 'Miejsce parkingowe', 'area' => 75, 'price' => 410000, 'history' => true],
                                                            ] as $fake)
                                                                <tr>
                                                                    <td valign="middle">{{ $fake['name'] }}</td>
                                                                    <td class="text-center" valign="middle">{{ $fake['area'] }} m<sup>2</sup></td>
                                                                    <td class="text-center" valign="middle">
                                                                        @money($fake['price'])
                                                                    </td>
                                                                    <td valign="middle" align="right">
                                                                        <a href="#" class="btn-history" data-id=""><svg class="d-block" width="16px" height="16px" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"><path fill="#000000" d="M10.6972,0.468433 C12.354,1.06178 13.7689,2.18485 14.7228,3.66372 C15.6766,5.14258 16.1163,6.89471 15.9736,8.64872 C15.8309,10.4027 15.1138,12.0607 13.9334,13.366 C12.753,14.6712 11.1752,15.5508 9.4443,15.8685 C7.71342,16.1863 5.92606,15.9244 4.35906,15.1235 C2.79206,14.3226 1.53287,13.0274 0.776508,11.4384 C0.539137,10.9397 0.750962,10.343 1.24963,10.1057 C1.74831,9.86829 2.34499,10.0801 2.58236,10.5788 C3.14963,11.7705 4.09402,12.742 5.26927,13.3426 C6.44452,13.9433 7.78504,14.1397 9.08321,13.9014 C10.3814,13.6631 11.5647,13.0034 12.45,12.0245 C13.3353,11.0456 13.8731,9.80205 13.9801,8.48654 C14.0872,7.17103 13.7574,5.85694 13.042,4.74779 C12.3266,3.63864 11.2655,2.79633 10.0229,2.35133 C8.78032,1.90632 7.42568,1.88344 6.1688,2.28624 C5.34644,2.54978 4.59596,2.98593 3.96459,3.5597 L4.69779,4.29291 C5.32776,4.92287 4.88159,6.00002 3.99069,6.00002 L1.77635684e-15,6.00002 L1.77635684e-15,2.00933 C1.77635684e-15,1.11842 1.07714,0.672258 1.70711,1.30222 L2.54916,2.14428 C3.40537,1.3473 4.43126,0.742882 5.55842,0.381656 C7.23428,-0.155411 9.04046,-0.124911 10.6972,0.468433 Z M8,4 C8.55229,4 9,4.44772 9,5 L9,7.58579 L10.7071,9.29289 C11.0976,9.68342 11.0976,10.3166 10.7071,10.7071 C10.3166,11.0976 9.68342,11.0976 9.29289,10.7071 L7,8.41421 L7,5 C7,4.44772 7.44772,4 8,4 Z"/>
                                                                            </svg></a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endforelse
                                                        </tbody>
                                                    </table>
                                                @endif
                                                @if($property->visitor_related_type != 1)
                                                    <div class="property-offer-check">
                                                        <p>Dodanie powierzchni dodatkowych służy jedynie orientacyjnej wycenie. Ostateczna oferta oraz warunki zakupu zostaną przedstawione przez przedstawiciela sprzedaży.</p>
                                                        <a href="#" class="btn bttn bttn-sm btn-offer mt-3" data-id="{{ $property->id }}">Dodaj do oferty</a>
                                                        <div id="offerModal"></div>
                                                        <table class="table d-none mt-3">
                                                            <thead>
                                                            <tr>
                                                                <th>Nazwa</th>
                                                                <th class="text-center">Powierzchnia</th>
                                                                <th class="text-center">Cena</th>
                                                                <th></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="offerList"></tbody>
                                                        </table>
                                                    </div>
                                                @endif
                                            </div>
                                            @if($property->highlighted && $property->promotion_price_show)
                                                <div class="property-summary fs-5 d-flex" data-totalprice="{{ ($property->promotion_price + $property->relatedProperties->sum('price_brutto')) }}">
                                                    Cena za całość: <span class="ms-auto"><b class="fw-bold" id="totalDisplay">@money(($property->promotion_price + $property->relatedProperties->sum('price_brutto')))</b></span>
                                                </div>
                                            @else
                                                @if($property->price_brutto)
                                                    <div class="property-summary fs-5 d-flex" data-totalprice="{{ ($property->price_brutto + $property->relatedProperties->sum('price_brutto')) }}">
                                                        Cena za całość: <span class="ms-auto"><b class="fw-bold" id="totalDisplay">@money(($property->price_brutto + $property->relatedProperties->sum('price_brutto')))</b></span>
                                                    </div>
                                                @endif
                                            @endif
                                        @endif
                                        <div class="mb-3"></div>
                                    @endauth
                                </div>
                                <div class="col-12">
                                    <ul class="price-component mb-0 list-unstyled">
                                        <li>
                                            Opłata za przeniesienie udziału w gruncie
                                            <span class="ms-auto text-end">
                                                                                                            <span class="d-block"><b>2,500 zł</b></span>
                                                                                                                                                                <span class="small">Obowiązkowy</span>
                                                                                                        </span>
                                        </li>
                                        <li>
                                            Ogrzewanie podłogowe
                                            <span class="ms-auto text-end">
                                                                                                                                                                <span class="small">Opcjonalny</span>
                                                                                                        </span>
                                        </li>
                                        <li>
                                            Udział w infrastrukturze technicznej
                                            <span class="ms-auto text-end">
                                                                                                                                                                <span class="small">Zmienny</span>
                                                                                                        </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endauth

                        <div class="mt-4 mt-sm-5">
                            @if($property->file_pdf)
                                <a href="{{ asset('/investment/property/pdf/'.$property->file_pdf) }}" class="bttn">Karta mieszkania PDF</a>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-lg-8">
                        <div class="property-plan ps-0 ps-lg-5 mt-5 mt-lg-0">
                            <div class="position-relative">
                                <div class="mieszkanie-rzuty">
                                    @if($property->file)
                                        <div class="mieszkanie-rzut">
                                            <a href="{{ asset('/investment/property/'.$property->file) }}" class="swipebox">
                                                <picture>
                                                    <source type="image/webp" srcset="{{ asset('/investment/property/thumbs/webp/'.$property->file_webp) }}">
                                                    <source type="image/jpeg" srcset="{{ asset('/investment/property/thumbs/'.$property->file) }}">
                                                    <img src="{{ asset('/investment/property/thumbs/'.$property->file) }}" alt="{{$property->name}}">
                                                </picture>
                                            </a>
                                        </div>
                                    @endif
                                    @if($property->file2)
                                        <div class="mieszkanie-rzut">
                                            <a href="{{ asset('/investment/property/'.$property->file2) }}" class="swipebox">
                                                <picture>
                                                    <source type="image/webp" srcset="{{ asset('/investment/property/thumbs/webp/'.$property->file2_webp) }}">
                                                    <source type="image/jpeg" srcset="{{ asset('/investment/property/thumbs/'.$property->file2) }}">
                                                    <img src="{{ asset('/investment/property/thumbs/'.$property->file2) }}" alt="{{$property->name}}">
                                                </picture>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pt-0">
            <div class="container">
                <div class="row flex-row-reverse">
                    <div class="col-12">
                        <div class="">
                            <div class="section-title mb-4 mb-sm-5">
                                <div class="sub-section-title">
                                    <span>KONTAKT</span>
                                </div>
                                <h2 class="">Zapytaj o {{$property->name}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        @include('components.contact-form', ['pageTitle' => $property->name, 'back' => 'true' , 'investmentId' => $property->investment->id, 'investmentName' => $property->investment->name, 'propertyId' => $property->id])
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@push('scripts')
    <script>
        $(".mieszkanie-rzuty").responsiveSlides({auto:true, pager:false, nav:true, timeout:5000, random:false, speed: 500});
    </script>
@endpush
