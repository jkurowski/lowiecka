<div class="modal fade modal-form" id="portletModal" tabindex="-1" aria-labelledby="portletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{route('admin.crm.clients.store')}}" method="post" id="companyModalForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="portletModalLabel">Nowa firma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fe-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="modal-form container">
                        <div class="form-group row">
                            <div class="col-6">
                                <div class="row">
                                    <label for="inputName"
                                        class="col-12 col-form-label control-label required justify-content-start">Imię<span
                                            class="text-danger d-inline w-auto ps-1">*</span>
                                    </label>
                                    <div class="col-12">
                                        <input type="text"
                                            class="validate[required] form-control @error('title') is-invalid @enderror"
                                            id="inputName" name="name" value="" required>
                                        @if ($errors->first('name'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('name') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="row">
                                    <label for="inputSurname"
                                        class="col-12 col-form-label control-label required justify-content-start">Nazwisko<span
                                            class="text-danger d-inline w-auto ps-1">*</span>
                                    </label>
                                    <div class="col-12">
                                        <input type="text"
                                            class="validate[required] form-control @error('surname') is-invalid @enderror"
                                            id="inputSurname" name="surname" value="{{ $entry->surname }}" required>
                                        @if ($errors->first('surname'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('surname') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div class="row">
                                    <label for="inputEmail"
                                        class="col-12 col-form-label control-label required justify-content-start">E-mail<span
                                            class="text-danger d-inline w-auto ps-1">*</span>
                                    </label>
                                    <div class="col-12">
                                        <input type="text"
                                            class="validate[required] form-control @error('mail') is-invalid @enderror"
                                            id="inputEmail" name="email" value="{{ $entry->mail }}" required>
                                        @if ($errors->first('mail'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('mail') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row w-100">

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
                            
                                <div class="col-6">
                                    @include('form-elements.html-input-text', [
                                        'label' => 'Reprezentant',
                                        'name' => 'exponent',
                                        'value' => $client->exponent ?? '',
                                    ])
                                </div>
                            </div>
                            <input type="hidden" name="is_company" value="on">
                            <input type="hidden" name="status" value="1">
                            <input type="hidden" name="source" value="1">


                            @foreach ($required_rodo_rules as $r)
                                <div class="mt-3 col-12 @error('rule_' . $r->id) is-invalid @enderror">
                                    <div class="d-flex align-items-start">
                                        <input name="rule{{ $r->id }}" id="rule{{ $r->id }}"
                                            value="1" type="checkbox"
                                            @if ($r->required === 1) class="validate[required]" @endif
                                            data-prompt-position="topLeft:0">
                                        <label for="rule{{ $r->id }}" class="rules-text ms-2">
                                            {!! $r->text !!}
                                            @error('rule_' . $r->id)
                                                <span class="invalid-feedback"
                                                    role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                    <button type="submit" class="btn btn-primary">Dodaj</button>
                </div>
                <div id="modal-loader" class="text-center p-3">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



 


