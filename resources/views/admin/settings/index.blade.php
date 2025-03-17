@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-sliders"></i>Ustawienia</h4>
                </div>
            </div>
        </div>

        @include('admin.settings.menu')

        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    @yield('settings')
                </div>
            </div>
        </div>
    </div>
@endsection
