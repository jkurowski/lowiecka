@extends('layouts.page', ['body_class' => 'property'])

@section('meta_title', $property->investment->name .' - '.$property->floor->name.' - '.$property->name)
@section('seo_title', '')
@section('seo_description', '')

@section('content')
    <main>
        <section>
            <div class="container mb-5">
                <div id="planNav" class="row">
                    <div class="col-12 col-sm-4 d-flex justify-content-start"><a href="" class="bttn bttn-sm">Poprzednie</a></div>
                    <div class="col-12 col-sm-4 d-flex justify-content-center">
                        <a href="{{route('front.developro.floor', [$floor, Str::slug($floor->name)])}}" class="bttn bttn-sm">Plan piętra</a>
                    </div>
                    <div class="col-12 col-sm-4 d-flex justify-content-end"><a href="" class="bttn bttn-sm">Następne</a></div>
                </div>
            </div>

            <div class="container property-card">
                <div class="row">
                    <div class="col-4">
                        <div class="room-status room-status-{{ $property->status }}">
                            {{ roomStatus($property->status) }}
                        </div>
                        <h1>{{$property->name}}</h1>
                        <div class="d-none">
                            @if($property->price_brutto && $property->status == 1)
                                <div class="fs-3 fw-normal">@money($property->price_brutto)</div>
                            @endif

                            @if($property->highlighted)
                                <div class="d-flex flex-column text-end">
                                    <div class="text-danger">
                                        <span class="text-uppercase d-block">Promocja</span>
                                        <span class="discount d-block fs-3 fw-normal">785 400 zł</span>
                                    </div>

                                    <div>
                                        <span class="d-block text-decoration-line-through fs-10px">785 400 zł</span>
                                        <span class="fs-10px d-block">Najniższa cena z 30 dni przed obniżką 785 400zł</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="pb-60px">
                            <div class="apartment-data">
                                <div class="apartment-data-name">Liczba pokoi</div>
                                <div class="apartment-data-number">{{$property->rooms}}</div>
                            </div>
                            <div class="apartment-data">
                                <div class="apartment-data-name">Piętro</div>
                                <div class="apartment-data-number">{{$property->floor->name}}</div>
                            </div>
                            <div class="apartment-data">
                                <div class="apartment-data-name">Powierzchnia</div>
                                <div class="apartment-data-number">{{$property->area}} m<sup>2</sup></div>
                            </div>
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
                        <div class="mt-5">
                                @if($property->file_pdf)
                                <a href="{{ asset('/investment/property/pdf/'.$property->file_pdf) }}" class="bttn">Karta mieszkania PDF</a>
                            @endif
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="property-plan ps-5">
                            <a href="{{ asset('/investment/property/'.$property->file) }}" class="glightbox">
                                <picture>
                                    <source type="image/webp" srcset="{{ asset('/investment/property/thumbs/webp/'.$property->file_webp) }}">
                                    <source type="image/jpeg" srcset="{{ asset('/investment/property/thumbs/'.$property->file) }}">
                                    <img src="{{ asset('/investment/property/thumbs/'.$property->file) }}" alt="{{$property->name}}">
                                </picture>
                            </a>
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
                            <div class="section-title mb-5">
                                <div class="sub-section-title">
                                    <span>KONTAKT</span>
                                </div>
                                <h2 class="">Zapytaj o {{$property->name}}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        @include('components.contact-form', ['page' => '', 'back' => ''])
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@push('scripts')
    <script>

    </script>
@endpush

