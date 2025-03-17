@extends('admin.layout')


@section('content')
    <div class="container-fluid">



        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title d-flex"><i class="fe-inbox"></i>Harmonogram wysyłki</h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">

                    </div>
                </div>
            </div>
        </div>




        @include('admin.mass-mail.top-menu')
        @include('components.errors')
        @include('admin.mass-mail.schedule-counts', ['scheduleCounts' => $scheduleCounts])

        <div class="card my-4">
            <div class="card-body filters">

                <div class="row gy-3 w-100 mb-3 table-filters">
                    <div class="col-12">
                        <p class="h6 mb-0">Filtry</p>
                    </div>
                    <div class="col-3">
                        <div class="form-control border-0 p-0">
                            <label class="form-label small fw-semibold" for="scheduleSearchInput">Wyszukaj</label>
                            <input type="text" class="form-control" placeholder="Wyszukaj" id="scheduleSearchInput">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-control border-0 p-0">
                            <label class="form-label small fw-semibold" for="scheduleSelectStatus">Status</label>
                            <select class="form-control" id="scheduleSelectStatus">
                                <option value="all">Wszystkie</option>
                                <option value="Oczekuje">Oczekuje</option>
                                <option value="Wysłane">Wysłane</option>
                                <option value="Błąd">Błąd</option>
                                <option value="Brak zgody RODO">Brak zgody RODO</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card overflow-hidden mt-4">
            <table class="table" style="table-layout: fixed;" id="schedule-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Data wysyłki</th>
                        <th>Status</th>
                        <th>Temat</th>
                        <th>Utworzono</th>
                        <th>Otwarty</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
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
                initializeModal();
            });


            const initializeTable = () => {
                const tableElement = document.querySelector('#schedule-table');
                return new DataTable(tableElement, {
                    info: false,
                    dom: 'rtip',
                    paging: true,
                    data: @json($scheduleEntries),
                    sort: true,
                    language: {
                        url: "{{ asset('/js/polish.json') }}"
                    },
                    columns: [{
                            data: 'id'
                        },
                        {
                            data: 'email_address'
                        },
                        {
                            data: 'scheduled_date',
                            render: formatDate
                        },
                        {
                            data: 'status',
                            render: getStatusBadge
                        },
                        {
                            data: 'subject'
                        },
                        {
                            data: 'created_at',
                            render: formatDate
                        },
                        {
                            data: 'opened_at',
                            render: (_, __, row) => row.opened_at ? formatDate(row.opened_at) : '-'
                        },
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


            const getStatusBadge = status => {
                const badgeClasses = {
                    'Wysłane': 'bg-success',
                    'Błąd': 'bg-danger',
                    'Brak zgody RODO': 'bg-danger opacity-75',
                    'default': 'bg-info'

                };
                const badgeClass = badgeClasses[status] || badgeClasses.default;
                return `<span class="badge ${badgeClass}">${status}</span>`;
            };


            const createActionButton = id => {
                const route = "{{ route('admin.mass-mail.schedule.destroy', ':id') }}".replace(':id', id);
                return `
                <button type="button" onclick="confirmDelete(${id}, '${route}')" class="btn btn-outline-danger btn-sm">
                    <i class="fe-trash"></i>
                </button>
            `;
            };


            const initializeFilters = table => {
                const searchUserInput = document.getElementById('scheduleSearchInput');
                const scheduleSelectStatus = document.getElementById('scheduleSelectStatus');

                const applyFilters = () => {
                    table.columns().search('').draw();
                    const searchTerm = searchUserInput.value.toLowerCase();
                    const statusFilter = scheduleSelectStatus.value;

                    table.columns().every(function() {
                        if (this.index() === 3 && statusFilter !== 'all') {
                            this.search(statusFilter);
                        }
                    });

                    table.search(searchTerm).draw();
                };

                ['input', 'change'].forEach(eventType => {
                    [searchUserInput, scheduleSelectStatus].forEach(element => {
                        element.addEventListener(eventType, applyFilters);
                    });
                });
            };

            const initializeModal = () => {
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

                const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                const confirmBtn = document.getElementById('confirmDeleteBtn');

                confirmBtn.addEventListener('click', () => {
                    const id = modal._element.dataset.id;
                    const route = modal._element.dataset.route;
                    deleteSchedule(id, route);
                    modal.hide();
                });
            };


            const confirmDelete = (id, route) => {
                const modalElement = document.getElementById('deleteConfirmModal');
                modalElement.dataset.id = id;
                modalElement.dataset.route = route;
                const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                modal.show();
            };


            const deleteSchedule = (id, route) => {
                fetch(route, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => handleDeleteResponse(data, id))
                    .catch(handleDeleteError)
                    .finally(removeModalBackdrop);
            };

            const handleDeleteResponse = (data, id) => {
                if (data.success) {
                    toastr.success('Wpis został poprawnie usunięty');
                    setTimeout(() => window.location.reload(), 1000);
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


            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: 3000
            };
        </script>
    @endpush
