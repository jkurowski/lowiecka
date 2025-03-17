@php
    $subLabel = isset($sublabel) ? '<span>' . $sublabel . '</span>' : '';
    $labelClass = 'col-12 col-form-label control-label pb-2';
    $inputClass = 'form-control';
    $divClass = $class ?? 'col-12 control-input position-relative d-flex align-items-center input-group';
    $required = isset($required) && $required;
    $readonly = isset($readonly) && $readonly;
@endphp

{!! Form::label(
    $name,
    '<div class="text-start">' . $label . ($required ? ' <span class="text-danger d-inline">*</span>' : '') . $subLabel . '</div>',
    ['class' => $labelClass . ($required ? ' required' : '')],
    false
) !!}

<div class="{{ $divClass }}">
    {!! Form::text($name, old($name, $value), ['class' => $inputClass, 'data-type' => $dataType, ($required ? ' required' : ''), ($readonly ? ' readonly' : '')]) !!}
    <button class="btn btn-primary" type="button" id="getClientData">Wczytaj dane klienta</button>
</div>
@if($errors->first($name))
    <div class="col-12 col-form-label control-label pb-2"></div>
    <div class="col-12 control-input invalid-feedback d-block">{{ $errors->first($name) }}</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('getClientData');
        const token = '{{ csrf_token() }}';

        button.addEventListener('click', function () {
            jQuery.ajax({
                type: 'POST',
                data: {
                    '_token': token
                },
                url: '{{ route('admin.rodo.clients.modal') }}',
                success: function(response) {
                    if(response) {
                        $('body').append(response);
                        initClientModal();
                    } else {
                        alert('Error');
                    }
                }
            });
        });
    });

    function initClientModal() {

        const modal = document.getElementById('portletModal'),
            clientDataModal = new bootstrap.Modal(modal),
            form = document.getElementById('modalForm'),
            inputClient = $('#inputClient'),
            inputClientId = $('#inputClientId');

        const users = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.obj.nonword(['name', 'mail', 'phone']),
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: {
                url: "/admin/rodo/clients"
            }
        });

        clientDataModal.show();

        modal.addEventListener('shown.bs.modal', function () {
            users.clearPrefetchCache();
            users.initialize();
            inputClient.typeahead({
                    hint: true,
                    highlight: true,
                    minLength: 3
                },
                {
                    name: 'users',
                    templates: {
                        suggestion: function (data) {
                            return '<div class="item">' +
                                '<div class="row">' +
                                '<div class="col-12"><h4>' + data.name + ' ' + data.lastname + '</h4></div>' +
                                '<div class="col-12">' + (data.mail ? '<span>E: ' + data.mail + '</span>' : '') + '</div>' +
                                '<div class="col-12">' + (data.phone ? '<span>T: ' + data.phone + '</span>' : '') + '</div>' +
                                '<div class="col-12">' + (data.nip ? '<span>NIP: ' + data.nip + '</span>' : '') + '</div>' +
                                '<div class="col-12">' + (data.pesel ? '<span>PESEL: ' + data.pesel + '</span>' : '') + '</div>' +
                                '</div>' +
                                '</div>';
                        }
                    },
                    display: 'value',
                    source: users
                });
            inputClient.on('typeahead:select', function (ev, suggestion) {
                const inputPesel = $('[data-type="client-pesel"]');
                if (inputPesel.length) {
                    inputPesel.val(suggestion.pesel);
                } else {
                    console.log('Input with data-type="client-pesel" not found');
                }

                const inputCity = $('[data-type="client-city"]');
                if (inputCity.length) {
                    inputCity.val(suggestion.city);
                } else {
                    console.log('Input with data-type="client-city" not found');
                }

                const inputStreet = $('[data-type="client-street"]');
                if (inputStreet.length) {
                    inputStreet.val(suggestion.street);
                } else {
                    console.log('Input with data-type="client-email" not found');
                }

                const inputFlat = $('[data-type="client-flat"]');
                if (inputFlat.length) {
                    if (suggestion.house_number && suggestion.apartment_number) {
                        inputFlat.val(suggestion.house_number + '/' + suggestion.apartment_number);
                    } else if (suggestion.apartment_number) {
                        inputFlat.val(suggestion.apartment_number);
                    } else {
                        console.log('No valid house_number or apartment_number found');
                        inputFlat.val('');
                    }
                } else {
                    console.log('Input with data-type="client-flat" not found');
                }

                const inputEmail = $('[data-type="client-email"]');
                if (inputEmail.length) {
                    inputEmail.val(suggestion.mail);
                } else {
                    console.log('Input with data-type="client-email" not found');
                }

                const inputPhone = $('[data-type="client-phone"]');
                if (inputPhone.length) {
                    inputPhone.val(suggestion.phone);
                } else {
                    console.log('Input with data-type="client-phone" not found');
                }

                const inputName = $('[data-type="client-name"]');
                if (inputName.length) {
                    inputName.val(suggestion.lastname +' '+ suggestion.name);
                } else {
                    console.log('Input with data-type="client-name" not found');
                }

                const inputID = $('[data-type="client-id_number"]');
                if (inputID.length) {
                    inputID.val(suggestion.id_number);
                } else {
                    console.log('Input with data-type="client-id_number" not found');
                }

                clientDataModal.hide();
                modal.remove();
            });
        })
    }
</script>