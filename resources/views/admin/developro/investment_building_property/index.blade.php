@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-home"></i><a href="{{route('admin.developro.investment.index')}}">Inwestycje</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{route('admin.developro.investment.buildings.index', $investment)}}">{{$investment->name}}</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{route('admin.developro.investment.building.floors.index', [$investment, $building])}}">{{$building->name}}</a><span class="d-inline-flex me-2 ms-2">/</span>{{$floor->name}}</h4>
                </div>
            </div>
        </div>
        @include('admin.developro.investment_shared.menu')

        <div class="row">
            @isset($count_property_status[1])
                <div class="col-3">
                    <div class="floor-status floor-status-1 rounded">
                        Na sprzedaż<b class="float-end">{{$count_property_status[1]}}</b>
                    </div>
                </div>
            @endisset
            @isset($count_property_status[2])
                <div class="col-3">
                    <div class="floor-status floor-status-2 rounded">
                        Rezerwacja<b class="float-end">{{$count_property_status[2]}}</b>
                    </div>
                </div>
            @endisset

            @isset($count_property_status[3])
                <div class="col-3">
                    <div class="floor-status floor-status-3 rounded">
                        Sprzedane<b class="float-end">{{$count_property_status[3]}}</b>
                    </div>
                </div>
            @endisset

            @isset($count_property_status[4])
                <div class="col-3">
                    <div class="floor-status floor-status-4 rounded">
                        Wynajęte<b class="float-end">{{$count_property_status[4]}}</b>
                    </div>
                </div>
            @endisset
            @isset($count_property_status[5])
                <div class="col-3">
                    <div class="floor-status floor-status-5 rounded">
                        Umowa deweloperska<b class="float-end">{{$count_property_status[5]}}</b>
                    </div>
                </div>
            @endisset
            @isset($count_property_status[6])
                <div class="col-3">
                    <div class="floor-status floor-status-6 rounded">
                        Umowa przedsprzedażowa<b class="float-end">{{$count_property_status[6]}}</b>
                    </div>
                </div>
            @endisset
        </div>

        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table class="table mb-0" id="sortable">
                        <thead class="thead-default">
                        <tr>
                            <th>#</th>
                            <th>Nazwa</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Pokoje</th>
                            <th class="text-center">Metraż</th>
                            <th class="text-center">Wizyty</th>
                            <th class="text-center">Wiadomości</th>
                            <th class="text-center">Widoczność</th>
                            <th class="text-center">Klient</th>
                            <th class="text-center">Data modyfikacji</th>
                            <th class="text-center">Data sprzedaży</th>
                            <th class="text-center">Klient</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach ($list->floorRooms as $index => $p)
                            <tr id="recordsArray_{{ $p->id }}">
                                <th class="position" scope="row">{{ $index+1 }}</th>
                                <td>{{ $p->name }}</td>
                                <td><span class="badge room-list-status-{{ $p->status }}">{{ roomStatus($p->status) }}</span></td>
                                <td class="text-center">{{ $p->rooms }}</td>
                                <td class="text-center">{{ $p->area }} m<sup>2</sup></td>
                                <td class="text-center">{{ $p->views }}</td>
                                <td class="text-center">{{ $p->roomsNotifications()->count() }}</td>
                                <td class="text-center">{!! status($p->active) !!}</td>
                                <td class="text-center">{{ $p->client_id }}</td>
                                <td class="text-center">{!! tableDate($p->updated_at) !!}</td>
                                <td class="text-center">{!! tableDate($p->saled_at) !!}</td>
                                <td class="text-center">
                                    @if($p->client_id != null)
                                        <a href="{{ route('admin.crm.clients.show', $p->client->id) }}">{{ $p->client->name }} {{ $p->client->lastname }}</a>
                                    @endif
                                </td>
                                <td class="option-120">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.crm.handover', $p) }}" class="btn action-button me-1" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Odbiór" data-id="{{ $p->id }}"><svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg></a>

                                        <a href="#" class="btn action-button me-1 btn-activity" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Historia" data-id="{{ $p->id }}"><i class="fe-activity"></i></a>

                                        <a href="{{route('admin.developro.investment.message.index', [$investment, $p])}}" class="btn action-button me-1" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Pokaż wiadomości"><i class="fe-mail"></i></a>
                                        <a href="{{route('admin.developro.investment.building.floor.properties.edit', [$investment, $building, $floor, $p])}}" class="btn action-button me-1" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Edytuj"><i class="fe-edit"></i></a>
                                        <form method="POST" action="{{route('admin.developro.investment.building.floor.properties.destroy', [$investment, $building, $floor, $p])}}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn action-button confirm" data-bs-toggle="tooltip" data-placement="top" data-bs-title="Usuń" data-id="{{ $p->id }}"><i class="fe-trash-2"></i></button>
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
    <div class="form-group form-group-submit">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <a href="{{route('admin.developro.investment.building.floor.properties.create', [$investment, $building, $floor])}}" class="btn btn-primary">Dodaj</a>
                </div>
            </div>
        </div>
    </div>
    <div id="modalHistory"></div>
    @routes('property')
    @push('scripts')
        <script>
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-right",timeOut:"3000"};toastr.success("{{ session('success') }}"); @endif
            $(document).ready(function() {
                $(".btn-activity").click((event) => {
                    event.preventDefault();
                    const modalHolder = $('#modalHistory');
                    const dataId = event.currentTarget.dataset.id;
                    modalHolder.empty();

                    jQuery.ajax({
                        url: route('admin.developro.investment.property.history', {
                            investment: '{{ $investment->id }}',
                            property: dataId,
                        }),
                        success: function(response) {
                            if (response) {
                                modalHolder.append(response);

                                const modalElement = document.getElementById('portletModal');
                                const bootstrapModal = new bootstrap.Modal(modalElement);
                                bootstrapModal.show();

                                modalElement.addEventListener('hidden.bs.modal', function() {
                                    modalHolder.empty();
                                }, { once: true });
                            } else {
                                alert('Error');
                            }
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
