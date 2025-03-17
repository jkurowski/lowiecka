@extends('admin.layout')

@section('content')
    @if (Route::is('admin.developro.investment.edit'))
        <form method="POST" action="{{ route('admin.developro.investment.update', $entry->id) }}"
            enctype="multipart/form-data">
            @method('PUT')
        @else
            <form method="POST" action="{{ route('admin.developro.investment.store') }}" enctype="multipart/form-data">
    @endif
    @csrf
    <div class="container">
        <div class="card">
            <div class="card-head container">
                <div class="row">
                    <div class="col-12 pl-0">
                        <h4 class="page-title"><i class="fe-book-open"></i><a
                                href="{{ route('admin.developro.investment.index') }}" class="p-0">Inwestycje</a><span
                                class="d-inline-flex ms-2 me-2">/</span>{{ $cardTitle }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            @include('form-elements.back-route-button')
            <div class="card-body control-col12">

                <div class="row w-100 mb-4">
                    <div class="col-12">
                        @include('form-elements.html-input-text', [
                            'label' => 'Nazwa inwestycji',
                            'name' => 'name',
                            'value' => $entry->name,
                            'required' => 1,
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4">
                    <div class="col-6">
                        @include('form-elements.html-select', [
                            'label' => 'Typ inwestycji',
                            'name' => 'type',
                            'selected' => $entry->type,
                            'select' => [
                                '1' => 'Inwestycja osiedlowa',
                                '2' => 'Inwestycja budynkowa',
                                '3' => 'Inwestycja z domami',
                            ],
                        ])
                    </div>
                    <div class="col-6">
                        @include('form-elements.html-select', [
                            'label' => 'Status inwestycji',
                            'name' => 'status',
                            'selected' => $entry->status,
                            'select' => [
                                '1' => 'Inwestycja w sprzedaży',
                                '2' => 'Inwestycja zakończona',
                                '3' => 'Inwestycja planowana',
                                '4' => 'Inwestycja ukryta',
                            ],
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4">
                    <div class="col-6">
                        @include('form-elements.html-input-text', [
                            'label' => 'Adres inwestycji',
                            'name' => 'address',
                            'value' => $entry->address,
                        ])
                    </div>
                    <div class="col-6">
                        @include('form-elements.html-input-text', [
                            'label' => 'Miasto inwestycji',
                            'name' => 'city',
                            'value' => $entry->city,
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4">
                    <div class="col-6">
                        @include('form-elements.html-input-text', [
                            'label' => 'Termin rozpoczęcia inwestycji',
                            'name' => 'date_start',
                            'value' => $entry->date_start,
                        ])
                    </div>
                    <div class="col-6">
                        @include('form-elements.html-input-text', [
                            'label' => 'Termin zakończenia inwestycji',
                            'name' => 'date_end',
                            'value' => $entry->date_end,
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4">
                    <div class="col-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Ilość lokali',
                            'sublabel' => '(tylko liczby)',
                            'name' => 'areas_amount',
                            'value' => $entry->areas_amount,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.input-text', [
                            'label' => 'Zakres powierzchni w wyszukiwarce xx-xx',
                            'sublabel' => '(zakresy oddzielone przecinkiem)',
                            'name' => 'area_range',
                            'value' => $entry->area_range,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-select', [
                            'label' => 'Pokaż ceny',
                            'sublabel' => '(ceny wszystkich powierzchni)',
                            'name' => 'show_prices',
                            'selected' => $entry->show_prices,
                            'select' => [
                                '1' => 'Tak',
                                '0' => 'Nie',
                            ],
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4">
                    <div class="col-4">
                        @include('form-elements.html-select', [
                            'label' => 'Pokaż mieszkania jako',
                            'sublabel' => '()',
                            'name' => 'show_properties',
                            'selected' => $entry->show_properties,
                            'select' => [
                                '1' => 'Plan z wyborem i lista',
                                '2' => 'Tylko lista',
                                '3' => 'Tylko lista dostępnych',
                            ],
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-select-multiple', [
                            'label' => 'Opiekun inwestycji',
                            'sublabel' => '()',
                            'name' => 'users',
                            'select' => $users,
                            'selected' => $selected,
                            'required' => 1,
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4">
                    <div class="col-12">
                        @include('form-elements.html-input-text', [
                            'label' => 'Opiekunowie inwestycji z portali nieruchomości',
                            'sublabel' =>
                                'Podaj adres-y e-mail na które zostanie wysłane powiadmienie o zmianach w inwestycji',
                            'name' => 'supervisors',
                            'value' => $entry->supervisors,
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4">
                    <div class="col-12">
                        @include('form-elements.html-input-text', [
                            'label' => 'Adres biura sprzedaży',
                            'name' => 'office_address',
                            'value' => $entry->office_address,
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4">
                    <div class="col-12">
                        @include('form-elements.html-input-text', [
                            'label' => 'Numer konta bankowego',
                            'name' => 'bank_account',
                            'value' => $entry->bank_account,
                            'inputclass' => 'input-mask',
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-5">
                    <div class="col-4">
                        @include('form-elements.html-input-text-count', [
                            'label' => 'Nagłówek strony',
                            'sublabel' => 'Meta tag - title',
                            'name' => 'meta_title',
                            'value' => $entry->meta_title,
                            'maxlength' => 60,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-input-text-count', [
                            'label' => 'Opis strony',
                            'sublabel' => 'Meta tag - description',
                            'name' => 'meta_description',
                            'value' => $entry->meta_description,
                            'maxlength' => 158,
                        ])
                    </div>
                    <div class="col-4">
                        @include('form-elements.html-input-text', [
                            'label' => 'Indeksowanie',
                            'sublabel' => 'Meta tag - robots',
                            'name' => 'meta_robots',
                            'value' => $entry->meta_robots,
                        ])
                    </div>
                </div>

                <div class="row w-100 mb-4">
                    @include('form-elements.html-input-text', [
                        'label' => 'Krótki opis na liście',
                        'name' => 'entry_content',
                        'value' => $entry->entry_content,
                    ])
                </div>

                <div class="row w-100 mb-4">
                    @include('form-elements.html-input-file', [
                        'label' => 'Miniaturka',
                        'sublabel' =>
                            '(wymiary: ' .
                            config('images.investment.thumb_width') .
                            'px / ' .
                            config('images.investment.thumb_height') .
                            'px)',
                        'name' => 'file',
                        'file' => $entry->file_thumb,
                        'file_preview' => config('images.investment.preview_file_path'),
                    ])
                </div>

                {{-- Szablony @start --}}
                {{-- <div class="my-4 control-col12">

                    <h2>Szablony</h2>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Podziękowanie za wysłanie formularza',
                            'name' => 'investmentTemplates[template_send_thanks]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_send_thanks,
                        ])
                    </div>
                    
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Z ofertą',
                            'name' => 'investmentTemplates[template_offer_mail]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_offer_mail,
                        ])
                    </div>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Przypomnienie o przesłanej ofercie',
                            'name' => 'investmentTemplates[template_offer_reminder]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_offer_reminder,
                        ])
                    </div>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Zaproszenie na dzień otwarty',
                            'name' => 'investmentTemplates[template_open_day]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_open_day,
                        ])
                    </div>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Zaproszenie na podpisanie umowy przedwstępnej',
                            'name' => 'investmentTemplates[template_preliminary_agreement]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_preliminary_agreement,
                        ])
                    </div>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Zaproszenie na przegląd lokalu',
                            'name' => 'investmentTemplates[template_local_review]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_local_review,
                        ])
                    </div>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Zaproszenie na odbiór lokalu',
                            'name' => 'investmentTemplates[template_local_pickup]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_local_pickup,
                        ])
                    </div>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Zaproszenie na podpisanie umowy przeniesienia własności',
                            'name' => 'investmentTemplates[template_transfer_of_ownership]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_transfer_of_ownership,
                        ])
                    </div>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Informacja o zmianach lokatorskich',
                            'name' => 'investmentTemplates[template_tenant_changes]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_tenant_changes,
                        ])
                    </div>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Informacja o dokumentach potrzebnych do uzyskania kredytu',
                            'name' => 'investmentTemplates[template_documents_for_credit]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_documents_for_credit,
                        ])
                    </div>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Faktury i rozliczenia',
                            'name' => 'investmentTemplates[template_invoices]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_invoices,
                        ])
                    </div>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Przesyłane dokumenty np po odbiorze, umowie',
                            'name' => 'investmentTemplates[template_documents]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_documents,
                        ])
                    </div>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Oferty specjalne',
                            'name' => 'investmentTemplates[template_special_offers]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_special_offers,
                        ])
                    </div>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Zaproszenie na spotkanie',
                            'name' => 'investmentTemplates[template_meeting_invitation]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_meeting_invitation,
                        ])
                    </div>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Przypominajka o spotkaniu',
                            'name' => 'investmentTemplates[template_meeting_reminder]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_meeting_reminder,
                        ])
                    </div>
                    <div class='row w-100 form-group mb-4'>
                        @include('form-elements.html-select', [
                            'label' => 'Email - Wygaśnięcie oferty',
                            'name' => 'investmentTemplates[template_offer_expiration]',
                            'select' => $emailTemplates,
                            'selected' => $investmentTemplates->template_offer_expiration,
                        ])
                    </div>
                  
                </div> --}}
                {{-- Szablony @end --}}

                <div class="row w-100 mb-4">
                    @include('form-elements.textarea-fullwidth', [
                        'label' => 'Email - Opis inwestycji',
                        'name' => 'content',
                        'value' => $entry->content,
                        'rows' => 11,
                        'class' => 'tinymce',
                        'required' => 1,
                    ])
                </div>

                <div class="row w-100 mb-4">
                    @include('form-elements.textarea-fullwidth', [
                        'label' => 'Email - Opis inwestycji po zakończeniu',
                        'name' => 'end_content',
                        'value' => $entry->end_content,
                        'rows' => 11,
                        'class' => 'tinymce',
                    ])
                </div>
            </div>
        </div>
    </div>
    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz'])
    </form>
    @include('form-elements.tintmce')
@endsection
@push('scripts')
    <script src="{{ asset('/js/inputmask.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('[name=supervisors]').tagify({
                'autoComplete.enabled': false
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            const ibanMask = new Inputmask("A{0,2}99 9999 9999 9999 9999 9999 9999");
            ibanMask.mask(document.getElementById('bank_account'));
        });
    </script>
@endpush
