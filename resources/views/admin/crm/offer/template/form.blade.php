@extends('admin.layout')
@section('meta_title', '- ' . $cardTitle)

@section('content')
    @if (Route::is('admin.crm.offer.templates.edit'))
        <form method="POST" action="{{ route('admin.crm.offer.templates.update', $entry->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form method="POST" action="{{ route('admin.crm.offer.templates.store') }}" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="container">
                        <div class="card-head container">
                            <div class="row">
                                <div class="col-12 pl-0">
                                    <h4 class="page-title"><i class="fe-mail"></i><a
                                                href="{{ route('admin.crm.offer.templates') }}"
                                                class="p-0">Szablony ofert</a><span
                                                class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                                </div>
                            </div>
                        </div>
                        @include('components.errors')
                        <div class="card mt-3">
                            @include('form-elements.back-route-button')
                            <div class="card-body control-col12">
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-select', [
                                        'label' => 'Inwestycja',
                                        'name' => 'investment_id',
                                        'selected' => $entry->investment_id,
                                        'select' => $investments
                                    ])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', [
                                        'label' => 'Nazwa',
                                        'name' => 'name',
                                        'value' => $entry->name,
                                        'required' => 1,
                                    ])
                                </div>
                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', [
                                        'label' => 'Opis',
                                        'name' => 'description',
                                        'value' => $entry->description,
                                    ])
                                </div>
                            </div>
                        </div>
                    </div>

                    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
                </form>
        @endsection
