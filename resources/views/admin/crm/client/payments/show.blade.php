@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-calendar"></i><a href="{{route('admin.crm.clients.index')}}">Klienci</a><span
                                class="d-inline-flex me-2 ms-2">/</span><a
                                href="{{ route('admin.crm.clients.show', $client->id) }}">{{$client->name}}</a><span
                                class="d-inline-flex me-2 ms-2">/</span>Mieszkania</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit"></div>
            </div>
        </div>
        @include('admin.crm.client.client_shared.menu')
        <div class="row">
            <div class="col-3">
                @include('admin.crm.client.client_shared.aside')
            </div>
            <div class="col-9 pt-3 payments">
                @include('form-elements.back-route-button')
                <div class="card">
                    <div class="card-body card-body-rem">
                        <div class="row">
                            <div class="col-3">
                                @if ($property->file)
                                    <a href="{{ asset('/investment/property/' . $property->file) }}" class="swipebox" target="_top">
                                        <picture>
                                            <source type="image/webp"
                                                    srcset="{{ asset('/investment/property/thumbs/webp/' . $property->file_webp) }}">
                                            <source type="image/jpeg"
                                                    srcset="{{ asset('/investment/property/thumbs/' . $property->file) }}">
                                            <img src="{{ asset('/investment/property/thumbs/' . $property->file) }}"
                                                 alt="{{ $property->name }}">
                                        </picture>
                                    </a>
                                @else
                                    <img src="https://placehold.co/600x400?text=Brak+zdjęcia" alt="">
                                @endif
                            </div>
                            <div class="col-6">
                                <div class="status-badge mb-2 d-flex">
                                    <span class="badge room-list-status-{{ $property->status }}">{{ roomStatus($property->status) }}</span>
                                </div>
                                <h2><a href="{{ $property->getLinkToProperty($property) }}">{{ $property->name }}</a></h2>
                                <h4>{{ $property->investment->name }}</h4>
                                <hr>
                                <ul class="card-ul">
                                    <li>Powierzchnia: {{ $property->area }}m<sup>2</sup></li>
                                    <li>Ilość pokoi: {{ $property->rooms }}</li>
                                    <li>Piętro: {{ $property->floor->number }}</li>
                                    <li>Aneks kuchenny</li>
                                    <li>Data podpisania umowy: 03.07.2024</li>
                                    @if($property->bank_account)
                                        <li>Numer konta: <span class="badge" onclick="copyAccountNumber(this)" data-bs-toggle="tooltip" data-bs-title="Skopiuj numer konta">{{ $property->bank_account }}</span></li>
                                    @endif
                                </ul>

                                @if($property->status == 2)
                                    <p>To jeszcze nie działa.</p>
                                <a href="#" class="btn btn-danger mt-3">Usuń rezerwację</a>
                                <a href="#" class="btn btn-primary mt-3">Sprzedane</a>
                                @endif
                            </div>
                            <div class="col-3 d-flex justify-content-center align-items-center text-center">
                                <div>
                                    @if($property->price_brutto)
                                        <h4>Kwota za całość:</h4>
                                        <h3>{{ number_format($property->price_brutto, 2, '.', ' ') }} zł</h3>
                                    @else
                                        <div class="alert alert-warning m-0" role="alert">
                                            Brak wprowadzonej ceny
                                        </div>
                                        <a href="{{ $property_url }}" class="btn btn-primary mt-3 w-100">Wprowadź cenę</a>
                                        <a href="{{ route('admin.developro.investment.payments', $property->investment) }}" class="btn btn-primary mt-3 w-100">Przeprowadź symulację</a>
                                    @endif

                                    <div id="upcomingPayment">
                                        <h4>Kwota najbliższej płatności:</h4>
                                        <h3>-</h3>
                                    </div>

                                    <div id="upcomingDate">
                                        <h4 class="mt-3">Najbliższy termin płatności:</h4>
                                        <h3>-</h3>
                                    </div>

                                    @if($property->price_brutto)
                                        @if($property->investment->payments->count() > 0)
                                            <button class="btn btn-primary mt-3" id="generatePaymentsButton" style="display: none">Generuj harmonogram</button>
                                            <div class="alert alert-warning m-0" id="generatePaymentsInfo" style="display: none" role="alert">
                                                Aby wygenerować nowy harmonogram, usuń istniejące płatności.
                                            </div>
                                        @else
                                            Inwestycja nie posiada harmonogramu.
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($property->relatedProperties->count() > 0)
                            <div class="row mt-3 border-top">
                                <div class="col-2 d-flex align-items-center text-center">
                                    <h4 class="m-0 pt-3">Powiązane lokale:</h4>
                                </div>
                                <div class="col-10">
                                    <div class="row">
                                        @foreach($property->relatedProperties as $r)
                                            <div class="col-3 mt-3">
                                                <div class="border p-2">
                                                    <h4 class="m-0">
                                                        <span class="badge badge-sm room-list-status-{{ $r->status }}">{{ roomStatus($r->status) }}</span>
                                                        <a href="{{ $r->getLinkToProperty($r) }}" class="d-block mt-2"> {{ $r->name }}</a>
                                                    </h4>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <table class="table data-table mb-0 w-100">
                    <thead class="thead-default">
                    <tr>
                        <th>Termin płatności</th>
                        <th class="text-center">Wartość procentowa</th>
                        <th class="text-center">Kwota</th>
                        <th class="text-center">Data edycji</th>
                        <th class="text-center">Status płatności</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody class="content" id="tableContent">
                    @if($latestPayment)
                        <tr class="tr">
                            <td class="td" colspan="6">
                                <div class="d-flex justify-content-center p-4">
                                    <div class="spinner-border text-info" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="editModal"></div>

    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Potwierdzenie usunięcia</h5>
                </div>
                <div class="modal-body">
                    Czy na pewno chcesz usunąć?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nie</button>
                    <button type="button" class="btn btn-primary" id="confirmDeleteButton">Tak</button>
                </div>
            </div>
        </div>
    </div>
    @routes('payments')
    @push('scripts')
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>
        <link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
        <script>
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger : 'hover'
                })
            });

            const token = '{{ csrf_token() }}';
            const upcomingDate = document.querySelector('#upcomingDate h3');
            const upcomingPayment = document.querySelector('#upcomingPayment h3');
            const generatePaymentsButton = document.getElementById('generatePaymentsButton');
            const generatePaymentsInfo = document.getElementById('generatePaymentsInfo');

            @if($property->price_brutto)
            document.addEventListener('DOMContentLoaded', function() {
                loadPaymentsTable();
            });
            @endif

            function copyAccountNumber(element) {
                const tempInput = document.createElement("input");
                tempInput.value = element.textContent.trim();
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand("copy");
                document.body.removeChild(tempInput);
                toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-right",timeOut:"3000"};toastr.success("Numer konta skopiowany");
            }

            function loadPaymentsTable(checkList = false) {
                fetch('{{ route('admin.crm.clients.payments.generate-table', [$client, $property]) }}', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        document.getElementById('tableContent').innerHTML = data.html;
                        upcomingDate.textContent = data.data.latestPayment;
                        upcomingPayment.textContent = data.data.latestAmount;

                        if (data.propertyPayments > 0) {
                            if (generatePaymentsButton) {
                                generatePaymentsButton.style.display = 'none';
                                generatePaymentsInfo.style.display = 'block';
                            }
                        } else {
                            if (generatePaymentsButton) {
                                generatePaymentsButton.style.display = 'block';
                                generatePaymentsInfo.style.display = 'none';
                            }
                        }

                        attachDeleteButtonHandlers();
                        attachEditButtonHandlers();
                        attachAddPaymentButton();
                    })
                    .catch(error => {
                        console.error('There has been a problem with your fetch operation:', error);
                    });
            }

            function attachDeleteButtonHandlers() {
                const deleteButtons = document.querySelectorAll('.confirm-delete-button');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();

                        const paymentId = this.getAttribute('data-id');

                        // Show confirmation modal
                        $('#confirmDeleteModal').modal('show');

                        // Define confirmHandler function
                        const confirmHandler = () => {
                            // Close modal
                            $('#confirmDeleteModal').modal('hide');

                            // Perform delete action via AJAX
                            fetch('{{ route('admin.crm.clients.payments.destroy', '') }}/' + paymentId, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': token
                                }
                            })
                                .then(response => {
                                    if (!response.ok) {
                                        throw new Error('Network response was not ok');
                                    }
                                    return response.json();
                                })
                                .then(data => {
                                    if (data.success) {
                                        document.getElementById('recordsArray_' + paymentId).remove();
                                        loadPaymentsTable(true);
                                    } else {
                                        alert('An error occurred.');
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        };

                        // Attach confirmHandler to confirm button click event
                        document.getElementById('confirmDeleteButton').addEventListener('click', confirmHandler);

                        // Clear event listener when modal is dismissed
                        $('#confirmDeleteModal').on('hidden.bs.modal', function () {
                            document.getElementById('confirmDeleteButton').removeEventListener('click', confirmHandler);
                        });
                    });
                });
            }

            function attachEditButtonHandlers() {
                const editButtons = document.querySelectorAll('.edit-button');

                editButtons.forEach(button => {
                    button.addEventListener('click', function(d) {
                        const paymentId = this.getAttribute('data-id');

                        const button = $(d.currentTarget);
                        button.css('pointer-events', 'none');

                        // Assuming you want to load the edit modal via AJAX
                        fetch('{{ route('admin.crm.clients.payments.edit', '') }}/' + paymentId, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }
                            return response.text();
                        })
                        .then(html => {
                            // Assuming you have a modal container with id 'editModal' where you'll load the edit form
                            document.getElementById('editModal').innerHTML = html;

                            initPaymentModal();
                        })
                        .catch(error => {
                            console.error('There has been a problem with your fetch operation:', error);
                        })
                        .finally(() => {
                            button.css('pointer-events', 'auto');
                        });
                    });
                });
            }

            function attachAddPaymentButton() {
                const addButtons = document.getElementById('addPayment');

                addButtons.addEventListener('click', function(d) {

                    const button = $(d.currentTarget);
                    button.css('pointer-events', 'none');

                    fetch('{{ route('admin.crm.clients.payments.create', '') }}/{{ $property->id }}', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        }
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok ' + response.statusText);
                            }
                            return response.text();
                        })
                        .then(html => {
                            document.getElementById('editModal').innerHTML = html;
                            initPaymentModal('store');
                        })
                        .catch(error => {
                            console.error('There has been a problem with your fetch operation:', error);
                        })
                        .finally(() => {
                            button.css('pointer-events', 'auto');
                        });
                });
            }

            function initPaymentModal(action = 'update'){
                const modal = document.getElementById('paymentEditModal'),
                    paymentEditModal = new bootstrap.Modal(modal),
                    form = document.getElementById('modalForm'),
                    inputDate = $('#inputDate'),
                    inputPercent = $('#inputPercent'),
                    inputAmount = $('#inputAmount'),
                    inputStatus = $('#inputStatus'),
                    inputPaymentId = $('#inputPaymentId'),
                    inputPropertyId = $('#inputPropertyId'),
                    calcPercent = $('#calc-percent'),
                    calcAmount = $('#calc-amount'),
                    inputPaid = $('#inputPaid'),
                    modalSpiner = $('#modalSpiner');

                paymentEditModal.show();

                modal.addEventListener('shown.bs.modal', function () {
                    $('.date-picker').datepicker({
                        format: 'yyyy-mm-dd',
                        todayHighlight: true,
                        language: "pl"
                    });

                    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl, {
                            trigger : 'hover'
                        })
                    });
                })

                calcPercent.on('click', function(d) {

                    const button = $(d.currentTarget);
                    button.css('pointer-events', 'none');

                    $.ajax({
                        url: '/admin/crm/clients/payments/calc-percent',
                        type: 'POST',
                        data: {
                            '_token': token,
                            'percent': inputPercent.val(),
                            'property_id': inputPropertyId.val()
                        },
                        beforeSend: function() {
                            modalSpiner.toggleClass('d-none d-block');
                        },
                        success: function(response) {
                            if (response.percent_value !== undefined) {
                                console.log(response.percent_value);

                                inputAmount.val(response.percent_value);
                            } else {
                                console.error("Percent value not found in response");
                            }
                        },
                        error: function(result) {
                            if (result.responseJSON && result.responseJSON.errors) {
                                alert.html('');
                                $.each(result.responseJSON.errors, function (key, value) {
                                    alert.show();
                                    alert.append('<span>' + value + '</span>');
                                });
                            } else {
                                alert.html('An error occurred. Please try again later.');
                                alert.show();
                            }
                        },
                        complete: function() {
                            modalSpiner.toggleClass('d-none d-block');
                            button.css('pointer-events', 'auto');
                        }
                    });
                });

                calcAmount.on('click', function(d) {

                    const button = $(d.currentTarget);
                    button.css('pointer-events', 'none');

                    $.ajax({
                        url: '/admin/crm/clients/payments/calc-amount',
                        type: 'POST',
                        data: {
                            '_token': token,
                            'amount': inputAmount.val(),
                            'property_id': inputPropertyId.val()
                        },
                        beforeSend: function() {
                            modalSpiner.toggleClass('d-none d-block');
                        },
                        success: function(response) {
                            if (response.percent_value !== undefined) {
                                console.log(response.percent_value);

                                inputPercent.val(response.percent_value);
                            } else {
                                console.error("Percent value not found in response");
                            }
                        },
                        error: function(result) {
                            if (result.responseJSON && result.responseJSON.errors) {
                                alert.html('');
                                $.each(result.responseJSON.errors, function (key, value) {
                                    alert.show();
                                    alert.append('<span>' + value + '</span>');
                                });
                            } else {
                                alert.html('An error occurred. Please try again later.');
                                alert.show();
                            }
                        },
                        complete: function() {
                            modalSpiner.toggleClass('d-none d-block');
                            button.css('pointer-events', 'auto');
                        }
                    });
                });

                modal.addEventListener('hidden.bs.modal', function () {
                    $('#paymentEditModal').remove();
                })

                const alert = $('.alert-danger');

                const url = action === 'update' ? route('admin.crm.clients.payments.' + action, { payment: inputPaymentId.val() }) : route('admin.crm.clients.payments.' + action, { property: inputPropertyId.val() });
                const method = action === 'update' ? 'PUT' : 'POST';

                form.addEventListener('submit', (e) => {
                    e.preventDefault();

                    const submitButton = form.querySelector('button[type="submit"]');
                    submitButton.disabled = true;

                    jQuery.ajax({
                        url: url,
                        method: method,
                        data: {
                            '_token': token,
                            'percent': inputPercent.val(),
                            'amount': inputAmount.val(),
                            'due_date': inputDate.val(),
                            'status': inputStatus.val(),
                            'paid_at': inputPaid.val(),
                            'property_id': inputPropertyId.val()
                        },
                        success: function () {
                            paymentEditModal.hide();
                            toastr.options =
                                {
                                    "closeButton": true,
                                    "progressBar": true
                                }
                            toastr.success("Wpis został zaktualizowany");
                            document.getElementById('tableContent').innerHTML = '';
                            loadPaymentsTable();
                        },
                        error: function (result) {
                            if (result.responseJSON.data) {
                                alert.html('');
                                $.each(result.responseJSON.data, function (key, value) {
                                    alert.show();
                                    alert.append('<span>' + value + '</span>');
                                });
                            }
                        },
                        complete: () => {
                            submitButton.disabled = false;
                        }

                    });
                });
            }

            @if($property->investment->payments->count() > 0 && $property->price_brutto)
            document.getElementById('generatePaymentsButton').addEventListener('click', function() {
                fetch('{{ route('admin.crm.clients.payments.generate', [$client, $property]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                }).then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadPaymentsTable();
                        } else {
                            alert('Failed to generate payments.');
                        }
                    });
            });
            @endif
        </script>
    @endpush
@endsection
