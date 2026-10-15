@extends('admin.settings.index')

@section('settings')
    <form method="POST" action="{{route('admin.settings.popup.store')}}" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid p-0">
            <div class="card p-4">
                <div class="card-body p-3 control-col12">
                    <div class="row w-100 form-group">
                        @include('form-elements.html-select', ['label' => 'Status', 'name' => 'popup_status', 'selected' => settings()->get("popup_status"), 'select' => ['0' => 'Wyłączony', '1' => 'Włączony']])
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.html-select', ['label' => 'Uruchom baner', 'name' => 'popup_mode', 'selected' => settings()->get("popup_mode"), 'select' => ['0' => 'Tylko raz', '1' => 'Za każdym razem']])
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.html-input-number', ['label' => 'Czas wyświetlenia', 'sublabel'=> '0 lub puste = baner zamyka tylko odwiedzający (krzyżykiem). 1000 = 1 sekunda', 'name' => 'popup_timeout', 'value' => settings()->get("popup_timeout")])
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.html-input-date', ['label' => 'Wyświetlaj od dnia', 'sublabel'=> 'Pierwszy dzień wyświetlania (włącznie). Puste = od razu', 'name' => 'popup_from', 'value' => settings()->get("popup_from")])
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.html-input-date', ['label' => 'Wyświetlaj do dnia', 'sublabel'=> 'Ostatni dzień wyświetlania (włącznie). Puste = bez końca', 'name' => 'popup_until', 'value' => settings()->get("popup_until")])
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.textarea-fullwidth', ['label' => 'Treść / grafika banera', 'name' => 'popup_text', 'value' => settings()->get("popup_text"), 'rows' => 11, 'class' => 'tinymce'])
                    </div>
                </div>
            </div>
            <div class="form-group form-group-submit">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <input name="submit" id="submit" value="Zapisz" class="btn btn-primary" type="submit">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('form-elements.tintmce')
    @push('scripts')
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>
        <link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
        <script>
            $('.datepicker').datepicker({ format: 'yyyy-mm-dd', todayHighlight: true, language: 'pl', clearBtn: true });

            @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-right",timeOut:"3000"};toastr.success("{{ session('success') }}"); @endif
        </script>
    @endpush
@endsection
