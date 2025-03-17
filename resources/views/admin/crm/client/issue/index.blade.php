@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-file"></i><a href="{{route('admin.crm.clients.index')}}">Klienci</a><span class="d-inline-flex me-2 ms-2">/</span><a href="{{ route('admin.crm.clients.show', $client->id) }}">{{$client->name}}</a><span class="d-inline-flex me-2 ms-2">/</span>Zgłoszenia</h4>
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
                    <div class="card-body card-body-rem">
                        <div class="table-overflow">
                            <table class="table mb-0" id="sortable">
                                <thead class="thead-default">
                                <tr>
                                    <th>Nazwa</th>
                                    <th>Data przyjęcia</th>
                                    <th>Lokal</th>
                                    <th>Status</th>
                                    <th>Dział</th>
                                    <th>Opiekun</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody class="content">
                                    @foreach($client->issues as $i)
                                        <tr>
                                            <td>{{ $i->title }}</td>
                                            <td>{{ $i->start }}</td>
                                            <td>
                                                @if($i->property)
                                                {{ $i->property->name }}
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge issue-status-{{$i->status}}">
                                                    {{ issueStatus($i->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $i->department->name }}</td>
                                            <td>{{ $i->user->name }} {{ $i->user->surname }}</td>
                                            <td>
                                                <a href="{{ route('admin.crm.issue.show', $i) }}"
                                                   class="btn action-edit-button action-button me-1"
                                                   data-bs-toggle="tooltip"
                                                   data-placement="top"
                                                   data-bs-title="Pokaż zgłoszenie">
                                                    <i class="fe-eye"></i>
                                                </a>
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
    @push('scripts')

    @endpush
@endsection
