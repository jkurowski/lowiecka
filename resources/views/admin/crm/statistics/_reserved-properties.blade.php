<div class='section-_reserved-properties'>
    <div class="card-head container-fluid">
        <div class="row">
            <div class="col-12 pl-0">
                <h4 class="page-title"><i class="fe-bar-chart-line-"></i>Mieszkania zarezerwowane</h4>
            </div>
        </div>
    </div>
    <div class="card my-4">
        <div class="card-body filters">
            <div class="row gy-3 w-100 mb-3 table-filters">
                <div class="col-12">
                    <p class="h6 mb-0">Filtry</p>
                </div>
                <div class="col-3">
                    <div class="form-control border-0 p-0">
                        <label class="form-label small fw-semibold" for="searchReservedProperties">Wyszukaj</label>
                        <input type="text" class="form-control" placeholder="Wyszukaj" id="searchReservedProperties">
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="card overflow-hidden">
        <table class="table" style="table-layout: fixed;" id="reserved-properties-table">
            <thead>
                <tr>
                    <th>Nazwa</th>
                    <th>Inwestycja</th>
                    <th>Klient</th>
                    <th>Rezerwacja do</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@push('scripts')
    <script src="{{ asset('/js/datatables.min.js') }}" charset="utf-8"></script>
    <link href="{{ asset('/css/datatables.min.css') }}" rel="stylesheet">
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            initializeTable();
        });

        const initializeTable = () => {
            const searchReservedProperties = document.getElementById('searchReservedProperties');
            const table = $('#reserved-properties-table').DataTable({
                "info": false,
                dom: 'rtip',
                paging: true,
                data: @json($reservedProperties),
                language: {
                    "url": "{{ asset('/js/polish.json') }}"
                },
                columns: [{
                        data: 'name',
                        render: (data, type, row, meta) => {
                            if (row.link_to_property && data) {
                                return `<a href="${row.link_to_property}" target="_blank">${data}</a>`;
                            }
                            if (data) {
                                return data
                            }
                            return '-'
                        }
                    },
                    {
                        data: 'investment.name',
                        render: (data, type, row, meta) => {
                            if (row.link_to_investment && data) {
                                return `<a href="${row.link_to_investment}" target="_blank">${data}</a>`;
                            }
                            if (data) {
                                return data
                            }
                            return '-'

                        }
                    },
                    {
                        data: 'client.name',
                        render: (data, type, row, meta) => {
                            if (row.link_to_client && data) {
                                return `<a href="${row.link_to_client}" target="_blank">${data}</a>`;
                            }
                            if (data) {
                                return data
                            }
                            return '';
                        }
                    },
                    {
                        data: 'reserved_to',
                        render: (data, type, row, meta) => {
                            if (data) {
                                return new Date(data).toLocaleDateString();
                            }
                            return '-';
                        }
                    }
                ]
            });
            initializeFilters(table);
        }

        const initializeFilters = (table) => {
            const searchReservedProperties = document.getElementById('searchReservedProperties');
            searchReservedProperties.addEventListener('input', () => {
                table.search(searchReservedProperties.value).draw();
            });
        }
    </script>
@endpush
