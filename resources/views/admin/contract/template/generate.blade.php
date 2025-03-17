@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    <form method="POST" action="{{ route('admin.contract.template.generate', $entry) }}" enctype="multipart/form-data">
        @csrf
        <div class="container">
            <div class="card-head container">
                <div class="row">
                    <div class="col-12 pl-0">
                        <h4 class="page-title"><i class="fe-book-open"></i><a href="{{route('admin.contract.index')}}" class="p-0">Generator dokumentów</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                @include('form-elements.back-route-button')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info" role="alert">
                                Liczba dostępnych kredytów: {{ getCloudConvertCredits() }}
                            </div>
                        </div>
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.html-select', ['label' => 'Generuj jako', 'name' => 'file_type', 'select' => [
                            '1' => 'Plik .docx',
                            //'2' => 'Plik .pdf'
                            ]])
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if(isset($placeholders) && !empty($placeholders))
                            @foreach($placeholders as $p => $pp)
                                @php
                                    $view = 'admin.contract.form-elements.html-input-' . $pp['type'];

                                    // Check if the view exists, if not fallback to 'html-input-text'
                                    if (!view()->exists($view)) {
                                        $view = 'admin.contract.form-elements.html-input-text';
                                    }
                                @endphp

                                @include($view, [
                                    'label' => $pp['form'],
                                    'name' => $pp['placeholder'],
                                    'value' => '',
                                    'required' => $pp['required'] ? 1 : 0,
                                    'dataType' => $pp['type']
                                ])
                            @endforeach
                            @else
                                <div class="alert alert-warning" role="alert">
                                    Brak znaczników w dokumencie
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('form-elements.submit', ['name' => 'submit', 'value' => 'Generuj'])
    </form>
@endsection
@push('scripts')
    <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>
    <link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
    <script>
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            language: "pl"
        });
    </script>
@endpush
