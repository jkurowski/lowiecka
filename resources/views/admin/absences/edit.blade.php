@extends('admin.layout')


@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title d-flex"><i class="fe-inbox"></i>
                            @if ($absence->id)
                                Edytuj nieobecność
                            @else
                                Nowa nieobecność
                            @endif
                        </h4>
                    </div>

                </div>
            </div>
        </div>
        @include('admin.absences.top-menu')
        @include('components.errors')

        <div class="card mt-4 col-12 col-md-6">
            <div class="card-body">
                @include('admin.absences.form', [
                    'absence' => $absence,
                    'users' => $users ?? [],
                    'action' => $absence->id
                        ? route('admin.absences.update', $absence)
                        : route('admin.absences.store'),
                    'method' => $absence->id ? 'put' : 'post',
                ])
            </div>
        </div>


    </div>
@endsection
