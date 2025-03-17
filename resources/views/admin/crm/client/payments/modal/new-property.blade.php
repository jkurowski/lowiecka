<div class="modal fade" id="propertyModal" tabindex="-1" aria-labelledby="propertyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content position-relative">
            <form action="" method="post" id="modalForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="propertyModalLabel">Dodaj mieszkanie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fe-x"></i></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger" style="display:none"></div>
                    <div class="modal-form container">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group row">
                                    <div class="col-6">
                                        <div class="row">
                                            <label for="inputInvestment" class="col-123 col-form-label control-label required justify-content-start">Inwestycja
                                                <span class="text-danger d-inline w-auto ps-1">*</span>
                                            </label>
                                            <div class="col-12">
                                                <select class="form-select" id="inputInvestment" name="investment_id" onchange="fetchInvestmentProperties()">
                                                    <option value="0">Inwestycja</option>
                                                    @foreach($investments as $i)
                                                        <option value="{{ $i->id }}">{{ $i->name }}</option>
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
                                            <label for="inputProperty" class="col-12 col-form-label control-label required justify-content-start">Mieszkanie</label>
                                            <div class="col-12">
                                                <select class="form-control selectpicker" data-live-search="true" name="property_id" id="inputProperty">
                                                    <option value="0">Wybierz opcje</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8 mt-4">
                                <div class="form-group row d-none" id="formStatusInput">
                                    @include('form-elements.html-select', [
                                        'label' => 'Status',
                                        'name' => 'status',
                                        'selected' => '',
                                        'select' => \App\Helpers\RoomStatusMaper::getForClient()
                                    ])
                                </div>
                            </div>
                            <div class="col-4 d-none mt-4" id="formSaleInput">
                                @include('form-elements.html-input-date', ['label' => 'Data sprzedaży', 'sublabel'=> '', 'name' => 'saled_at', 'value' => ''])
                            </div>
                            <div class="col-4 d-none mt-4" id="formReservationInput">
                                @include('form-elements.html-input-date', ['label' => 'Data zakończenia rezerwacji', 'sublabel'=> '', 'name' => 'reservation_date'])
                            </div>
                            <div class="col-12" id="statusAlertPlaceholder"></div>
                        </div>
                    </div>
                </div>
                <div id="modalSpiner" class="text-center d-none"><div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
                    <button type="submit" class="btn btn-primary">Zapisz</button>
                </div>
            </form>
        </div>
    </div>
</div>
