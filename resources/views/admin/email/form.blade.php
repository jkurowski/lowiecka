@extends('admin.layout')
@section('meta_title', '- ' . $cardTitle)


@section('content')
    @if (Route::is('admin.email.generator.edit'))
        <form method="POST" action="{{ route('admin.email.generator.update', $entry->id) }}" enctype="multipart/form-data">
            @method('PUT')
        @else
            <form method="POST" action="{{ route('admin.email.generator.store') }}" enctype="multipart/form-data">
    @endif
    @csrf
    <div class="container">
        <div class="card-head container">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-mail"></i><a
                            href="{{ route('admin.email.generator.index', ['investment_id' => $investment_id]) }}"
                            class="p-0">Szablony e-mail</a><span
                            class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                </div>
            </div>
        </div>
        @include('components.errors')
        <div class="card mt-3">
            @include('form-elements.back-route-button')
            <div class="card-body control-col12">
                <input type="hidden" name="investment_id" value="{{ $investment_id }}">
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

                @if (Route::currentRouteName() === 'admin.email.generator.create' && request()->route('investment_id') == 0)

                    <input type="hidden" name="meta[template_type]" value="{{ \App\Helpers\TemplateTypes::NEWSLETTER }}">
                @elseif (Route::currentRouteName() === 'admin.email.generator.edit' && request()->route('investment_id') == 0)
                    <input type="hidden" name="meta[template_type]" value="{{ $entry->meta['template_type'] }}">
                @else
                    <div class='row w-100 form-group'>
                        <label for="meta[template_type]" class="col-12 col-form-label control-label pb-2">Typ</label>
                        <div class='col-12 control-col12 control-input position-relative d-flex align-items-center'>
                            <select name="meta[template_type]" id="meta[template_type]" class="form-select"
                                @if (($entry->meta && $entry->is_uploaded) || $entry->investment_id == 0 || $entry->investment_id == null) disabled readonly @endif>
                                @foreach ($templateTypes as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ $entry->meta && $entry->meta['template_type'] == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>
                @endif
            </div>
        </div>



    </div>

    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
    </form>
@endsection
