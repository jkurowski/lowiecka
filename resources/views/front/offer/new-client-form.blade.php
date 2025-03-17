<form action="{{ route('front.store-client', $offer) }}" method="post" id="modalForm">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title" id="portletModalLabel">Jestem nowym klientem</h5>
    </div>
    <div class="modal-body">
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
                                class="validate[required] form-control @error('lastname') is-invalid @enderror"
                                id="inputSurname" name="lastname" value="{{ $entry->lastname }}" required>
                            @if ($errors->first('lastname'))
                                <div class="invalid-feedback d-block">{{ $errors->first('lastname') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-6 mt-3">
                    <div class="row">
                        <label for="inputEmail"
                            class="col-12 col-form-label control-label required justify-content-start">E-mail<span
                                class="text-danger d-inline w-auto ps-1">*</span>
                        </label>
                        <div class="col-12">
                            <input type="text"
                                class="validate[required] form-control @error('email') is-invalid @enderror"
                                id="inputEmail" name="email" value="{{ $entry->email }}" required>
                            @if ($errors->first('email'))
                                <div class="invalid-feedback d-block">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-2 mt-3 d-none">
                    <label for=""
                        class="col-12 col-form-label control-label required mb-2 justify-content-start">&nbsp;</label>
                    <div class="col-12 text-center">
                        <p style="font-size: 17px"><i class="las la-arrow-left"></i> lub <i
                                class="las la-arrow-right"></i></p>
                    </div>
                </div>
                <div class="col-6 mt-3">
                    <div class="row">
                        <label for="inputPhone"
                            class="col-12 col-form-label control-label required justify-content-start">Telefon<span
                                class="text-danger d-inline w-auto ps-1">*</span></label>
                        <div class="col-12">
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                id="inputPhone" name="phone" value="{{ $entry->phone }}">
                            @if ($errors->first('phone'))
                                <div class="invalid-feedback d-block">{{ $errors->first('phone') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-5 mt-3 d-none">
                    <div class="row">
                        <label for="inputInvestment"
                            class="col-12 col-form-label control-label required justify-content-start">Inwestycja</label>
                        <div class="col-12">
                            <select class="form-select" id="inputInvestment" name="investment_id">
                                <option value="">Nazwa inwestycji</option>
                                <option value="">Nazwa inwestycji</option>
                                <option value="">Nazwa inwestycji</option>
                                <option value="">Nazwa inwestycji</option>
                                <option value="">Nazwa inwestycji</option>
                            </select>
                        </div>
                    </div>
                </div>
 
                @if ($obligation)
                    <p class='mt-3'>{!! $obligation->obligation !!}</p>
                @endif

                @foreach ($required_rodo_rules as $r)
                    <div class="mt-3 col-12 @error('rule' . $r->id) is-invalid @enderror">
                        <div class="form-check">
                            <input name="rule{{ $r->id }}" id="rule{{ $r->id }}" value="1"
                                type="checkbox" @if ($r->required === 1) class="validate[required] form-check-input" @endif
                                data-prompt-position="topLeft:0">
                            <label for="rule{{ $r->id }}" class="rules-text ms-2 form-check-label">
                                {!! $r->text !!}
                                @error('rule' . $r->id)
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
    <div class="text-center">
        <button type="submit" class="btn btn-primary">Wyślij</button>
    </div>
</form>
