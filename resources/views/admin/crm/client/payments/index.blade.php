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
            <div class="col-9">
                <div class="card mt-3">
                    <div class="card-head container-fluid">
                        <div class="row">
                            <div class="col-6 pl-0">
                                <h4 class="page-title">&nbsp;</h4>
                            </div>
                            <div class="col-6 d-flex justify-content-end align-items-center form-group-submit">
                                <button
                                        class="btn btn-primary"
                                        id="addProperty"
                                        data-bs-toggle="tooltip"
                                        data-placement="top"
                                        data-bs-title="Dodaj lokal">Dodaj lokal</button>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach($client->properties as $p)
                <div class="card mt-3">
                    <div class="card-body card-body-rem">
                        <div class="row">
                            <div class="col-3">
                                @if ($p->file)
                                    <a href="{{ asset('/investment/property/' . $p->file) }}" class="swipebox" target="_top">
                                        <picture>
                                            <source type="image/webp" srcset="{{ asset('/investment/property/thumbs/webp/' . $p->file_webp) }}">
                                            <source type="image/jpeg" srcset="{{ asset('/investment/property/thumbs/' . $p->file) }}">
                                            <img src="{{ asset('/investment/property/thumbs/' . $p->file) }}" alt="{{ $p->name }}">
                                        </picture>
                                    </a>
                                @else
                                    <img src="https://placehold.co/600x400?text=Brak+zdjęcia" alt="">
                                @endif
                            </div>
                            <div class="col-6">
                                <div class="status-badge mb-2 d-flex">
                                    <span class="badge room-list-status-{{ $p->status }}">{{ roomStatus($p->status) }}</span>
                                </div>
                                <h2><a href="{{ $p->getLinkToProperty($p) }}">{{ $p->name }}</a></h2>
                                <h4>{{ $p->investment->name }}</h4>
                                <hr>
                                <ul class="card-ul">
                                    <li>Powierzchnia: {{ $p->area }}m<sup>2</sup></li>
                                    <li>Ilość pokoi: {{ $p->rooms }}</li>
                                    <li>Piętro: {{ $p->floor->number }}</li>
                                    <li>Aneks kuchenny</li>
                                    <li>Data podpisania umowy: 03.07.2024</li>
                                    @if($p->bank_account)
                                    <li>Numer konta: <span class="badge" onclick="copyAccountNumber(this)" data-bs-toggle="tooltip" data-bs-title="Skopiuj numer konta">{{ $p->bank_account }}</span></li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-3 d-flex justify-content-center align-items-center text-center">
                                <div>
                                    @if($p->price)
                                        <h4>Kwota za całość:</h4>
                                        <h3>{{ number_format($p->price, 2, '.', ' ') }} zł</h3>
                                    @endif
                                    @if($p->payments->count() > 0)
                                        @if($p->late)
                                            <div class="alert alert-danger m-0" id="generatePaymentsInfo" role="alert">
                                                Istnieje zadłużenie
                                            </div>
                                        @endif
                                        @if($p->latestPayment)
                                        <h4>Kwota najbliższej płatności:</h4>
                                        <h3>{{ number_format($p->latestPayment->amount, 2, ',', ' ') }} zł</h3>
                                        <h4 class="mt-3">Najbliższy termin płatności:</h4>
                                        <h3>{{ \Illuminate\Support\Carbon::parse($p->latestPayment->due_date)->format('Y-m-d') }}</h3>
                                        @endif
                                    @endif
                                    <a href="{{ route('admin.crm.clients.payments.show', [$client, $p]) }}" class="btn btn-primary mt-3">Pokaż harmonogram</a>
                                </div>
                            </div>
                        </div>
                        @if($p->relatedProperties->count() > 0)
                        <div class="row mt-3 border-top">
                            <div class="col-2 d-flex align-items-center text-center">
                                <h4 class="m-0 pt-3">Powiązane lokale:</h4>
                            </div>
                            <div class="col-10">
                                <div class="row">
                                    @foreach($p->relatedProperties as $r)
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
                @endforeach
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
@endsection
@push('scripts')
    <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>
    <link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/bootstrap-select/bootstrap-select.min.js') }}" charset="utf-8"></script>
    <link href="{{ asset('/js/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
    <script>
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger : 'hover'
            })
        });
        const token = '{{ csrf_token() }}';

        function copyAccountNumber(element) {
            const tempInput = document.createElement("input");
            tempInput.value = element.textContent.trim();
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            toastr.options={closeButton:!0,progressBar:!0,positionClass:"toast-bottom-right",timeOut:"3000"};toastr.success("Numer konta skopiowany");
        }

        function attachAddPropertyButton() {
            const addButton = document.getElementById('addProperty');

            addButton.addEventListener('click', function(d) {
                const button = $(d.currentTarget);
                button.css('pointer-events', 'none');

                fetch('{{ route('admin.crm.clients.property.create', $client) }}', {
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
                        initModal('store');
                    })
                    .catch(error => {
                        console.error('There has been a problem with your fetch operation:', error);
                    })
                    .finally(() => {
                        button.css('pointer-events', 'auto');
                    });
            });
        }

        attachAddPropertyButton();

        function fetchInvestmentProperties() {
            const investmentId = document.getElementById('inputInvestment').value;
            const selectPropertyRow = document.querySelector('.dynamic-property-row');
            const selectPropertyElement = document.getElementById('inputProperty');
            const formStatusInput = document.getElementById('formStatusInput');
            const formSaleInput = document.getElementById('formSaleInput');
            const formReservationInput = document.getElementById('formReservationInput');

            if (investmentId !== '0') {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/developro/investment/' + investmentId + '/available-properties', true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        const properties = JSON.parse(xhr.responseText);

                        if (Object.keys(properties).length > 0) {
                            console.log('properties exist');

                            const propertyTypes = {
                                property: 1,
                                // storage: 2,
                                // parking: 3
                            };

                            Object.entries(propertyTypes).forEach(([type, value]) => {
                                const selectRow = document.querySelector(`.dynamic-${type}-row`);
                                const selectElement = document.getElementById(`input${type.charAt(0).toUpperCase() + type.slice(1)}`);

                                if (properties[value] && properties[value].length > 0) {
                                    console.log(`properties type:${type} exist`);

                                    selectRow.classList.remove('d-none');

                                    const firstOption = selectElement.options[0];
                                    selectElement.innerHTML = '';
                                    selectElement.appendChild(firstOption);

                                    properties[value].forEach(property => {
                                        const option = document.createElement('option');
                                        option.value = property.id;
                                        option.textContent = property.name;
                                        selectElement.appendChild(option);
                                    });
                                    $(selectElement).selectpicker();

                                    if (selectElement) {
                                        // Add an event listener to detect changes
                                        selectElement.addEventListener('change', (event) => {
                                            const selectedValue = event.target.value; // Get the selected value
                                            console.log(`Selected value for ${type}: ${selectedValue}`);

                                            resetDatepicker();

                                            // Add your custom logic here
                                            if (selectedValue && selectedValue > 0) {
                                                document.getElementById('statusSelect').value = '1';
                                                formStatusInput.classList.remove('d-none');
                                                formSaleInput.classList.add('d-none');
                                                formReservationInput.classList.add('d-none');
                                            } else {

                                                formStatusInput.classList.add('d-none');
                                                formSaleInput.classList.add('d-none');
                                                formReservationInput.classList.add('d-none');

                                                document.getElementById('statusSelect').value = '1';
                                            }
                                        });
                                    }
                                } else {
                                    selectRow.classList.add('d-none');
                                    selectElement.innerHTML = '';
                                    $(selectElement).selectpicker('destroy');
                                }
                            });
                        } else {
                            const elementsToReset = [selectPropertyRow];
                            const selectElementsToReset = [selectPropertyElement];

                            resetSelectElements(elementsToReset, selectElementsToReset);
                        }
                    }
                };
                xhr.send();
            } else {
                const elementsToReset = [selectPropertyRow];
                const selectElementsToReset = [selectPropertyElement];

                resetSelectElements(elementsToReset, selectElementsToReset);
            }
        }

        function resetSelectElements(selectRows, selectElements) {
            selectRows.forEach(selectRow => selectRow.classList.add('d-none'));
            selectElements.forEach(selectElement => {
                const firstOption = selectElement.options[0];
                selectElement.innerHTML = '';
                selectElement.appendChild(firstOption);
                $(selectElement).selectpicker('destroy');
            });
        }

        function resetDatepicker() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                language: "pl",
                autoclose: true
            });
            $('.datepicker').datepicker('update', '');
        }

        function initModal(){
            const modal = document.getElementById('propertyModal'),
                propertyModal = new bootstrap.Modal(modal),
                form = document.getElementById('modalForm'),
                modalSpiner = $('#modalSpiner'),
                inputPropertyId = document.getElementById('inputProperty'),
                statusSelect = document.getElementById('statusSelect'),
                formSaleInput = document.getElementById('formSaleInput'),
                formReservationInput = document.getElementById('formReservationInput')
                alert = $('.alert-danger');

            // Initial call to set the correct div visibility on page load
            toggleDivs();

            // Event listener for dropdown change
            statusSelect.addEventListener('change', toggleDivs);

            propertyModal.show();
            fetchInvestmentProperties();

            modal.addEventListener('hidden.bs.modal', function () {
                modal.remove();
            })

            function toggleDivs() {
                const selectedValue = statusSelect.value;

                formSaleInput.classList.add('d-none');
                formSaleInput.classList.remove('d-block');
                formReservationInput.classList.add('d-none');
                formReservationInput.classList.remove('d-block');

                resetDatepicker();

                if (selectedValue === '3') {
                    formSaleInput.classList.remove('d-none');
                    formSaleInput.classList.add('d-block');
                } else if (selectedValue === '2') {
                    formReservationInput.classList.remove('d-none');
                    formReservationInput.classList.add('d-block');
                }
            }

            form.addEventListener('submit', (e) => {
                e.preventDefault();

                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;

                const formData = new FormData(form);

                form.action = '{{ route('admin.crm.clients.property.store', $client) }}';
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        propertyModal.hide();
                        location.reload();
                    } else {
                        if (data.data) {
                            alert.html('');
                            $.each(data.data, function (key, value) {
                                alert.show();
                                alert.append('<span class="d-block">' + value + '</span>');
                            });
                        }

                        submitButton.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    submitButton.disabled = false;
                });
            });
        }
    </script>
@endpush
