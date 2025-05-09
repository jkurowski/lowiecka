@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title"><i class="fe-alert-triangle"></i>Zgłoszenia</h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                        <a href="#" class="btn btn-primary btn-add">Dodaj zgłoszenie</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-header card-nav">
            <nav class="nav">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-4">

                        </div>
                        <div class="col-8 d-flex justify-content-end">
                            <div class="row">
                                <div class="col">
                                    <label for="form_date_from" class="form-label">Data od</label>
                                    <input type="text" class="form-control" id="form_date_from" name="date_from">
                                </div>
                                <div class="col">
                                    <label for="form_date_to" class="form-label">Data do</label>
                                    <input type="text" class="form-control" id="form_date_to" name="date_to">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </nav>
        </div>

        <div class="card mt-3">
            <div class="table-overflow">
                <table class="table data-table w-100">
                    <thead class="thead-default">
                    <tr>
                        <th>#</th>
                        <th>Zgłaszający</th>
                        <th>Opiekun</th>
                        <th>Dział</th>
                        <th>Kategoria</th>
                        <th>Nazwa</th>
                        <th>Status</th>
                        <th>Inwestycja</th>
                        <th>Lokal</th>
                        <th class="text-center">Start&nbsp;&nbsp;<i class="fe-arrow-right"></i></th>
                        <th class="text-center">Termin&nbsp;&nbsp;<i class="fe-arrow-right"></i></th>
                        <th class="text-center">Koniec</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="content"></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group form-group-submit">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <a href="#" class="btn btn-primary btn-add">Dodaj zgłoszenie</a>
                </div>
            </div>
        </div>
    </div>
    <div id="modalHolder"></div>
    @routes('issue')
    @push('scripts')
        <script src="{{ asset('/js/datatables.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/bootstrap-select/bootstrap-select.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>

        <link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/datatables.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/js/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
        <script>
            $(function () {
                $.fn.dataTable.ext.errMode = 'none';
                $('.data-table').on( 'error.dt', function ( e, settings, techNote, message ) {
                    console.log( 'An error has been reported by DataTables: ', message );
                });
            });
            $(document).ready(function() {
                const t = $('.data-table').DataTable({
                    processing: true,
                    serverSide: false,
                    responsive: true,
                    dom: 'Brtip',
                    "buttons": [
                        {
                            extend: 'excelHtml5',
                            header: true,
                            exportOptions: {
                                modifier: {
                                    order: 'index',
                                    page: 'all',
                                    search: 'applied'
                                }
                            }
                        },
                        {
                            extend: 'csv',
                            header: true,
                            exportOptions: {
                                modifier: {
                                    order: 'index',
                                    page: 'all',
                                    search: 'applied'
                                }
                            }
                        },
                        {
                            extend: 'colvis',
                            columns: function (idx, title, th) {
                                return $(th).text().trim() !== '';
                            }
                        }
                    ],
                    language: {
                        "url": "{{ asset('/js/polish.json') }}"
                    },
                    iDisplayLength: 13,
                    ajax: {
                        url: "{{ route('admin.crm.issue.datatable') }}",
                        type: "GET",
                        data: function(d) {
                            d.minDate = $('#form_date_from').val();
                            d.maxDate = $('#form_date_to').val();
                        }
                    },
                    columns: [
                        /* 0 */ { data: null, defaultContent: '' },
                        /* 1 */ { data: 'contact', name: 'contact' },
                        /* 2 */ { data: 'user', name: 'user' },
                        /* 3 */ { data: 'department', name: 'department' },
                        /* 4 */ { data: 'type', name: 'type' },
                        /* 5 */ { data: 'title', name: 'title' },
                        /* 6 */ { data: 'status', name: 'status' },
                        /* 7 */ { data: 'investment', name: 'investment' },
                        /* 8 */ { data: 'property', name: 'property' },
                        /* 9 */ { data: 'start', name: 'start' },
                        /* 10 */ { data: 'deadline', name: 'deadline' },
                        /* 11 */ { data: 'end', name: 'end' },
                        /* 12 */ {data: 'actions', name: 'actions'}
                    ],
                    bSort: false,
                    columnDefs: [
                        { className: 'text-center', targets: [6,7,8,9,10,11] },
                        { className: 'select-column', targets: [1,2,3,4,6,7] },
                        { className: 'option-120 text-end', targets: [12] }
                    ],
                    initComplete: function () {
                        this.api().columns('.select-column').every(function () {
                            const column = this;
                            const select = $('<select class="selectpicker"><option value="">' + this.header().textContent + '</option></select>')
                                .appendTo($(column.header()).empty())
                                .on('change', function () {
                                    const val = $.fn.dataTable.util.escapeRegex($(this).val());
                                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                                });


                            column.data().unique().sort().each(function (value) {
                                if (value.indexOf("<") >= 0) {
                                    value = value.replace(/<[^>]+>/g, '');
                                }

                                if (value !== null && value.trim() !== '') {
                                    select.append('<option value="' + value + '">' + value + '</option>');
                                }
                            });

                            $('.selectpicker').selectpicker();
                        });

                        $('<button class="dt-button buttons-refresh">Odśwież tabelę</button>').appendTo('div.dt-buttons');

                        $(".btn-add").click((d) => {
                            d.preventDefault();

                            const button = $(d.currentTarget);
                            button.css('pointer-events', 'none');

                            const modalHolder = $('#modalHolder');
                            modalHolder.empty();

                            jQuery.ajax({
                                url: '{{ route('admin.crm.issue.create') }}',
                                success: function (response) {
                                    if (response) {
                                        modalHolder.append(response);
                                        initModal();
                                        const modal = document.getElementById('portletModal');
                                        modal.addEventListener('hidden.bs.modal', function () {
                                            t.ajax.reload(null, false);
                                        });
                                    } else {
                                        alert('Error');
                                    }
                                },
                                complete: function() {
                                    button.css('pointer-events', 'auto');
                                }
                            });
                        });

                        $(".buttons-refresh").click(function () {
                            t.ajax.reload();
                        });
                    },
                    createdRow: function(row, data, dataIndex) {
                        const currentDate = new Date();
                        const deadlineDate = new Date(data.deadline);

                        const diffInTime = deadlineDate - currentDate;
                        const diffInDays = Math.ceil(diffInTime / (1000 * 60 * 60 * 24));

                        console.log(diffInDays);

                        if (diffInDays <= 5 && diffInDays >= 0) {
                            $(row).addClass('near-deadline');
                        } else if (diffInDays < 0) {
                            $(row).addClass('past-deadline'); // Class for past deadline
                        }
                    },
                    "drawCallback": function () {
                        $(".confirmForm").click(function (d) {
                            d.preventDefault();
                            const c = $(this).closest("form");
                            const a = c.attr("action");
                            const b = $("meta[name='csrf-token']").attr("content");
                            $.confirm({
                                title: "Potwierdzenie usunięcia",
                                message: "Czy na pewno chcesz usunąć?",
                                buttons: {
                                    Tak: {
                                        "class": "btn btn-primary",
                                        action: function () {
                                            $.ajax({
                                                url: a,
                                                type: "DELETE",
                                                data: {
                                                    _token: b,
                                                }
                                            }).done(function () {
                                                t.row(c.parents('tr')).remove().draw();
                                            });
                                        }
                                    },
                                    Nie: {
                                        "class": "btn btn-secondary",
                                        action: function () {
                                        }
                                    }
                                }
                            })
                        });

                        $('.data-table').off('click', '.action-edit-button').on('click', '.action-edit-button', function (d) {

                            console.log('action-edit-button');

                            const button = $(d.currentTarget);
                            button.css('pointer-events', 'none');

                            d.preventDefault();
                            const dataId = $(this).data("bs-id");
                            const modalHolder = $('#modalHolder');
                            modalHolder.empty();

                            jQuery.ajax({
                                url: route('admin.crm.issue.edit', dataId),
                                type: 'GET',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if(response) {
                                        modalHolder.append(response);
                                        initModal();

                                        const modal = document.getElementById('portletModal');
                                        modal.addEventListener('hidden.bs.modal', function () {
                                            t.ajax.reload(null, false);
                                        })
                                    } else {
                                        alert('Error');
                                    }
                                },
                                complete: function() {
                                    button.css('pointer-events', 'auto');
                                }
                            });
                        });
                    },
                });

                t.on('init.dt', function () {
                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    });
                });

                t.on('order.dt search.dt', function () {
                    const count = t.page.info().recordsDisplay;
                    t.column(0, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function (cell, i) {
                        cell.innerHTML = count - i
                    });
                }).draw();
            });
        </script>
    @endpush
@endsection
