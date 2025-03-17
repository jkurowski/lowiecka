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
                                <h4 class="page-title">&nbsp;</h4>
                            </div>
                            <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                                <a href="{{ route('admin.crm.clients.preferences.create', $client->id) }}" class="btn btn-primary">Dodaj wpis</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body card-body-rem">
                        <div class="table-overflow">
                            <table class="table mb-0" id="sortable">
                                <thead class="thead-default">
                                <tr>
                                    <th>Inwestycja</th>
                                    <th>Mieszkanie</th>
                                    <th>Metraż od</th>
                                    <th>Metraż do</th>
                                    <th class="text-center">Pokoje</th>
                                    <th>Budżet</th>
                                    <th class="text-center">Przeznaczenie</th>
                                    <th class="text-center">Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody class="content">
                                @foreach($client->preferences as $p)
                                    <tr>
                                        <td>
                                            @if($p->investment_id)
                                            {{ $p->investment->name }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                        <td>{{ ($p->apartment) ?: '-' }}</td>
                                        <td>{{ $p->area_min }}</td>
                                        <td>{{ $p->area_max }}</td>
                                        <td class="text-center">{{ $p->rooms }}</td>
                                        <td>{{ $p->budget }}</td>
                                        <td class="text-center">{{ clientPurpose($p->purpose) }}</td>
                                        <td class="text-center">{!! clientPurposeStatus($p->status) !!}</td>
                                        <td class="option-120">
                                            <div class="btn-group">
                                                @if($p->note)
                                                <a href="#"
                                                   class="btn action-button me-1"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#noteModal"
                                                   data-note="{{ $p->note }}">
                                                    <i class="fe-file-text"></i>
                                                </a>
                                                @endif
                                                <a href="{{route('admin.crm.clients.preferences.edit', [$client, $p])}}" class="btn action-button me-1" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Edytuj wpis">
                                                    <i class="fe-edit"></i>
                                                </a>

                                                <form method="POST" action="{{route('admin.crm.clients.preferences.destroy', [$client, $p])}}">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button
                                                            type="submit" class="btn action-button confirm"
                                                            data-bs-toggle="tooltip"
                                                            data-placement="top"
                                                            data-bs-title="Usuń wpis"
                                                            data-id="{{ $p }}"
                                                    ><i class="fe-trash-2"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Note Modal -->
    <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noteModalLabel">Notatka</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fe-x"></i></button>
                </div>
                <div class="modal-body">
                    <p id="noteContent"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            document.addEventListener('DOMContentLoaded', function () {
                var noteModal = document.getElementById('noteModal');
                noteModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget; // Button that triggered the modal
                    var note = button.getAttribute('data-note'); // Extract note data

                    // Update the modal's content
                    var modalBody = noteModal.querySelector('#noteContent');
                    modalBody.textContent = note;
                });
            });

            @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-right",timeOut:"3000"};toastr.success("{{ session('success') }}"); @endif
        </script>
    @endpush
@endsection
