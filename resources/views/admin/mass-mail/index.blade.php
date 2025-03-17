@extends('admin.layout')


@section('content')
    <div class="container-fluid">



        <div class="card">
            <div class="card-head container-fluid">
                <div class="row">
                    <div class="col-6 pl-0">
                        <h4 class="page-title d-flex"><i class="fe-inbox"></i>Newsletter</h4>
                    </div>
                    <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">

                    </div>
                </div>
            </div>
        </div>

        @include('admin.mass-mail.top-menu')
        @session('success')
            <div class="alert alert-success my-4">
                {{ session('success') }}
            </div>
        @endsession
        @include('components.errors')

        <div id="select-investments" class="card mt-4">
            <div class="card-body">
                @include('form-elements.searchable-select', [
                    'name' => 'investment',
                    'options' => $investments,
                    'selected' => null,
                    'placeholder' => 'Wybierz inwestycje',
                    'id' => 'select-investments-select',
                    'label' => 'Wybierz inwestycje',
                    'required' => true,
                ])

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
                            <label class="form-label small fw-semibold" for="searchUserInput">Wyszukaj</label>
                            <input type="text" class="form-control" placeholder="Wyszukaj" id="searchUserInput">
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-control border-0 p-0">
                            <label class="form-label small fw-semibold" for="select-clients-select">Pokaż</label>
                            <select class="form-control" id="select-clients-select">
                                <option value="all">Wszystkie</option>
                                <option value="selected">Zaznaczone</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form action="{{ route('admin.mass-mail.send') }}" method="POST" id='send-mail-form'>

            <div class="card overflow-hidden">
                @csrf
                <table class="table" style="table-layout: fixed;" id="clients-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">
                                <input class="form-check-input" type="checkbox" id="selectAll">
                            </th>
                            <th style="width: 200px">Imię</th>
                            <th style="width: 200px">Nazwisko</th>
                            <th style="width: 200px">Email</th>
                            <th style="width: 200px">Miasto</th>
                            <th style="width: 200px">Data rejestracji</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>

            <div id="select-action" class="card my-4">
                <div class="card-body" role="tablist">
                    <div class="row">

                        <div class="col-12 col-md-10 col-lg-8 col-xxl-6  offset-md-1 offset-lg-2 offset-xxl-3">
                            <ul class="list-unstyled row">
                                <li class='col-6'>
                                    <input class="d-none active action-radio-input" type="radio" name="action"
                                        id="radio_create_template" value='create_template' data-bs-toggle='tab' checked
                                        data-bs-target='#create_template_pane'>
                                    <label
                                        class='w-100 text-center border border-1 p-3 rounded h-100 d-flex align-items-center justify-content-center'
                                        for="radio_create_template">
                                        Wybierz szablon</label>

                                </li>

                                <li class='col-6'>
                                    <input class='d-none action-radio-input' type="radio" name="action"
                                        id="radio_text_template" value='text_template' data-bs-toggle='tab'
                                        data-bs-target='#text_template_pane'>
                                    <label
                                        class='w-100 text-center border border-1 p-3 rounded h-100 d-flex align-items-center justify-content-center'
                                        for="radio_text_template">Wyślij
                                        wiadomość tekstową</label>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-10 col-lg-8 col-xxl-6  offset-md-1 offset-lg-2 offset-xxl-3">
                            <div class="form-group border-0">
                                <label for="subject">Temat wiadomości<abbr class="text-danger">*</abbr></label>
                                <input required type="text" class="form-control" id="message-subject" name="subject">
                            </div>
                        </div>
                        <div class='col-12 col-md-10 col-lg-8 col-xxl-6  offset-md-1 offset-lg-2 offset-xxl-3'>

                            <div class="tab-content">
                                <div class="tab-pane fade active show" id='create_template_pane'>
                                    <div class="col-12">
                                        <div class="row">
           
                                            <div class="col-6">
                                                @include('form-elements.searchable-select', [
                                                    'name' => 'template',
                                                    'options' => $templates,
                                                    'selected' => null,
                                                    'placeholder' => 'Wybierz szablon',
                                                    'id' => 'select-template',
                                                ])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id='text_template_pane'>
                                    <div class="col-12">
                                        <div class="form-group border-0">
                                            <label for="content">Treść wiadomości</label>
                                            <textarea class="form-control" id="message-content" name="content" style="min-height: 200px;"
                                                placeholder="Treść wiadomości"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card my-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div>
                                <div class="form-check">
                                    <label for="schedule-send" class="form-label fs-6">Zaplanuj wysyłkę</label>
                                    <input type="checkbox" class="form-check-input" id="schedule-send"
                                        data-bs-toggle='collapse' data-bs-target='#schedule-send-options'>
                                </div>
                                <div class='collapse mb-4' id='schedule-send-options'>
                                    <div class="col-12 col-lg-6 col-xl-4">
                                        <div class="form-control border-0 p-0">
                                            <label for="schedule-send" class="form-label">Data wysyłki</label>
                                            <input class="form-control" type="datetime-local" name="schedule-send"
                                                id="schedule-send">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check">
                                    <label for="send-test-email" class="form-label fs-6">Wyślij testowy</label>
                                    <input type="checkbox" class="form-check-input" id="send-test-email"
                                        data-bs-toggle='collapse' data-bs-target='#send-test-email-options'>
                                </div>
                                <div class="collapse mb-4" id="send-test-email-options">
                                    <div class="col-12 col-lg-6 col-xl-4">
                                        <div class="form-control border-0 p-0">
                                            <label for="test-email" class="form-label">Email na który wyślemy testowy
                                                mail</label>
                                            <input class="form-control" type="email" id="test-email">
                                        </div>
                                        <button type="button" class="btn btn-primary mt-3"
                                            id="send-test-email-button">Wyślij testowy mail</button>
                                    </div>
                                </div>
                                <div class='text-end'>
                                    <button class="btn btn-primary" form="send-mail-form">Wyślij maila</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>

    @endsection
    @push('scripts')
        <script src="{{ asset('/js/datatables.min.js') }}" charset="utf-8"></script>
        <link href="{{ asset('/css/datatables.min.css') }}" rel="stylesheet">
        <script src="{{ asset('/js/editor/tinymce.min.js') }}" charset="utf-8"></script>

        <script type="module">
            import {
                NewsletterStateSaver,
                NewsletterStateCollector
            } from "{{ asset('/js/newsletter-state-saver.js') }}";
            document.addEventListener('DOMContentLoaded', () => {
                @session('success')
                // set flag if send successfully
                setShouldClearFlagInStorage();
                @endsession

                initializeEditor();
                initializeClientsTable();
                const sendMailForm = document.getElementById('send-mail-form');
                sendMailForm.addEventListener('submit', (e) => {
                    setShouldClearFlagInStorage();
                });
            });

            const setShouldClearFlagInStorage = (save = 1) => {
                localStorage.setItem('shouldClear', save);
            }

            const initializeClientsTable = () => {
                const searchUserInput = document.getElementById('searchUserInput');
                const table = $('#clients-table').DataTable({
                    "info": false,
                    dom: 'lrti',
                    paging: false,
                    data: @json($users),
                    language: {
                        "url": "{{ asset('/js/polish.json') }}"
                    },
                    columns: [{
                            data: 'id',
                            render: (data, type, row, meta) => {
                                return `<input class="form-check-input" type="checkbox" name="users[]" value="${data}">`;
                            },
                            orderable: false,
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: 'surname'
                        },
                        {
                            data: 'email'
                        },
                        {
                            data: 'city'
                        },
                        {
                            data: 'created_at',
                            render: (data, type, row, meta) => {
                                return new Date(data).toLocaleDateString();
                            }
                        }
                    ]
                })
                initializeFilters(table);
                initializeTestEmailSender();
                initializeValidation();
            }

            const initializeValidation = () => {
                const sendMailForm = document.getElementById('send-mail-form');
                sendMailForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const formData = new FormData(sendMailForm);
                    if (formData.get('action') === 'create_template') {
                        if (formData.get('template') === '') {
                            toastr.error('Podaj szablon wiadomości');
                            return;
                        }
                    }
                    if (formData.get('action') === 'upload_template') {
                        toastr.error('Wybierz utworzony szablon lub wyślij wiadomość tekstową');
                        return;
                    }
                    e.target.submit();
                });
            }

            const initializeTestEmailSender = () => {
                const sendTestEmailButton = document.getElementById('send-test-email-button');


                sendTestEmailButton.addEventListener('click', async function() {
                    const testEmailInput = document.getElementById('test-email');

                    const isSubmitting = true;
                    const action = document.querySelector('input[name="action"]:checked').value;
                    const subject = document.getElementById('message-subject').value;
                    const template = document.getElementById('select-template').value;
                    const content = tinymce.activeEditor.getContent({
                        format: 'html'
                    });

                    try {
                        this.disabled = true;
                        this.innerHTML = 'Wysyłanie...';

                        if (testEmailInput.value === '') {
                            toastr.error('Podaj email na który wyślemy testowy mail');
                            return;
                        }

                        if (subject === '') {
                            toastr.error('Podaj temat wiadomości');
                            return;
                        }
                        if (action === 'text_template') {
                            if (content === '') {
                                toastr.error('Podaj treść wiadomości');
                                return;
                            }
                        }
                        if (action === 'create_template') {
                            if (template === '') {
                                toastr.error('Podaj szablon wiadomości');
                                return;
                            }
                        }
                        if (action === 'upload_template') {
                            toastr.error('Wybierz utworzony szablon lub wyślij wiadomość tekstową');
                            return;
                        }

                        const response = await fetch('{{ route('admin.mass-mail.send-test') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                email: testEmailInput.value,
                                action: action,
                                subject: subject,
                                template: template,
                                content: content
                            }),
                        });

                        if (response.ok) {
                            toastr.success('Testowy mail został wysłany');
                        } else {
                            toastr.error('Wystąpił błąd podczas wysyłania testowego maila');
                        }
                    } catch (error) {
                        console.error(error);
                    } finally {
                        this.disabled = false;
                        this.innerHTML = 'Wyślij testowy mail';
                    }
                });
            }

            const initializeFilters = (table) => {
                // search
                const searchUserInput = document.getElementById('searchUserInput');
                searchUserInput.addEventListener('input', () => {
                    table.search(searchUserInput.value).draw();
                });

                // checkbox
                const selectAllCheckbox = document.getElementById('selectAll');
                selectAllCheckbox.addEventListener('change', () => {
                    const checkboxes = document.querySelectorAll('tbody tr input[type="checkbox"]');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                });

                // select
                const selectClientsSelect = document.getElementById('select-clients-select');
                selectClientsSelect.addEventListener('change', () => {
                    if (selectClientsSelect.value === 'selected') {
                        table.rows().every(function() {
                            const row = this.node();
                            if (row) {
                                const checkbox = row.querySelector('input[type="checkbox"]');
                                if (!checkbox.checked) {
                                    $(row).hide();
                                } else {
                                    $(row).show();
                                }
                            }
                        })
                    } else {
                        table.rows().every(function() {
                            $(this.node()).show();
                        });
                    }
                });
            }




            function logErrors(errors) {
                if (errors.length > 0) {
                    toastr.error('Wystąpiły błędy podczas przesyłania szablonu');

                    errors.forEach(error => {
                        toastr.error(error);
                    });
                }
            }

            const initializeEditor = () => {
                tinymce.init({
                    selector: "#message-content",
                    language: "pl",
                    plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste help wordcount emoticons',
                    toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | help',
                    menubar: 'file edit view insert format tools table help',
                    toolbar_mode: 'sliding',
                    contextmenu: 'link image table',
                    height: 300,
                    content_style: 'body { font-family: Arial, Helvetica, sans-serif; font-size: 14px; }',
                    paste_data_images: true,
                    convert_urls: false,
                    relative_urls: false,
                    remove_script_host: false,
                    branding: false,
                    setup: (editor) => {
                        editor.on('init', () => {

                            window.stateSaver = new NewsletterStateSaver(
                                new NewsletterStateCollector(
                                    tinymce), tinymce);

                        });
                    }
                });
            };
        </script>
    @endpush
    @push('scripts')
        <style>
            #clients-table_wrapper {
                overflow-y: scroll;
                height: min(80vh, 500px);
                overflow-x: hidden;
            }

            .action-radio-input+label {
                cursor: pointer;
            }

            .action-radio-input.active+label {
                color: var(--bs-white);
                background-color: #00acc1;
                border-color: currentcolor !important;
            }
        </style>
    @endpush
