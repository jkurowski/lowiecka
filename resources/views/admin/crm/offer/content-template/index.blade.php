@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-book-open"></i>Oferty</h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                        <a href="{{ route('admin.crm.offer.content-templates.create') }}" class="btn btn-primary">Nowy szablon</a>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.crm.offer.offer-share.menu')

        <div class="card mt-3">
            <div class="card-body card-body-rem p-0">
                <div class="table-overflow">
                    <table class="table mb-0">
                        <thead class="thead-default">
                        <tr>
                            <th>Tytuł</th>
                            <th>Opis</th>
                            <th class="text-center">Data utworzenia</th>
                            <th class="text-center">Data edycji</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody class="content">
                        @foreach($templates as $item)
                            <tr id="recordsArray_{{ $item->id }}">
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->description }}</td>
                                <td class="text-center">{{ $item->created_at }}</td>
                                <td class="text-center">{{ $item->updated_at }}</td>
                                <td class="option-120">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.crm.offer.content-templates.edit', ['id' => $item->id]) }}"
                                           class="btn action-button me-1"
                                           data-bs-toggle="tooltip"
                                           data-placement="top"
                                           data-bs-title="Edytuj wpis">
                                            <i class="fe-edit"></i>
                                        </a>
                                        <form method="POST"
                                              action="{{ route('admin.crm.offer.content-templates.destroy', ['id' => $item->id]) }}">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit"
                                                    class="btn action-button confirm"
                                                    data-bs-toggle="tooltip"
                                                    data-placement="top"
                                                    data-bs-title="Usuń wpis"
                                                    data-id="{{ $item->id }}">
                                                <i class="fe-trash-2"></i>
                                            </button>
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
                    <a href="{{ route('admin.crm.offer.content-templates.create') }}" class="btn btn-primary">Nowy szablon</a>
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
            @if (session('success')) toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-left",timeOut:"3000"};toastr.success("{{ session('success') }}"); @endif
        </script>
    @endpush
@endsection
