<form id="modalForm">
    @csrf
    <div class="modal-header">
        <h5 class="modal-title">Dodaj zadanie</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fe-x"></i></button>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger" style="display:none"></div>
        <div class="modal-form container">
            <div class="form-group row">
                <div class="col-6">
                    <div class="row">
                        <label for="inputDate" class="col-12 col-form-label control-label required mb-2 justify-content-start">Termin zakończenia zadania</label>
                        <div class="col-12">
                            <input type="text"
                                   value="{{ $task->due_date ?? '' }}"
                                   class="validate[required] form-control @error('start') is-invalid @enderror"
                                   id="inputDate"
                                   name="due_date">
                            @if($errors->first('due_date'))
                                <div class="invalid-feedback d-block">{{ $errors->first('due_date') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="row">
                        <label for="inputStatus" class="col-12 col-form-label control-label required mb-2 justify-content-start">Status zadania</label>
                        <div class="col-12">
                            <select class="form-select" id="inputStatus" name="completed">
                                <option value="0" {{ isset($task) && $task->completed == 0 ? 'selected' : '' }}>Nowe</option>
                                <option value="1" {{ isset($task) && $task->completed == 1 ? 'selected' : '' }}>Zakończone</option>
                            </select>
                            @if($errors->first('completed'))
                                <div class="invalid-feedback d-block">{{ $errors->first('completed') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-12">
                    <div class="row">
                        <label for="inputNote" class="col-12 col-form-label control-label required mb-2 justify-content-start">Opis zadania</label>
                        <div class="col-12">
                                        <textarea name="text"
                                                  cols="30"
                                                  rows="5"
                                                  class="form-control @error('note') is-invalid @enderror"
                                                  id="inputNote"
                                                  style="resize: none">{{ $task->text ?? '' }}</textarea>
                            @if($errors->first('text'))
                                <div class="invalid-feedback d-block">{{ $errors->first('text') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
        <button type="submit" class="btn btn-primary">Zapisz</button>
    </div>
</form>
