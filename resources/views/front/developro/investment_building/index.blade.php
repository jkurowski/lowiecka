@extends('layouts.page', ['body_class' => 'investments'])
@section('meta_title', $investment->name)

@section('content')
    <div class="container">
        <div class="row border-bottom pb-3 mb-3">
            <div class="col-8">
                <h1>{{$investment->name}} - {{$building->name}}</h1>
            </div>
            <div class="col-4 text-right">
                <a href="{{route('front.investment.show', $investment)}}" class="bttn bttn-right"><i class="las la-arrow-left"></i> Wróć do listy</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                @if($building->file)
                    <div id="plan">
                        <div id="plan-holder"><img src="{{ asset('/investment/building/'.$building->file.'') }}" alt="{{$building->name}}" id="invesmentplan" usemap="#invesmentplan"></div>
                        <map name="invesmentplan">
                            <map name="invesmentplan">
                                @foreach($investment->buildingFloors as $floor)
                                    @if($floor->html)
                                        <area shape="poly" href="{{route('front.investment.building.floor.index', [$investment, $building, $floor])}}" data-item="{{$floor->id}}" title="{{$floor->name}}" alt="floor-{{$floor->id}}" data-floornumber="{{$floor->id}}" data-floortype="{{$floor->type}}" coords="{{cords($floor->html)}}">
                                    @endif
                                @endforeach
                            </map>
                        </map>
                    </div>
                @endif
            </div>
        </div>

        @include('front.investment_shared.filtr')

        @include('front.investment_shared.sort')

        <div class="row">
            @include('front.investment_shared.list', ['investment' => $investment])
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('/js/plan/imagemapster.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/plan/plan.js') }}" charset="utf-8"></script>
@endpush
