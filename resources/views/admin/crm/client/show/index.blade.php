@extends('admin.layout')

@section('content')

    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-users"></i><a
                            href="{{ route('admin.crm.clients.index') }}">Klienci</a><span
                            class="d-inline-flex me-2 ms-2">/</span>{{ $client->name }}</h4>
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
                    <form method="POST" action="{{ route('admin.crm.clients.update', $client) }}"
                        class="card-body control-col12">
                        @method('PUT')
                        @csrf
                        <div class="row w-100 mb-4">
                            <div class="col-3">
                                @include('form-elements.html-select', [
                                    'label' => 'Status',
                                    'name' => 'active',
                                    'selected' => '',
                                    'select' => ['1' => 'Aktywny', '2' => 'Nieaktywny'],
                                ])
                            </div>
                            <div class="col-3">
                                @include('form-elements.html-select', [
                                    'label' => 'Opiekun',
                                    'name' => 'user_id',
                                    'selected' => $client->user_id,
                                    'select' => $users,
                                ])
                            </div>
                        </div>

                        <div class="row w-100 mb-4">
                            <div class="col-4">
                                @include('form-elements.html-input-text', [
                                    'label' => 'Imię',
                                    'name' => 'name',
                                    'value' => $client->name,
                                    'required' => 1,
                                ])
                            </div>
                            <div class="col-4">
                                @include('form-elements.html-input-text', [
                                    'label' => 'Nazwisko',
                                    'name' => 'lastname',
                                    'value' => $client->lastname,
                                    'required' => 1,
                                ])
                            </div>
                            <div class="col-4">
                                @include('form-elements.html-select', [
                                    'label' => 'Stan cywilny',
                                    'name' => 'martial_status',
                                    'selected' => $client->martial_status,
                                    'select' => [
                                        '' => 'Nieokreślony',
                                        'single' => 'Wolny/Wolna',
                                        'married' => 'Zamężny/Zamężna',
                                        'divorced' => 'Rozwiedziony/Rozwiedziona',
                                        'widowed' => 'Wdowa/Wdowiec',
                                    ],
                                ])
                                {{-- <select name="martial_status" id="martial_status" class="form-select">
                                    <option value="single">Nieokreślony</option>
                                    <option value="single">Wolny/Wolna</option>
                                    <option value="married">Zamężny/Zamężna</option>
                                    <option value="divorced">Rozwiedziony/Rozwiedziona</option>
                                    <option value="widowed">Wdowa/Wdowiec</option>
                                </select> --}}
                            </div>
                        </div>

                        <div class="row w-100 mb-4">
                            <div class="col-6">
                                @include('form-elements.html-input-text', [
                                    'label' => 'Adres e-mail',
                                    'name' => 'mail',
                                    'value' => $client->mail,
                                    'required' => 1,
                                    'readonly' => 1,
                                ])
                            </div>
                            <div class="col-6">
                                @include('form-elements.html-input-text', [
                                    'label' => 'Adres e-mail 2',
                                    'name' => 'mail2',
                                    'value' => $client->mail2,
                                ])
                            </div>
                        </div>

                        <div class="row w-100 mb-4">
                            <div class="col-6">
                                @include('form-elements.html-input-text', [
                                    'label' => 'Telefon',
                                    'name' => 'phone',
                                    'value' => $client->phone,
                                    'required' => 1,
                                ])
                            </div>
                            <div class="col-6">
                                @include('form-elements.html-input-text', [
                                    'label' => 'Telefon 2',
                                    'name' => 'phone2',
                                    'value' => $client->phone2,
                                ])
                            </div>
                        </div>
                        <div class="row gy-3 w-100 mb-4">

                            <div class="col-4">
                                @include('form-elements.html-input-text', [
                                    'label' => 'Ulica',
                                    'name' => 'street',
                                    'value' => $client->street ?? '',
                                ])
                            </div>
                            <div class="col-4">
                                @include('form-elements.html-input-text', [
                                    'label' => 'Numer domu',
                                    'name' => 'house_number',
                                    'value' => $client->house_number ?? '',
                                ])
                            </div>
                            <div class="col-4">
                                @include('form-elements.html-input-text', [
                                    'label' => 'Numer mieszkania',
                                    'name' => 'apartment_number',
                                    'value' => $client->apartment_number ?? '',
                                ])
                            </div>
                            <div class="col-6">
                                @include('form-elements.html-input-text', [
                                    'label' => 'Kod pocztowy',
                                    'name' => 'post_code',
                                    'value' => $client->post_code ?? '',
                                ])
                            </div>
                            <div class="col-6">
                                @include('form-elements.html-input-text', [
                                    'label' => 'Miejscowość',
                                    'name' => 'city',
                                    'value' => $client->city ?? '',
                                ])
                            </div>

                        </div>
                        <div class="row w-100 mb-4">
                            <div class="col-6">
                                @include('form-elements.html-input-text', [
                                    'label' => 'PESEL (tylko cyfry)',
                                    'name' => 'pesel',
                                    'value' => $client->pesel ?? '',
                                ])
                            </div>
                            <div class="col-6">
                                <p class="col-form-label">Dokument tożsamości</p>
                                <div class="form-check form-check-inline fs-6">
                                    <input type="radio" name="id_type" id="id_type_dowod_osobisty"
                                        class="form-check-input" value="dowod_osobisty" checked>
                                    <label for="id_type_dowod_osobisty" class="form-check-label">Dowód osobisty</label>
                                </div>
                                <div class="form-check form-check-inline fs-6">
                                    <input type="radio" name="id_type" id="id_type_paszport" class="form-check-input"
                                        value="paszport">
                                    <label for="id_type_paszport" class="form-check-label">Paszport</label>
                                </div>
                            </div>
                            <div class="col-4 mt-3">
                                @include('form-elements.html-input-text', [
                                    'label' => 'Numer dowodu/paszportu',
                                    'name' => 'id_number',
                                    'value' => $client->id_number ?? '',
                                ])
                            </div>
                            <div class="col-4 mt-3">
                                @include('form-elements.html-input-text', [
                                    'label' => 'Wydany przez',
                                    'name' => 'id_issued_by',
                                    'value' => $client->id_issued_by ?? '',
                                ])
                            </div>
                            <div class="col-4 mt-3">
                                <div class="form-control border-0 p-0">
                                    <label for="id_issued_date" class="col-form-label">Data wydania</label>
                                    <input type="date" name="id_issued_date" id="id_issued_date" class="form-control"
                                        value="{{ $client->id_issued_date }}">
                                </div>

                            </div>
                        </div>
                        <div class="row w-100">
                            <div class="col-12">
                                <div class="form-check fs-6">
                                    <input type="checkbox" name="is_company" id="is_company" class="form-check-input"
                                        data-bs-toggle="collapse" data-bs-target="#collapseCompanyFields"
                                        {{ $client->is_company === 'on' || $client->is_company === 1 ? 'checked' : '' }}>
                                    <label for="is_company" class="form-check-label">Firma</label>
                                </div>
                            </div>
                        </div>
                        <div data-company-fields class="company-fields w-100 mt-4">
                            <div id="collapseCompanyFields"
                                class="collapse {{ $client->is_company === 'on' || $client->is_company === 1 ? 'show' : '' }}">

                                <div class="row w-100 mb-4">
                                    <div class="col-6">
                                        @include('form-elements.html-input-text', [
                                            'label' => 'Nip',
                                            'name' => 'nip',
                                            'value' => $client->nip ?? '',
                                            'required' => false,
                                        ])
                                        @if (true)
                                            <button type="button" id="get_data_from_gus" class="btn link-secondary p-0"
                                                style="--bs-btn-font-size: 0.8rem;">
                                                <i class="fe-download"></i>
                                                <span>
                                                    Pobierz dane z GUS
                                                </span>
                                            </button>
                                        @endif
        
                                    </div>
                                    <div class="col-6">
                                        @include('form-elements.html-input-text', [
                                            'label' => 'Reprezentant',
                                            'name' => 'exponent',
                                            'value' => $client->exponent ?? '',
                                        ])
                                    </div>
                                </div>
                                <div class="row w-100">
                                    <div class="col-6">
                                        @include('form-elements.html-input-text', [
                                            'label' => 'Nazwa firmy',
                                            'name' => 'company_name',
                                            'value' => $client->company_name ?? '',
                                        ])
                                    </div>
                                    <div class="col-6">
                                        @include('form-elements.html-input-text', [
                                            'label' => 'Regon (tylko cyfry)',
                                            'name' => 'regon',
                                            'value' => $client->regon ?? '',
                                        ])
                                    </div>
                                </div>
                                <div class="row w-100">
                                    <div class="col-6">
                                        @include('form-elements.html-input-text', [
                                            'label' => 'KRS',
                                            'name' => 'krs',
                                            'value' => $client->krs ?? '',
                                        ])
                                    </div>
                                    <div class="col-6">
                                        @include('form-elements.html-input-text', [
                                            'label' => 'Adres siedziby',
                                            'name' => 'address',
                                            'value' => $client->address ?? '',
                                        ])
                                    </div>
                                </div>

                            </div>
                            <div class="row w-100">
                                <div class="col-12">
                                    <div class="section">CRM</div>
                                </div>
                            </div>
                            <div class="row w-100 mb-4">
                                <div class="col-6">
                                    @include('form-elements.html-select', [
                                        'label' => 'Źródło kontaktu',
                                        'name' => 'source',
                                        'selected' => $client->source,
                                        'select' => \App\Helpers\ContactOrigins::getStatusesForSelect(),
                                    ])
                                </div>
                                <div class="col-6">
                                    @include('form-elements.html-input-text', [
                                        'label' => 'Dodatkowa informacja o źródle',
                                        'name' => 'source_additional',
                                        'value' => $client->source_additional,
                                    ])
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-end">
                                    <input name="submit" id="submit" value="Zapisz" class="btn btn-primary" type="submit">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
 
    @push('scripts')
        <script type="module">
            import PeselValidator from '{{ asset('js/PeselValidator.js') }}';
            import RegonValidator from '{{ asset('js/RegonValidator.js') }}';
            import NipValidator from '{{ asset('js/NipValidator.js') }}';
            window.investment_options = @json($investment_options);
            window.status_options = @json(\App\Helpers\ClientSalesStatuses::getAllStatuses());
            const initializeValidators = () => {
                window.PeselValidator = new PeselValidator();
                window.RegonValidator = new RegonValidator();
                window.NipValidator = new NipValidator();
            };

            const initializeTooltips = () => {
                const tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.forEach(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            };

            const showSuccessToast = (message) => {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: "toast-bottom-right",
                    timeOut: "3000"
                };
                toastr.success(message);
            };

            const handleValidation = (input, validator, errorMessage) => {
                input.addEventListener('blur', () => {
                    if (!validator.validate(input.value)) {
                        input.classList.add('is-invalid');
                        input.parentNode.classList.add('flex-wrap');
                        if (!input.parentNode.querySelector('.invalid-feedback')) {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback';
                            errorDiv.textContent = errorMessage;
                            input.parentNode.appendChild(errorDiv);
                        }
                    } else {
                        input.classList.remove('is-invalid');
                        const errorDiv = input.parentNode.querySelector('.invalid-feedback');
                        if (errorDiv) errorDiv.remove();
                    }
                });
            };

            const getGusData = (selector) => {

                const button = document.querySelector(selector);
                if (!button) return;
                button.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const nip = document.getElementById('nip').value;
                    if (!nip) return;

                    try {
                        const response = await fetch('{{ route('admin.nip.index') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                nip: nip
                            })
                        })
                        if (response.ok) {
                            const data = await response.json();
                            if (data.status === 'success') {
                                toastr.success(data.message || 'Dane zostały pobrane pomyślnie');
                                fillFormFields(data.data[0]);
                            }
                        } else {
                            const data = await response.json();
                            toastr.error(data.message);
                        }

                    } catch (error) {
                        console.error(error);


                    }
                });
            };

            function fillFormFields(data) {
                const fieldsMapping = {
                    'regon': 'regon',
                    'nip': 'nip',
                    'name': 'company_name',
                    'province': 'province',
                    'district': 'district',
                    'community': 'community',
                    'city': 'city',
                    'propertyNumber': 'house_number',
                    'apartmentNumber': 'apartment_number',
                    'zipCode': 'post_code',
                    'street': 'street',
                    'postCity': 'post_city'
                };

                Object.keys(fieldsMapping).forEach(key => {
                    const field = document.querySelector(`[name="${fieldsMapping[key]}"]`);
                    if (field) {
                        field.value = data[key] || '';
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                initializeValidators();
                initializeTooltips();
                getGusData('#get_data_from_gus');

                @if (session('success'))
                    showSuccessToast("{{ session('success') }}");
                @endif

                document.getElementById("mail").readOnly = true;

                const fieldGroupBuilder = new FieldGroupBuilder('[data-fields-groups]');
                window.clientFields = @json($client->dealsFields);
                window.clientFields.forEach(field => fieldGroupBuilder.addFieldGroup(field));

                document.querySelector('[data-new-fields-group]').addEventListener('click', () => {
                    fieldGroupBuilder.addFieldGroup();
                });

                handleValidation(document.getElementById('regon'), new RegonValidator(), 'Nieprawidłowy numer REGON');
                handleValidation(document.getElementById('nip'), new NipValidator(), 'Nieprawidłowy numer NIP');
                handleValidation(document.getElementById('pesel'), new PeselValidator(), 'Nieprawidłowy numer PESEL');


            });

            class FieldGroupBuilder {
                constructor(containerSelector) {
                    this.container = document.querySelector(containerSelector);
                }

                addFieldGroup(fieldData = {}) {
                    const newFieldGroup = this.createFieldGroup(fieldData);
                    this.container.appendChild(newFieldGroup);
                }

                createFieldGroup(fieldData) {
                    const fieldGroup = document.createElement('div');
                    fieldGroup.classList.add('d-flex', 'mb-4', 'border-bottom', 'border-secondary');
                    fieldGroup.setAttribute('data-fields-group', this.getNewGroupId());

                    fieldGroup.innerHTML = this.buildFieldGroupContent(fieldData);
                    this.addEventListeners(fieldGroup);

                    // If we have fieldData, investment_id, and correct status, trigger both change events
                    if (fieldData?.investment_id) {
                        const statusSelect = fieldGroup.querySelector('[name*="[status]"]');
                        const investmentSelect = fieldGroup.querySelector('[data-investment-select]');
                        
                        // Trigger status change first
                        if (fieldData.status) {
                            statusSelect.value = fieldData.status;
                            statusSelect.dispatchEvent(new Event('change'));
                        }
                        
                        // Then trigger investment change
                        investmentSelect.dispatchEvent(new Event('change'));
                    }

                    return fieldGroup;
                }

                buildFieldGroupContent(fieldData) {
                    return `
                        <div class="flex-fill">
                            ${this.createRow([
                                this.createSelectField('Status sprzedaży', 'statusSelect', `fields[${this.getNewGroupId()}][status]`, this.getStatusOptions(), fieldData.status ?? '', 'col-6'),
                                this.createInputField('Dodatkowa informacja o statusie sprzedaży', `fields[${this.getNewGroupId()}][deal_additional]`, fieldData.deal_additional ?? '')
                            ])}
                            ${this.createRow([
                                this.createSelectField('Inwestycja', 'investment_idSelect', `fields[${this.getNewGroupId()}][investment_id]`, this.getInvestmentOptions(), fieldData.investment_id ?? '', 'col-4', true),
                                this.createPropertySelect('Nieruchomość', `fields[${this.getNewGroupId()}][property_id]`, fieldData.property_id ?? '', 'col-4 property-select-container d-none'),
                                this.createStorageSelect('Komórka', `fields[${this.getNewGroupId()}][storage_id]`, fieldData.storage_id ?? '', 'col-4 storage-select-container d-none'),
                                this.createParkingSelect('Parking', `fields[${this.getNewGroupId()}][parking_id]`, fieldData.parking_id ?? '', 'col-4 parking-select-container d-none')
                            ])}
                            ${this.createRow([
                                this.createInputField('Pokoje', `fields[${this.getNewGroupId()}][room]`, fieldData.room ?? '', 'col-4'),
                                this.createInputField('Metraż', `fields[${this.getNewGroupId()}][area]`, fieldData.area ?? '', 'col-4'),
                                this.createInputField('Budżet', `fields[${this.getNewGroupId()}][budget]`, fieldData.budget ?? '', 'col-4')
                            ])}
                            ${this.createRow([
                                this.createSelectField('Przeznaczenie', 'destinationSelect', `fields[${this.getNewGroupId()}][purpose]`, this.getPurposeOptions(), fieldData.purpose ?? '', 'col-6')
                            ])}
                        </div>
                        ${this.createRemoveButton()}
                    `;
                }

                createRow(fields) {
                    return `<div class="row w-100 mb-4">${fields.join('')}</div>`;
                }

                createSelectField(label, id, name, options, selectedValue = null, classNames = 'col-6', isInvestment = false) {
                    const optionsHtml = options.map(opt =>
                        `<option value="${opt.value}" ${opt.value === selectedValue ? 'selected' : ''}>${opt.text}</option>`
                    ).join('');
                    
                    const selectAttributes = isInvestment ? 'data-investment-select' : '';
                    
                    return `
                        <div class="${classNames}">
                            ${this.createLabel(label, id)}
                            <div class="col-12 control-input position-relative d-flex align-items-center flex-column">
                                <select class="form-select" id="${id}" name="${name}" ${selectAttributes}>${optionsHtml}</select>
                            </div>
                        </div>
                    `;
                }

                createInputField(label, id, value = '', classNames = 'col-6') {
                    return `
                        <div class="${classNames}">
                            ${this.createLabel(label, id)}
                            <div class="col-12 control-input position-relative d-flex align-items-center">
                                <input class="form-control" name="${id}" type="text" id="${id}" value="${value}">
                            </div>
                        </div>
                    `;
                }

                createLabel(text, id) {
                    return `
                        <label for="${id}" class="col-12 col-form-label control-label pb-2">
                            <div class="text-start">${text}</div>
                        </label>
                    `;
                }

                createRemoveButton() {
                    return `
                        <div class="d-flex align-items-center">
                            <button type="button" class="btn btn-danger remove-field-group">
                                <i class="fe-trash-2"></i>
                            </button>
                        </div>
                    `;
                }

                addEventListeners(fieldGroup) {
                    this.addRemoveEventListener(fieldGroup);
                    this.addStatusChangeListener(fieldGroup);
                    this.addInvestmentChangeListener(fieldGroup);
                }

                addStatusChangeListener(fieldGroup) {
                    const statusSelect = fieldGroup.querySelector('[name*="[status]"]');
                    const propertyContainer = fieldGroup.querySelector('.property-select-container');
                    const investmentSelect = fieldGroup.querySelector('[data-investment-select]');

                    statusSelect.addEventListener('change', (e) => {
                        const showPropertySelect = ['6', '11'].includes(e.target.value);
                        
                        if (showPropertySelect) {
                            // If investment is already selected, show property select
                            if (investmentSelect.value) {
                                propertyContainer.classList.remove('d-none');
                            }
                        } else {
                            propertyContainer.classList.add('d-none');
                            // Clear property selection when hiding
                            const propertySelect = propertyContainer.querySelector('select');
                            if (propertySelect) {
                                propertySelect.value = '';
                            }
                        }

                        // Trigger investment change to refresh property select visibility
                        investmentSelect.dispatchEvent(new Event('change'));
                    });
                }

                addInvestmentChangeListener(fieldGroup) {
                    const investmentSelect = fieldGroup.querySelector('[data-investment-select]');
                    if (!investmentSelect) return;

                    investmentSelect.addEventListener('change', async (e) => {
                        const investmentId = e.target.value;
                        const statusSelect = fieldGroup.querySelector('[name*="[status]"]');
                        const propertyContainer = fieldGroup.querySelector('.property-select-container');
                        const storageContainer = fieldGroup.querySelector('.storage-select-container');
                        const parkingContainer = fieldGroup.querySelector('.parking-select-container');
                        
                        const propertySelect = propertyContainer.querySelector('select');
                        const storageSelect = storageContainer.querySelector('select');
                        const parkingSelect = parkingContainer.querySelector('select');
                        
                        const initialPropertyId = propertySelect.getAttribute('data-initial-value');
                        const initialStorageId = storageSelect.getAttribute('data-initial-value');
                        const initialParkingId = parkingSelect.getAttribute('data-initial-value');

                        // Get current status value
                        const currentStatus = statusSelect.value;
                        const showPropertySelect = ['6', '11'].includes(currentStatus);
                        
                        if (!investmentId || !showPropertySelect) {
                            [propertyContainer, storageContainer, parkingContainer].forEach(container => {
                                container.classList.add('d-none');
                                const select = container.querySelector('select');
                                if (select) {
                                    select.innerHTML = `<option value="">${this.getPlaceholderText(select.name)}</option>`;
                                }
                            });
                            return;
                        }

                        try {
                            const response = await fetch('{{route('admin.crm.clients.getInvestmentProperties')}}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    investment_id: investmentId
                                })
                            });
                            
                            if (response.ok) {
                                const data = await response.json();
                                
                                if (showPropertySelect) {
                                    // Update all selects with their respective data
                                    this.updateSelect(propertySelect, data.data.apartments, initialPropertyId);
                                    this.updateSelect(storageSelect, data.data.storages, initialStorageId);
                                    this.updateSelect(parkingSelect, data.data.parkings, initialParkingId);
                                }
                            } else {
                                // Handle error case
                                [propertySelect, storageSelect, parkingSelect].forEach(select => {
                                    if (select) {
                                        this.updateSelect(select, [], null);
                                    }
                                });
                                toastr.error('Brak dostępnych nieruchomości');
                            }
                            
                        } catch (error) {
                            console.error('Error fetching properties:', error);
                            // Handle error case
                            [propertySelect, storageSelect, parkingSelect].forEach(select => {
                                if (select) {
                                    this.updateSelect(select, [], null);
                                }
                            });
                            toastr.error('Wystąpił błąd podczas pobierania listy nieruchomości');
                        }
                    });
                }

                addRemoveEventListener(fieldGroup) {
                    fieldGroup.querySelector('.remove-field-group').addEventListener('click', () => fieldGroup.remove());
                }

                getNewGroupId() {
                    return this.container.querySelectorAll('[data-fields-group]').length + 1;
                }

                getStatusOptions() {
                    return window.status_options;
                }

                getInvestmentOptions() {
                    return window.investment_options;
                    
                }

                getPurposeOptions() {
                    return [{
                            value: 1,
                            text: 'Prywatne'
                        },
                        {
                            value: 2,
                            text: 'Inwestycyjne'
                        }
                    ];
                }

                createPropertySelect(label, name, selectedValue, classNames) {
                    return `
                        <div class="${classNames}">
                            ${this.createLabel(label, name)}
                            <div class="col-12 control-input position-relative d-flex align-items-center flex-column">
                                <select class="form-select" name="${name}" data-initial-value="${selectedValue}">
                                    <option value="">Wybierz nieruchomość</option>
                                </select>
                            </div>
                        </div>
                    `;
                }

                createStorageSelect(label, name, selectedValue, classNames) {
                    return `
                        <div class="${classNames}">
                            ${this.createLabel(label, name)}
                            <div class="col-12 control-input position-relative d-flex align-items-center flex-column">
                                <select class="form-select" name="${name}" data-initial-value="${selectedValue}">
                                    <option value="">Wybierz komórkę</option>
                                </select>
                            </div>
                        </div>
                    `;
                }

                createParkingSelect(label, name, selectedValue, classNames) {
                    return `
                        <div class="${classNames}">
                            ${this.createLabel(label, name)}
                            <div class="col-12 control-input position-relative d-flex align-items-center flex-column">
                                <select class="form-select" name="${name}" data-initial-value="${selectedValue}">
                                    <option value="">Wybierz parking</option>
                                </select>
                            </div>
                        </div>
                    `;
                }

                updateSelect(select, options = [], initialValue = null) {
                    if (!select) return;
                    
        
                    const placeholderText = this.getPlaceholderText(select.name);
                    
                    select.innerHTML = `<option value="">${placeholderText}</option>`;
                    
   
                    if (!Array.isArray(options) || options.length === 0) {
                        select.parentElement.parentElement.classList.add('d-none');
                        return;
                    }
                    
                   
                    options.forEach(option => {
                        const optionElement = document.createElement('option');
                        optionElement.value = option.id;
                        optionElement.textContent = option.name;
                        if (initialValue && option.id.toString() === initialValue.toString()) {
                            optionElement.selected = true;
                        }
                        select.appendChild(optionElement);
                    });
                    
                   
                    select.parentElement.parentElement.classList.remove('d-none');
                }

          
                getPlaceholderText(selectName) {
                    if (selectName.includes('property_id')) {
                        return 'Brak dostępnych nieruchomości';
                    } else if (selectName.includes('storage_id')) {
                        return 'Brak dostępnych komórek';
                    } else if (selectName.includes('parking_id')) {
                        return 'Brak dostępnych miejsc parkingowych';
                    }
                    return 'Wybierz';
                }
            }
        </script>
    @endpush
@endsection
