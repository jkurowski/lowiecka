@props(['action' => null, 'method' => 'post', 'absence' => null, 'users' => []])
<form action="{{ $action }}" method="{{ $method === 'GET' ? 'GET' : 'POST' }}" id="absence-form">
    @csrf
    @method($method)
    <div class="row row-gap-3">
        <div class="col-12">
            @include('form-elements.searchable-select', [
                'name' => 'user_id',
                'placeholder' => 'Wybierz pracownika',
                'selected' => $absence->user_id ?? old('user_id'),
                'options' => $users,
                'required' => true,
                'label' => 'Pracownik',
            ])
        </div>
        <div class="col-md-6">
            <div class="form-control border-0 p-0">
                <label for="start_date" class="form-label fs-6">Data rozpoczęcia <span class="text-danger">*</span></label>
                <input type="datetime-local" name="start_date" required id="start_date" class="form-control"
                    value="{{ old('start_date', $absence->start_date ?? '') }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-control border-0 p-0">
                <label for="end_date" class="form-label fs-6">Data zakończenia <span
                        class="text-danger">*</span></label>
                <input type="datetime-local" name="end_date" required id="end_date" class="form-control"
                    value="{{ old('end_date', $absence->end_date ?? '') }}">
            </div>
        </div>
        <div class="col-12">
            <div class="form-control border-0 p-0">
                <label for="reason" class="form-label fs-6">Powód <span class="text-danger">*</span></label>
                <textarea name="reason" id="reason" required class="form-control" rows="3">{{ old('reason', $absence->reason ?? '') }}</textarea>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group-submit text-center">
                <button type="submit" class="btn btn-primary">
                    @if ($absence->id)
                        Zaktualizuj nieobecność
                    @else
                        Dodaj nieobecność
                    @endif
                </button>
            </div>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        function validateDates(startDateInput, endDateInput) {
            if (startDateInput.value && endDateInput.value) {
                const startDateTime = new Date(startDateInput.value);
                const endDateTime = new Date(endDateInput.value);
                if (startDateTime >= endDateTime) {
                    toastr.error('Data rozpoczęcia nie może być późniejsza lub ta sama jak data zakończenia');
                    endDateInput.value = '';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('start_date');
            const endDate = document.getElementById('end_date');

            startDate.addEventListener('change', () => validateDates(startDate, endDate));
            endDate.addEventListener('change', () => validateDates(startDate, endDate));
        });
    </script>
@endpush
