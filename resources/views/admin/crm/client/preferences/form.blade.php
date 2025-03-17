@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-file"></i><a href="{{route('admin.crm.clients.index')}}">Klienci</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{ route('admin.crm.clients.show', $client->id) }}">{{$client->name}}</a><span class="d-inline-flex me-2 ms-2">/</span>Zainteresowania</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit"></div>
            </div>
        </div>
        @include('admin.crm.client.client_shared.menu')
        <div class="row">
            <div class="col-3">
                @include('admin.crm.client.client_shared.aside')
            </div>
            <div class="col-9">
                <div class="card mt-3">
                    <div class="card-head container-fluid">
                        <div class="row">
                            <div class="col-6 pl-0">
                                <h4 class="page-title"><i class="fe-star"></i>Dodaj preferencje klienta</h4>
                            </div>
                            <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                                <a href="{{ route('admin.crm.clients.preferences.index', $client->id) }}" class="btn btn-primary">Wróć do listy</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    @if(Route::is('admin.crm.clients.preferences.edit'))
                        <form method="POST" action="{{route('admin.crm.clients.preferences.update', [$client->id, $entry->id])}}" enctype="multipart/form-data" class="card-body control-col12">
                            @method('PUT')
                            @else
                                <form method="POST" action="{{route('admin.crm.clients.preferences.store', ['client' => $client])}}" enctype="multipart/form-data" class="card-body control-col12">
                                    @endif
                                    @csrf
                                    @if ($errors->any())
                                        <div class="row w-100 mb-4">
                                            <div class="col-12">
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row w-100 mb-4">
                                        <div class="col-4">
                                            @include('form-elements.html-select', [
                                                'label' => 'Status',
                                                'name' => 'status',
                                                'selected' => $entry->status,
                                                'select' => [
                                                    '1' => 'Kontakt wstępny',
                                                    '2' => 'Wysłana oferta',
                                                    '3' => 'Rezygnacja z oferty',
                                                    '4' => 'Zainteresowany wysłaną ofertą',
                                                    '5' => 'Kontakt odroczony',
                                                ],
                                            ])
                                        </div>
                                        <div class="col-4">
                                            @include('form-elements.html-select', [
                                                'label' => 'Przeznaczenie',
                                                'name' => 'purpose',
                                                'selected' => $entry->purpose,
                                                'select' => ['1' => 'Prywatne', '2' => 'Inwestycyjne'],
                                            ])
                                        </div>
                                    </div>
                                    <div class="row w-100 mb-4">
                                        <div class="col-4">
                                            @include('form-elements.html-input-text', [
                                                'label' => 'Miasto',
                                                'name' => 'city_id',
                                                'value' => $entry->city_id
                                            ])
                                        </div>
                                        <div class="col-4">
                                            @include('form-elements.html-select', [
                                                'label' => 'Inwestycja',
                                                'name' => 'investment_id',
                                                'selected' => $entry->investment_id,
                                                'select' => $investments,
                                            ])
                                        </div>
                                        <div class="col-4">
                                            @include('form-elements.html-input-text', [
                                                'label' => 'Mieszkanie/a',
                                                'name' => 'apartment',
                                                'value' => $entry->apartment
                                            ])
                                        </div>
                                    </div>
                                    <div class="row w-100 mb-4">
                                        <div class="col-2">
                                            @include('form-elements.html-input-text', [
                                                'label' => 'Powierzchnia od',
                                                'name' => 'area_min',
                                                'value' => $entry->area_min
                                            ])
                                        </div>
                                        <div class="col-2">
                                            @include('form-elements.html-input-text', [
                                                'label' => 'Powierzchnia do',
                                                'name' => 'area_max',
                                                'value' => $entry->area_max
                                            ])
                                        </div>
                                        <div class="col-4">
                                            @include('form-elements.html-select', [
                                                'label' => 'Liczba pokoi',
                                                'name' => 'rooms',
                                                'selected' => '',
                                                'select' => [
                                                    '1' => '1 pokój',
                                                    '2' => '2 pokoje',
                                                    '3' => '3 pokoje',
                                                    '4' => '4 pokoje',
                                                    '5' => '5 pokoi',
                                                    '6' => '6 pokoi',
                                                    ],
                                            ])
                                        </div>
                                        <div class="col-4">
                                            @include('form-elements.html-input-text', [
                                                'label' => 'Budżet',
                                                'name' => 'budget',
                                                'value' => $entry->budget,
                                            ])
                                        </div>
                                    </div>
                                    <div class="row w-100 mb-4">
                                        <div class="col-12">
                                            @include('form-elements.textarea-fullwidth', ['label' => 'Notatka', 'name' => 'note', 'value' => $entry->note, 'rows' => 9])
                                        </div>
                                    </div>
                                    <div class="row w-100">
                                        <div class="col-12 text-end">
                                            <input type="hidden" name="client_id" value="{{ $client->id }}">
                                            <input name="submit" id="submit" value="Zapisz" class="btn btn-primary" type="submit">
                                        </div>
                                    </div>
                                </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('/js/typeahead.min.js') }}" charset="utf-8"></script>
        <script>
            const cityInput = $('#city_id'),
                cities = new Bloodhound({
                    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('Name'),
                    queryTokenizer: Bloodhound.tokenizers.whitespace,
                    prefetch: {
                        url: '/cities-clean.json',
                        cache: true
                    }
                });

            // Initialize Bloodhound
            cities.initialize();

            // Configure Typeahead
            cityInput.typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 4
                },
                {
                    name: 'cities',
                    display: 'Name',
                    limit: 10,
                    templates: {
                        suggestion: function (data) {
                            return `
                    <div class="item">
                        <div class="row">
                            <div class="col-12">
                                <h4>${data.Name}</h4>
                            </div>
                        </div>
                    </div>`;
                        }
                    },
                    source: cities
                });
            cityInput.on('typeahead:select', function (ev, suggestion) {
                console.log('Selected suggestion:', suggestion);
                console.log('Selected city:', cityInput.val());
            });
        </script>
    @endpush
@endsection
