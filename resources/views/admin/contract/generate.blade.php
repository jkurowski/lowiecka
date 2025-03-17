@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    <form method="POST" action="{{ route('admin.contract.generate', $entry) }}" enctype="multipart/form-data" id="generator">
        @csrf
        <div class="container">
            <div class="card-head container">
                <div class="row">
                    <div class="col-12 pl-0">
                        <h4 class="page-title"><i class="fe-book-open"></i><a href="{{route('admin.contract.index')}}" class="p-0">Generator dokumentów</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                @include('form-elements.back-route-button')
                <div class="card-body">
                    <div class="row w-100 form-group">
                        @include('form-elements.html-select', ['label' => 'Generuj jako', 'name' => 'file_type', 'select' => [
                            '1' => 'Plik .docx',
                            //'2' => 'Plik .pdf'
                            ]])
                    </div>
                    <div class="row w-100 form-group">
                        <div class="col-6">
                            <div class="row">
                                <label for="inputInvestment"
                                       class="col-123 col-form-label control-label required mb-2 justify-content-start">Inwestycja<span class="text-danger d-inline w-auto ps-1">*</span></label>
                                <div class="col-12">
                                    <select class="form-select" id="inputInvestment" name="investment_id" onchange="fetchInvestmentProperties()">
                                        <option value="0">Inwestycja</option>
                                        @foreach($investments as $i)
                                            <option value="{{ $i->id }}" @if($entry->investment_id == $i->id) selected @endif>{{ $i->name }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->first('investment_id'))
                                        <div class="invalid-feedback d-block">{{ $errors->first('investment_id') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="row dynamic-property-row d-none">
                                <label for="inputProperty" class="col-12 col-form-label control-label required mb-2 justify-content-start">Lokal</label>
                                <div class="col-12">
                                    <select class="form-control selectpicker" data-live-search="true" name="property_id" id="inputProperty">
                                        <option value="0">Wybierz opcje</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if(isset($placeholders) && !empty($placeholders))
                                @foreach($placeholders as $p => $pp)
                                    @php
                                        $view = 'admin.contract.form-elements.html-input-' . $pp['type'];

                                        if (!view()->exists($view)) {
                                            $view = 'admin.contract.form-elements.html-input-text';
                                        }
                                    @endphp

                                    @include($view, [
                                        'label' => $pp['form'],
                                        'name' => $pp['placeholder'],
                                        'value' => '',
                                        'required' => $pp['required'] ? 1 : 0,
                                        'dataType' => $pp['type']
                                    ])
                                @endforeach
                            @else
                                <div class="alert alert-warning" role="alert">
                                    Brak znaczników w dokumencie
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('form-elements.submit', ['name' => 'submit', 'value' => 'Generuj'])
    </form>
@endsection
@push('scripts')
    <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/bootstrap-select/bootstrap-select.min.js') }}" charset="utf-8"></script>
    <link href="{{ asset('/js/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
    <script>
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            language: "pl"
        });

        const investmentNameInput = document.querySelector('[data-type="investment-name"]');
        const investmentSelect = document.getElementById('inputInvestment');

        clearPropertyDetails();
        clearInvestmentDetails();

        function fetchInvestmentProperties() {
            const investmentId = investmentSelect.value;
            const selectPropertyRow = document.querySelector('.dynamic-property-row');
            const selectPropertyElement = document.getElementById('inputProperty');

            clearPropertyDetails();

            if (investmentNameInput) {
                if (investmentId > 0) {
                    const selectedOption = investmentSelect.options[investmentSelect.selectedIndex];
                    const investmentName = selectedOption.textContent.trim();
                    if (investmentNameInput) {
                        investmentNameInput.value = investmentName;
                    }
                } else {
                    clearInvestmentDetails();
                }
            }

            if (investmentId !== '0') {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', '/admin/developro/investment/' + investmentId + '/properties', true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        const properties = JSON.parse(xhr.responseText);

                        if (Object.keys(properties).length > 0) {
                            console.log('Properties found:', properties);

                            const propertyTypes = { property: 1 };

                            Object.entries(propertyTypes).forEach(([type, value]) => {
                                const selectRow = document.querySelector(`.dynamic-${type}-row`);
                                const selectElement = document.getElementById(`input${type.charAt(0).toUpperCase() + type.slice(1)}`);

                                if (properties[value] && properties[value].length > 0) {
                                    console.log(`Properties type: ${type} exist`);

                                    selectRow.classList.remove('d-none');

                                    const firstOption = document.createElement('option');
                                    firstOption.value = '0';
                                    firstOption.textContent = 'Wybierz lokal';
                                    selectElement.innerHTML = '';
                                    selectElement.appendChild(firstOption);

                                    properties[value].forEach(property => {
                                        const option = document.createElement('option');
                                        option.value = property.id;
                                        option.textContent = property.name;
                                        selectElement.appendChild(option);
                                    });

                                    // Attach an event listener to handle changes
                                    selectElement.onchange = function () {
                                        handlePropertyChange(selectElement.value);
                                    };

                                    $(selectElement).selectpicker('destroy');
                                    $(selectElement).selectpicker();
                                } else {
                                    selectRow.classList.add('d-none');
                                    selectElement.innerHTML = '';
                                    $(selectElement).selectpicker('destroy');
                                }
                            });

                            @if($entry->property_id)
                            console.log("Property selected: " + {{ $entry->property_id }});
                            $('.dynamic-property-row .selectpicker').selectpicker('val', '{{ $entry->property_id }}');
                            @endif
                        } else {
                            resetSelectElements([selectPropertyRow], [selectPropertyElement]);
                        }
                    }
                };
                xhr.send();
            } else {
                resetSelectElements([selectPropertyRow], [selectPropertyElement]);
            }
        }

        function handlePropertyChange(propertyId) {
            console.log('Property ID changed to:', propertyId);

            if (propertyId > 0) {
                // Fetch property details via AJAX
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `/admin/developro/property-details/${propertyId}`, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                        const propertyDetails = JSON.parse(xhr.responseText);

                        console.log('Property details:', propertyDetails);

                        // Update fields with property details
                        updatePropertyDetails(propertyDetails);
                    }
                };
                xhr.send();
            } else {
                // Clear fields if no property is selected
                clearPropertyDetails();
            }
        }

        // Function to update property details in the UI
        function updatePropertyDetails(details) {
            const propertyNameInput = document.querySelector('[data-type="property-name"]');
            const propertyAreaInput = document.querySelector('[data-type="property-area"]');
            const propertyRoomsInput = document.querySelector('[data-type="property-rooms"]');
            const propertyPriceInput = document.querySelector('[data-type="property-price"]');
            const propertyLocationInput = document.querySelector('[data-type="property-location"]');

            if (propertyNameInput) propertyNameInput.value = details.name || '';
            if (propertyAreaInput) propertyAreaInput.value = details.area || '';
            if (propertyRoomsInput) propertyRoomsInput.value = details.rooms || '';
            if (propertyPriceInput) propertyPriceInput.value = details.price || '';
            if (propertyLocationInput) propertyLocationInput.value = details.location || '';
        }

        // Function to clear property details
        function clearPropertyDetails() {
            const propertyNameInput = document.querySelector('[data-type="property-name"]');
            const propertyAreaInput = document.querySelector('[data-type="property-area"]');
            const propertyRoomsInput = document.querySelector('[data-type="property-rooms"]');
            const propertyPriceInput = document.querySelector('[data-type="property-price"]');
            const propertyLocationInput = document.querySelector('[data-type="property-location"]');

            if (propertyNameInput) propertyNameInput.value = '';
            if (propertyAreaInput) propertyAreaInput.value = '';
            if (propertyRoomsInput) propertyRoomsInput.value = '';
            if (propertyPriceInput) propertyPriceInput.value = '';
            if (propertyLocationInput) propertyLocationInput.value = '';
        }

        function clearInvestmentDetails(){
            if (investmentNameInput) {
                investmentNameInput.value = '';
                investmentSelect.value = '0';
            }
        }

        function resetSelectElements(rowsToHide, selectsToClear) {
            rowsToHide.forEach(row => row.classList.add('d-none'));
            selectsToClear.forEach(select => {
                select.innerHTML = '';
                const defaultOption = document.createElement('option');
                defaultOption.value = '0';
                defaultOption.textContent = 'Select an option';
                select.appendChild(defaultOption);
                $(select).selectpicker('refresh');
            });
        }
    </script>
@endpush
