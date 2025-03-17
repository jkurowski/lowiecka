@extends('admin.layout')


@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title d-flex"><i class="fe-inbox"></i>Nieobecności</h4>
                    </div>

                </div>
            </div>
        </div>
        @include('admin.absences.top-menu')
        @session('success')
            <div class="alert alert-success my-4">
                {{ session('success') }}
            </div>
        @endsession
        @include('components.errors')



        <div class="card my-4">
            <div class="card-body filters">
                <div class="row gy-3 w-100 mb-3 table-filters">
                    <div class="col-12">
                        <p class="h6 mb-0">Filtry</p>
                    </div>
                    <div class="col-3">
                        <div class="form-control border-0 p-0">
                            <label class="form-label small fw-semibold" for="absencesSearchInput">Wyszukaj</label>
                            <input type="text" class="form-control" placeholder="Wyszukaj" id="absencesSearchInput">
                        </div>
                    </div>
                    {{-- <div class="col-3">
                        <div class="form-control border-0 p-0">
                            <label class="form-label small fw-semibold" for="absencesSelectStatus">Status</label>
                            <select class="form-control" id="absencesSelectStatus">
                                <option value="all">Wszystkie</option>
                                <option value="Oczekuje">Oczekuje</option>
                                <option value="Wysłane">Wysłane</option>
                                <option value="Błąd">Błąd</option>
                            </select>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

        <div class="card overflow-hidden mt-4">
            <table class="table" style="table-layout: fixed;" id="absences-table">
                <thead>
                    <tr>
                        <th>Imię i nazwisko</th>
                        <th>Email</th>
                        <th>Data rozpoczęcia</th>
                        <th>Data zakończenia</th>
                        <th>Powód</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('/js/datatables.min.js') }}" charset="utf-8"></script>
    <link href="{{ asset('/css/datatables.min.css') }}" rel="stylesheet">
    <script>
        // Main initialization
        document.addEventListener('DOMContentLoaded', () => {
            const table = initializeTable();
            initializeFilters(table);
            initializeModal(table);
        });


        const initializeTable = () => {
            const tableElement = document.querySelector('#absences-table');
            window.absences = @json($absences);
            return new DataTable(tableElement, {
                info: false,
                dom: 'rtip',
                paging: true,
                data: @json($absences),
                sort: true,
                language: {
                    url: "{{ asset('/js/polish.json') }}"
                },
                columns: [
                    {
                        data: 'user',
                        render: (_, __, row) => `${row.user.name} ${row.user.surname ?? ''}`
                    },
                    {
                        data: 'email',
                        render: (_, __, row) => row.user.email
                    },
                    {
                        data: 'start_date',
                        render: formatDate
                    },
                    {
                        data: 'end_date',
                        render: formatDate
                    },
                    { data: 'reason' },
                    {
                        data: 'actions',
                        render: (_, __, row) => createActionButton(row.id)
                    }
                ]
            });
        };


        const formatDate = dateString => new Date(dateString).toLocaleString('pl-PL', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });


        const createActionButton = id => {
            const route = "{{ route('admin.absences.destroy', ':id') }}".replace(':id', id);
            const editRoute = "{{ route('admin.absences.edit', ':id') }}".replace(':id', id);
            return `
                    <button type="button" data-id="${id}" data-route="${route}" class="btn btn-sm link-danger delete-btn">
                        <i class="fe-trash"></i>
                    </button>
                    <a href="${editRoute}" class="btn btn-primary btn-sm edit-btn" title="Edit">
                        <i class="fe-edit"></i>
                    </a>
                `;
        };


        const initializeFilters = table => {
            const searchAbsencesInput = document.getElementById('absencesSearchInput');
            const applyFilters = () => table.search(searchAbsencesInput.value.toLowerCase()).draw();
            searchAbsencesInput.addEventListener('input', applyFilters);
        };

        const initializeModal = (table) => {
            const modalHtml = `
        <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">Potwierdzenie usunięcia</h5>
                        <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">&times;</button>
                    </div>
                    <div class="modal-body">
                        Czy na pewno chcesz usunąć ten wpis?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Usuń</button>
                    </div>
                </div>
            </div>
        </div>
    `;
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            const modalElement = document.getElementById('deleteConfirmModal');
            const modal = new bootstrap.Modal(modalElement);
            const confirmBtn = document.getElementById('confirmDeleteBtn');

            confirmBtn.addEventListener('click', () => {
                const { id, route } = modalElement.dataset;
                deleteSchedule(id, route, table);
                modal.hide();
            });
        };


        const confirmDelete = (id, route, modal) => {
            modal.dataset.id = id;
            modal.dataset.route = route;
            const modalInstance = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
            modalInstance.show();
        };


        const deleteSchedule = (id, route, table) => {
            fetch(route, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => handleDeleteResponse(data, id, table))
                .catch(handleDeleteError)
                .finally(removeModalBackdrop);
        };

        const handleDeleteResponse = (data, id, table) => {
            if (data.success) {
                toastr.success('Wpis został poprawnie usunięty');
                removeTableRow(id, table);
            } else {
                toastr.error('Wystąpił błąd podczas usuwania wpisu');
            }
        };

        const handleDeleteError = (error) => {
            console.error('Error:', error);
            toastr.error('Wystąpił błąd podczas usuwania wpisu');
        };

        const removeModalBackdrop = () => {
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) backdrop.remove();
            document.body.classList.remove('modal-open');
        };

        const removeTableRow = (id, table) => {
            const rowIndex = table.rows().indexes().filter(index => table.row(index).data().id === parseInt(id));
            if (rowIndex.length > 0) {
                table.row(rowIndex[0]).remove().draw(false);
            } else {
                console.warn(`Row with id ${id} not found in the table.`);
            }
        };

        // Event delegation for delete buttons
        document.querySelector('#absences-table').addEventListener('click', (event) => {
            const deleteBtn = event.target.closest('.delete-btn');
            if (deleteBtn) {
                const { id, route } = deleteBtn.dataset;
                const modalElement = document.getElementById('deleteConfirmModal');
                confirmDelete(id, route, modalElement);
            }
        });

        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 3000
        };
    </script>
@endpush

