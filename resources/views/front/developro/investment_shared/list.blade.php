<div id="roomsList">
    <div class="container">
        @if($properties->count() > 0)
            @foreach($properties as $room)
                <div class="row">
                    @if($room->price)
                        <span class="ribbon1"><span>Oferta specjalna</span></span>
                    @endif
                    <div class="col-6 col-sm d-flex align-items-center">
                        @if($investment->type == 2)
                            <a href="{{route('front.developro.property', [$room->floor, Str::slug($room->floor->name), $room, Str::slug($room->name) ])}}">
                                <h2>{{$room->name_list}}<br><span>{{$room->number}}</span></h2>
                            </a>
                        @endif
                    </div>
                    <div class="col d-none d-sm-flex align-items-center">
                        @if($room->file)
                            <picture>
                                <source type="image/webp" srcset="/investment/property/list/webp/{{$room->file_webp}}">
                                <source type="image/jpeg" srcset="/investment/property/list/{{$room->file}}">
                                <img src="/investment/property/list/{{$room->file}}" alt="{{$room->name}}">
                            </picture>
                        @endif
                    </div>
                    <div class="col-6 col-sm d-flex align-items-center">
                        <ul class="mb-0 list-unstyled">
                            @if($room->price_brutto && $room->status == 1)
                                @if($room->highlighted && $room->promotion_price)
                                    <li><span class="list-new-price fw-bold">promocja:</span> <b class="list-new-price">@money($room->promotion_price)</b></li>
                                    <li class="list-old-price">cena: <b>@money($room->price_brutto)</b></li>
                                @else
                                    <li>cena: <b>@money($room->price_brutto)</b></li>
                                @endif
                            @endif
                            @if($room->type == 1)
                            <li>pokoje: <b>{{$room->rooms}}</b></li>
                            @endif
                            <li>pow.: <b>{{$room->area}} m<sup>2</sup></b></li>
                        </ul>
                    </div>
                    <div class="col-6 col-sm d-flex align-items-center justify-content-center mt-3 mt-sm-0">
                        <span class="badge room-list-status-{{ $room->status }}">{{ roomStatus($room->status) }}</span>
                    </div>
                    <div class="col-6 col-sm d-flex justify-content-end align-items-center mt-3 mt-sm-0">
                        @if($investment->type == 2)
                            <a href="{{route('front.developro.property', [$room->floor, Str::slug($room->floor->name), $room, Str::slug($room->name) ])}}" class="bttn bttn-sm w-100">ZOBACZ <img src="{{ asset('images/bttn_arrow.svg') }}" alt=""></a>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="row">
                <div class="col-12 text-center">
                    <b>Brak wyników</b>
                </div>
            </div>
        @endif
    </div>
</div>
@once
    @push('style')
        <style>
            /* Promocja - cena promocyjna na czerwono, cena przed obniżką przygaszona i przekreślona */
            #roomsList .list-new-price { color: #d0021b; }
            #roomsList .list-old-price { text-decoration: line-through; opacity: .5; }
        </style>
    @endpush
@endonce
