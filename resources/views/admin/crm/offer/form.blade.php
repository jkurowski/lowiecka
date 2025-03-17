@extends('admin.layout')
@section('meta_title', '- ' . $cardTitle)

@section('content')
    <form id="offerForm" method="POST" action="{{ route('admin.crm.offer.update', $entry) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="container">
            <div class="card-head container">
                <div class="row">
                    <div class="col-12 pl-0">
                        <h4 class="page-title"><i class="fe-book-open"></i><a href="{{ route('admin.crm.offer.index') }}"
                                class="p-0">Oferty</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                @include('form-elements.back-route-button')
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group row">
                                <div class="col-12">
                                    <h2>Dane klienta</h2>
                                </div>
                                <div class="col-12 py-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_new_client"
                                            id="isNewClient" value="1" {{ $entry->is_new_client ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isNewClient">
                                            Klient nie istnieje w bazie
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 {{ $entry->is_new_client ? 'd-none' : '' }}" data-show-if-new-client>
                                    <label for="inputClient"
                                        class="col-12 col-form-label control-label required text-end">Znajdź w bazie</label>
                                    <div class="col-12 mb-3">
                                        <input type="text"
                                            class="validate[required] form-control @error('client') is-invalid @enderror"
                                            id="inputClient" name="client" autocomplete="off">
                                        <input type="hidden" name="client_id" value="0" id="inputClientId">
                                        @if ($errors->first('client'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('client') }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-6 {{ $entry->is_new_client ? 'd-none' : '' }}" data-show-if-new-client>
                                    @include('form-elements.input-text', [
                                        'label' => 'Imię',
                                        'name' => 'client_name',
                                        'value' => $entry->client ? $entry->client->name : '',
                                        'required' => $entry->is_new_client,
                                    ])
                                </div>
                                <div class="col-6 {{ $entry->is_new_client ? 'd-none' : '' }}" data-show-if-new-client>
                                    @include('form-elements.input-text', [
                                        'label' => 'Nazwisko',
                                        'name' => 'client_surname',
                                        'value' => $entry->client ? $entry->client->surname : '',
                                    ])
                                </div>
                                <div class="col-6 {{ $entry->is_new_client ? 'd-none' : '' }}" data-show-if-new-client>
                                    @include('form-elements.input-text', [
                                        'label' => 'Adres e-mail',
                                        'name' => 'client_email',
                                        'value' => $entry->client ? $entry->client->mail : '',
                                        'required' => $entry->is_new_client,
                                    ])
                                </div>
                                <div class="col-6 {{ $entry->is_new_client ? 'd-none' : '' }}" data-show-if-new-client>
                                    @include('form-elements.input-text', [
                                        'label' => 'Telefon',
                                        'name' => 'client_phone',
                                        'value' => $entry->client ? $entry->client->phone : '',
                                    ])
                                </div>
                            </div>

                            <div class="form-group row mt-5 pb-0 mb-0 border-0">
                                <div class="col-12">
                                    <h2>Ustawienia wiadomości</h2>
                                </div>
                                <div class="col-6">
                                    @include('form-elements.input-text', [
                                        'label' => 'Adresy e-mail BCC',
                                        'sublabel' => 'Ukryte do wiadomości',
                                        'name' => 'email_bcc',
                                        'value' => $entry->email_bcc,
                                    ])
                                </div>
                                <div class="col-6">
                                    @include('form-elements.html-input-date', [
                                        'label' => 'Data wygaśnięcia oferty',
                                        'sublabel' => 'Po tej dacie oferta pod linkiem nie będzie już dostępna',
                                        'name' => 'date_end',
                                        'value' => $entry->date_end,
                                    ])
                                </div>
                            </div>

                            <div class="form-group row mt-5 pb-0 mb-0 border-0">
                                <div class="col-12">
                                    <h2>Treść wiadomości</h2>
                                </div>
                                <div class="col-6">
                                    @include('form-elements.html-input-text', [
                                        'label' => 'Tytuł wiadomości',
                                        'name' => 'title',
                                        'value' => $entry->title,
                                        'required' => 1,
                                    ])
                                </div>
                                <div class="col-12">
                                    @include('form-elements.textarea-fullwidth', [
                                        'label' => 'Treść wiadomości',
                                        'name' => 'message',
                                        'value' => $entry->message,
                                        'rows' => 21,
                                        'class' => 'tinymce',
                                        'required' => 1
                                    ])
                                </div>
                            </div>

                            <div class="form-group row mt-5 pb-0 mb-0 border-0">
                                <div class="col-12">
                                    <h2>Załączniki</h2>
                                </div>
                                <div class="col-12">
                                    <div id="files">
                                        <div class="note">
                                            <div class="noteItemIcon"><i class="fe-hard-drive"></i></div>
                                            <div class="noteItemContent p-0">
                                                @if (count($attachments) > 0)
                                                    @foreach ($attachments as $file)
                                                        <div class="file" data-file-id="{{ $file['id'] }}">
                                                            <div class="noteItemType"><i class="{{ $file['icon'] }}"></i>
                                                            </div>
                                                            <div class="noteItemText">
                                                                <a href="{{ asset('/uploads/offer/' . $file['file']) }}"
                                                                    target="_blank">{{ $file['name'] }}</a>
                                                            </div>
                                                            <div class="noteItemDate">{{ $file['created_at'] }}<span
                                                                    class="separator">·</span>{{ $file['user']['name'] }}
                                                                {{ $file['user']['surname'] }}<span
                                                                    class="separator">·</span>{{ $file['size'] }}</div>
                                                            <div class="noteItemButtons">
                                                                <a role="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false" class="dropdown-menu-dots"><i
                                                                        class="fe-more-horizontal-"></i></a>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li><a class="dropdown-item dropdown-item-download"
                                                                            href="{{ asset('/uploads/offer/' . $file['file']) }}"
                                                                            download>Pobierz</a></li>
                                                                    <li><a class="dropdown-item dropdown-item-delete"
                                                                            href="#">Usuń</a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                        <div class="note-start">
                                            <div class="noteItemDate">{{ $entry->created_at }}</div>
                                            <div class="noteItemClient"><strong>Oferta dodana do systemu</strong></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div id="jquery-wrapped-fine-uploader"></div>
                                </div>
                            </div>

                            <div class="form-group row mt-5 pb-0 mb-0 border-0">
                                <div class="col-12">
                                    <h2>Szablon strony oferty</h2>
                                </div>

                                <div class="col-12">
                                    @include('form-elements.searchable-select', [
                                        'label' => 'Szablon',
                                        'name' => 'offer_template',
                                        'options' => $offer_templates,
                                        'selected' => $selected_template,
                                    ])
                                </div>
                            </div>

                            <div id="propertiesSearch" class="form-group row mt-5 pb-0 mb-0 border-0">
                                <div class="col-12">
                                    <h2>Oferta</h2>
                                </div>
                                <div class="col-3">
                                    @include('form-elements.html-select', [
                                        'label' => 'Inwestycja',
                                        'name' => 'investment',
                                        'selected' => $selectedInvestment,
                                        'select' => $investments,
                                    ])
                                </div>
                                <div class="col-3">
                                    @include('form-elements.html-select', [
                                        'label' => 'Typ powierzchni',
                                        'name' => 'type',
                                        'selected' => '',
                                        'select' => [
                                            '' => 'Wybierz / wszystkie',
                                            '1' => 'Mieszkanie / Apartament',
                                            '2' => 'Komórka lokatorska',
                                            '3' => 'Miejsce parkingowe',
                                        ],
                                    ])
                                </div>
                                <div class="col-3">
                                    @include('form-elements.html-select', [
                                        'label' => 'Ilość pokoi',
                                        'name' => 'rooms',
                                        'selected' => '',
                                        'select' => [
                                            '' => 'Wszystkie',
                                            '2' => '2',
                                            '3' => '3',
                                            '4' => '4',
                                        ],
                                    ])
                                </div>
                                <div class="col-3">
                                    @include('form-elements.html-select', [
                                        'label' => 'Metraż',
                                        'name' => 'area',
                                        'selected' => '',
                                        'select' => [
                                            '' => 'Wybierz / wszystkie',
                                            '40-50' => '40-50',
                                            '51-70' => '51-70',
                                            '71-80' => '71-80',
                                            '81-90' => '81-90',
                                            '91-100' => '91-100',
                                        ],
                                    ])
                                </div>
                                <div class="col-12 mt-4">
                                    <div id="properties">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th style="width: 30px" class="pe-0"></th>
                                                    <th>Nazwa</th>
                                                    <th class="text-center">Pokoje</th>
                                                    <th class="text-center">Powierzchnia</th>
                                                    <th class="text-center">Cena</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="selectedOffer">
                                                @foreach ($selectedOffer as $selected)
                                                    <tr>
                                                        <td class="pe-0 text-center">
                                                            <input type="checkbox" class="checkbox" name="property[]"
                                                                id="{{ $selected->id }}" value="1"
                                                                style="display: none;">
                                                            <span id="{{ $selected->id }}"><i
                                                                    class="las la-trash-alt"></i></span>
                                                        </td>
                                                        <td><a href="{{ $selected->url }}"
                                                                target="_blank">{{ $selected->name_list }}
                                                                {{ $selected->number }}</a></td>
                                                        <td class="text-center">{{ $selected->rooms }}</td>
                                                        <td class="text-center">{{ $selected->area }} m<sup>2</sup></td>
                                                        <td class="text-center">
                                                            @if ($selected->price)
                                                                @money($selected->price)
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span
                                                                class="badge room-list-status-{{ $selected->status }}">{{ roomStatus($selected->status) }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tbody id="ajaxLoad">
                                                <tr>
                                                    <td colspan="6">
                                                        <div class="d-flex justify-content-center mt-5 mb-5">
                                                            <div class="spinner-border text-primary" role="status">
                                                                <span class="visually-hidden">Loading...</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($entry->status == 2)
            @include('form-elements.submit', ['name' => 'submit', 'value' => 'Wyślij ofertę'])
        @endif
    </form>
    @include('form-elements.tintmce', ['templates' => 1])
    @routes('offer_files')
@endsection
@push('scripts')
    <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/typeahead.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/ui/jquery-ui.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/fineuploader.js') }}" charset="utf-8"></script>

    <link href="{{ asset('/js/ui/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
    <script>
        function sendAjaxRequest(selectedValues) {
            $.ajax({
                url: '{{ route('admin.crm.offer.ajax.search', $entry->id) }}',
                type: 'GET',
                data: selectedValues,
                dataType: 'json',
                success: function(response) {
                    $('#ajaxLoad').html(response.html);
                    attachCheckboxFunctionality();
                    restrictFromChangingInvestmentWhenPropertyIsSelected();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function restrictFromChangingInvestmentWhenPropertyIsSelected() {
            const selectedOffers = document.getElementById('selectedOffer');
            const investmentSelect = document.getElementById('investmentSelect');
            if (!selectedOffers) return;

            if (selectedOffers.children.length > 0) {
                investmentSelect.disabled = true;
            } else {
                investmentSelect.disabled = false;
            }
        }

        async function housingEstateInvestmentSelectedHandler(buildingId) {
            const investmentSelect = document.getElementById('investmentSelect');
            if (!investmentSelect?.value) return;

            const buildingSelectContainer = document.createElement('div');
            buildingSelectContainer.className = 'col-3';
            buildingSelectContainer.id = 'buildingSelectContainer';

            async function getInvestmentData() {
                try {
                    const response = await fetch('{{ route('admin.developro.ajax.investment') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            investment_id: investmentSelect.value
                        })
                    });

                    if (!response.ok) throw new Error('Network response was not ok');
                    return await response.json();
                } catch (error) {
                    console.error('Error fetching investment data:', error);
                    return null;
                }
            }

            async function loadBuildingsForInvestment(investmentId) {
                try {
                    const response = await fetch('{{ route('admin.developro.ajax.investment') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            get_investment_buildings: investmentId
                        })
                    });

                    if (!response.ok) throw new Error('Network response was not ok');
                    const data = await response.json();
                    return data.investment_buildings;
                } catch (error) {
                    console.error('Error fetching buildings:', error);
                    return [];
                }
            }

            async function loadPropertiesForBuilding(investmentId, buildingId) {
                try {
                    const response = await fetch('{{ route('admin.developro.ajax.investment') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            get_investment_properties_based_on_building: investmentId,
                            get_investment_properties_based_on_building_building_id: buildingId
                        })
                    });

                    if (!response.ok) throw new Error('Network response was not ok');
                    const data = await response.json();
                    return data.investment_properties_based_on_building;
                } catch (error) {
                    console.error('Error fetching properties:', error);
                    return [];
                }
            }

            function createBuildingSelect(buildings) {
                const select = document.createElement('select');
                select.className = 'form-select';
                select.id = 'buildingSelect';
                select.name = 'building';

                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Wybierz budynek';
                select.appendChild(defaultOption);

                buildings.forEach(building => {
                    const option = document.createElement('option');
                    option.value = building.id;
                    option.textContent = building.name;
                    select.appendChild(option);
                    console.log(parseInt(buildingId), building.id);
                    if (parseInt(buildingId) === building.id) {
                        select.value = buildingId;
                    }
                });



                const label = document.createElement('label');
                label.className = 'col-12 col-form-label control-label pb-2';
                label.textContent = 'Budynek';
                label.htmlFor = 'buildingSelect';

                buildingSelectContainer.innerHTML = '';
                buildingSelectContainer.appendChild(label);
                buildingSelectContainer.appendChild(select);

                return select;
            }


            const investmentData = await getInvestmentData();

            if (!investmentData) return;

            const isHousingEstate = investmentData.investment.type === 1;

            if (isHousingEstate) {
                const buildings = await loadBuildingsForInvestment(investmentSelect.value);
                const buildingSelect = createBuildingSelect(buildings);


                const investmentSelectParent = investmentSelect.closest('.col-3');
                investmentSelectParent.after(buildingSelectContainer);


                buildingSelect.addEventListener('change', async function() {
                    if (this.value) {
                        const properties = await loadPropertiesForBuilding(investmentSelect.value, this
                            .value);

                        const selectedValues = {
                            investmentSelect: investmentSelect.value,
                            buildingSelect: this.value,
                            typeSelect: document.getElementById('typeSelect').value,
                            roomsSelect: document.getElementById('roomsSelect').value,
                            areaSelect: document.getElementById('areaSelect').value
                        };
                        sendAjaxRequest(selectedValues);
                    }
                });
            } else {

                document.getElementById('buildingSelectContainer')?.remove();


                const selectedValues = {
                    investmentSelect: investmentSelect.value,
                    typeSelect: document.getElementById('typeSelect').value,
                    roomsSelect: document.getElementById('roomsSelect').value,
                    areaSelect: document.getElementById('areaSelect').value
                };
                sendAjaxRequest(selectedValues);
            }
        }


        document.getElementById('investmentSelect')?.addEventListener('change', housingEstateInvestmentSelectedHandler);


        if (document.getElementById('investmentSelect')?.value) {
            housingEstateInvestmentSelectedHandler('{{ $selectedBuilding }}');
        }

        function attachCheckboxFunctionality() {
            const checkboxes = document.querySelectorAll(".checkbox");

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    const parentTr = this.closest("tr");
                    let clonedTr;

                    if (this.checked) {
                        const clickedCheckboxId = this.id;

                        $.ajax({
                            url: route('admin.crm.offer.property', [{{ $entry->id }},
                                clickedCheckboxId
                            ]),
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                clonedTr = parentTr.cloneNode(true);
                                const clonedCheckbox = clonedTr.querySelector(".checkbox");
                                clonedCheckbox.style.display = "none";

                                const spanElement = document.createElement("span");
                                spanElement.id = clickedCheckboxId;
                                spanElement.innerHTML = '<i class="las la-trash-alt"></i>';
                                spanElement.addEventListener("click", function() {
                                    const closestTr = this.closest("tr");
                                    closestTr.remove();
                                });

                                clonedTr.querySelector("td:first-child").appendChild(
                                    spanElement);
                                document.querySelector("#selectedOffer").appendChild(clonedTr);
                                restrictFromChangingInvestmentWhenPropertyIsSelected();
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                            }
                        });
                    } else {
                        const clonedTr = document.querySelector("#selectedOffer tr#" + this.id);
                        if (clonedTr) {
                            clonedTr.remove();
                        }
                    }
                    parentTr.remove();
                });
            });
        }


        const loadedValues = {};
        $('#propertiesSearch select').each(function() {
            const selectId = $(this).attr('id');
            loadedValues[selectId] = $(this).val();
        });

        sendAjaxRequest(loadedValues);

        $('#propertiesSearch').on('change', function(e) {
            const selectedValues = {};
            console.log(e);

            if (e.handleObj.type === 'change') {
                $('#propertiesSearch select').each(function() {
                    const selectId = $(this).attr('id');
                    selectedValues[selectId] = $(this).val();
                });

                sendAjaxRequest(selectedValues);
            }


        });

        const users = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.nonword(['name', 'mail', 'phone']),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                prefetch: {
                    url: '{{ route('admin.rodo.clients.index') }}'
                }
            }),
            inputClient = $('#inputClient'),
            inputClientId = $('#inputClientId'),
            inputClientName = $('#form_client_name'),
            inputClientSurname = $('#form_client_surname'),
            inputClientPhone = $('#form_client_phone'),
            inputClientEmail = $('#form_client_email');

        users.clearPrefetchCache();
        users.initialize();
        inputClient.typeahead({
            hint: true,
            highlight: true,
            minLength: 3
        }, {
            name: 'users',
            templates: {
                suggestion: function(data) {
                    return '<div class="item">' +
                        '<div class="row">' +
                        '<div class="col-12"><h4>' + data.name + '</h4></div>' +
                        '<div class="col-6">' + (data.mail ? '<span>E: ' + data.mail + '</span>' : '') +
                        '</div>' +
                        '<div class="col-6">' + (data.phone ? '<span>T: ' + data.phone + '</span>' : '') +
                        '</div>' +
                        '</div>' +
                        '</div>';
                }
            },
            display: 'value',
            source: users
        });

        inputClient.bind('typeahead:select', function(ev, suggestion) {
            inputClientName.val(suggestion.name)
            inputClientSurname.val(suggestion.surname)
            inputClientPhone.val(suggestion.phone)
            inputClientEmail.val(suggestion.mail)
            inputClientId.val(suggestion.id);
        });

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            language: "pl"
        });
        $('[name=page_email]').tagify({
            'autoComplete.enabled': false
        });


        const isNewClientCheckbox = document.getElementById('isNewClient');

        function toggleClientFields() {
            const isChecked = isNewClientCheckbox.checked;
            const dataShowIfNewClient = document.querySelectorAll('[data-show-if-new-client]');
            const submitButton = document.querySelector('#submit');

            dataShowIfNewClient.forEach(input => {
                input.classList.toggle('d-none', isChecked);
            });

            submitButton.value = isChecked ? "Zapisz" : "Wyślij ofertę";
        }

        function toggleFieldsRequiredIfNotNewClient() {
            const fieldsIds = ['form_client_name', 'form_client_email']
            fieldsIds.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                field.required = !isNewClientCheckbox.checked;
            });
        }

        toggleClientFields();
        toggleFieldsRequiredIfNotNewClient();
        isNewClientCheckbox.addEventListener('change', toggleClientFields);
        isNewClientCheckbox.addEventListener('change', toggleFieldsRequiredIfNotNewClient);
    </script>

    <script>
        const UploadedFile = ({
                id,
                icon,
                file,
                name,
                user,
                created_at,
                file_size
            }) =>
            `<div class="file" data-file-id="${id}"><div class="noteItemType"><i class="${icon}"></i></div><div class="noteItemText"><a href="/uploads/offer/${file}" target="_blank">${name}</a><p></p></div><div class="noteItemDate">${created_at}<span class="separator">·</span>${user}<span class="separator">·</span>${file_size}</div><div class="noteItemButtons"><a role="button" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-menu-dots"><i class="fe-more-horizontal-"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item dropdown-item-download" href="/uploads/offer/${file}" download>Pobierz</a></li><li><a class="dropdown-item dropdown-item-delete" href="#">Usuń</a></li></ul></div></div>`;

        const filesList = $("#files .noteItemContent.p-0");
        let fileCount = 0;

        $('#jquery-wrapped-fine-uploader').fineUploader({
            debug: false,
            multiple: true,
            text: {
                uploadButton: "Wybierz plik",
                dragZone: "Przeciągnij i upuść plik tutaj"
            },
            request: {
                endpoint: '{{ route('admin.crm.offer.file.upload', $entry) }}',
                customHeaders: {
                    "X-CSRF-Token": $("meta[name='csrf-token']").attr("content")
                }
            }
        }).on('error', function(event, id, name, reason) {
            console.log(reason);
        }).on('submit', function() {
            fileCount++;
        }).on('complete', function(event, id, name, response) {
            if (response.success === true) {
                fileCount--;
                if (fileCount === 0) {
                    filesList.prepend([{
                        id: response.file.id,
                        icon: response.file.icon,
                        file: response.file.file,
                        name: response.file.name,
                        user: response.file.user.name + ' ' + response.file.user.surname,
                        created_at: response.file.created_at,
                        file_size: response.file.size
                    }, ].map(UploadedFile).join(''));
                }
            }
        });

        filesList.on('click', '.dropdown-item-delete', function(event) {
            const target = event.target;
            const parent = target.closest(".file");
            $.confirm({
                title: "Potwierdzenie usunięcia",
                message: "Czy na pewno chcesz usunąć?",
                buttons: {
                    Tak: {
                        "class": "btn btn-primary",
                        action: function() {
                            $.ajax({
                                url: route('admin.crm.offer.file.destroy', [
                                    {{ $entry->id }}, parent.dataset.fileId
                                ]),
                                type: "DELETE",
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function() {
                                    toastr.options = {
                                        "closeButton": true,
                                        "progressBar": true
                                    }
                                    toastr.success("Plik został poprawnie usunięty");
                                    parent.style.height = "0px"
                                    parent.remove();
                                }
                            })
                        }
                    },
                    Nie: {
                        "class": "btn btn-secondary",
                        action: function() {}
                    }
                }
            })
        });
    </script>

    {{-- <script>
        let formSubmitted = false;
        let isLeaving = false;
        const pageLoadTime = Date.now();
        const DELETE_WINDOW = 60_000; // 1 min

        async function handlePageLeave(event) {

            if (formSubmitted || isLeaving) {
                return;
            }


            const timeOnPage = Date.now() - pageLoadTime;
            if (timeOnPage > DELETE_WINDOW) {
                return;
            }

            if (event.type === 'beforeunload') {


                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('_method', 'DELETE');

                const success = navigator.sendBeacon(
                    '{{ route('admin.crm.offer.destroy', $entry->id) }}',
                    formData
                );


                if (!success) {
                    try {
                        await new Promise(resolve => setTimeout(resolve, 300));

                        await fetch('{{ route('admin.crm.offer.destroy', $entry->id) }}', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin',
                            keepalive: true
                        });
                    } catch (error) {
                        console.error('Error deleting offer:', error);
                    }
                }


                const start = Date.now();
                while (Date.now() - start < 300) {

                }
            }

            return message;
        }


        window.addEventListener('beforeunload', handlePageLeave);
        document.getElementById('offerForm').addEventListener('change', () => {
            window.removeEventListener('beforeunload', handlePageLeave);
        })
        document.getElementById('submit').addEventListener('click', () => {
            window.removeEventListener('beforeunload', handlePageLeave);     
        })
     
    </script> --}}
@endpush
