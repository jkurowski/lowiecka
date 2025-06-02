<div id="roomsList">
    <div class="container">
        @if($properties->count() > 0)
            @foreach($properties as $room)
                <div class="row">
                    @if($room->price)
                        <span class="ribbon1"><span>Oferta specjalna</span></span>
                    @endif
                    <div class="col d-flex align-items-center">
                        @if($investment->type == 2)
                            <a href="{{route('front.developro.property', [$room->floor, Str::slug($room->floor->name), $room, Str::slug($room->name) ])}}">
                                <h2>{{$room->name_list}}<br><span>{{$room->number}}</span></h2>
                            </a>
                        @endif
                    </div>
                    <div class="col d-flex align-items-center">
                        @if($room->file)
                            <picture>
                                <source type="image/webp" srcset="/investment/property/list/webp/{{$room->file_webp}}">
                                <source type="image/jpeg" srcset="/investment/property/list/{{$room->file}}">
                                <img src="/investment/property/list/{{$room->file}}" alt="{{$room->name}}">
                            </picture>
                        @endif
                    </div>
                    <div class="col d-flex align-items-center">
                        <ul class="mb-0 list-unstyled">
                            @if($room->price_brutto && $room->status == 1)
                                <li>cena: <b>@money($room->price_brutto)</b></li>
                            @endif
                            <li>pokoje: <b>{{$room->rooms}}</b></li>
                            <li>powierzchnia: <b>{{$room->area}} m<sup>2</sup></b></li>
                        </ul>
                    </div>
                    <div class="col d-flex align-items-center justify-content-center">
                        <span class="badge room-list-status-{{ $room->status }}">{{ roomStatus($room->status) }}</span>
                    </div>
                    <div class="col d-flex justify-content-end align-items-center">
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
