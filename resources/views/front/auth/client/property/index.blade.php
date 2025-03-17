@extends('front.auth.client.layouts.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-tablet"></i>Mieszkania</h4>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                @foreach($client->properties as $p)
                    <div class="col-12 card mt-3">
                        <div class="card-body card-body-rem">
                            <div class="row">
                                <div class="col-3">
                                    @if ($p->file)
                                        <a href="{{ asset('/investment/property/' . $p->file) }}" class="swipebox" target="_top">
                                            <picture>
                                                <source type="image/webp" srcset="{{ asset('/investment/property/thumbs/webp/' . $p->file_webp) }}">
                                                <source type="image/jpeg" srcset="{{ asset('/investment/property/thumbs/' . $p->file) }}">
                                                <img src="{{ asset('/investment/property/thumbs/' . $p->file) }}" alt="{{ $p->name }}">
                                            </picture>
                                        </a>
                                    @else
                                        <img src="https://placehold.co/600x400?text=Brak+zdjęcia" alt="">
                                    @endif
                                </div>
                                <div class="col-6">
                                    <div class="status-badge mb-2 d-flex">
                                        <span class="badge room-list-status-{{ $p->status }}">{{ roomStatus($p->status) }}</span>
                                    </div>
                                    <h2>{{ $p->name }}</h2>
                                    <h4>{{ $p->investment->name }}</h4>
                                    <hr>
                                    <ul>
                                        <li>Powierzchnia: {{ $p->area }}m<sup>2</sup></li>
                                        <li>Ilość pokoi: {{ $p->rooms }}</li>
                                        <li>Piętro: {{ $p->floor->number }}</li>
                                        <li>Aneks kuchenny</li>
                                        <li>Data podpisania umowy: 03.07.2024</li>
                                    </ul>
                                </div>
                                <div class="col-3 d-flex justify-content-center align-items-center text-center">
                                    <div>
                                        @if($p->price)
                                            <h4>Kwota za całość:</h4>
                                            <h3>{{ number_format($p->price, 2, '.', ' ') }} zł</h3>
                                        @endif
                                        @if($p->payments->count() > 0)
                                            @if($p->latestPayment)
                                                <h4>Kwota najbliższej płatności:</h4>
                                                <h3>{{ number_format($p->latestPayment->amount, 2, ',', ' ') }} zł</h3>
                                                <h4 class="mt-3">Najbliższy termin płatności:</h4>
                                                <h3>{{ \Illuminate\Support\Carbon::parse($p->latestPayment->due_date)->format('Y-m-d') }}</h3>
                                            @endif
                                        @endif
                                        <a href="{{ route('front.client.area.property.show', $p) }}" class="btn btn-primary mt-3">Pokaż harmonogram</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 pt-3 border-top">
                                <div class="col-2 d-flex align-items-center text-center">
                                    <h4>Powiązane produkty:</h4>
                                </div>
                                <div class="col-3 border-start text-center">
                                    <h4><i class="fe-check-circle text-success"></i> Komórka lokatorska nr. 4</h4>
                                </div>
                                <div class="col-3 border-start text-center">
                                    <h4><i class="fe-check-circle text-success"></i> Miejsce postojowe nr. 28</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
