<!-- searchable-single-select.blade.php -->
@props([
    'name',
    'options',
    'selected' => null,
    'placeholder' => 'Select an option',
    'id' => uniqid('searchable-select-'),
    'multiple' => false,
    'disabled' => false,
    'required' => false,
    'readonly' => false,
    'label' => false,
    'placeholder' => 'Wybierz opcję',
])

<div class="searchable-select">

    @if ($label)
        <label class="form-label fs-6" for="{{ $id }}">{{ $label }} @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    <select name="{{ $name }}" id="{{ $id }}" class="selectpicker form-control"
        style="--bs-dropdown-link-active-bg: #c0c0c0; --bs-dropdown-link-active-color: #fff;" title="{{ $placeholder }}"
        data-live-search="true" @if ($multiple) multiple @endif
        @if ($disabled) disabled @endif @if ($required) required @endif
        @if ($readonly) readonly @endif>
        @foreach ($options as $value => $optionLabel)
            <option data-tokens="{{ $optionLabel }}" value="{{ $value }}"
                @if ($selected == $value) selected @endif>{{ $optionLabel }}</option>
        @endforeach
    </select>

</div>

@push('scripts')
    <link href="{{ asset('/js/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
    <script src="{{ asset('/js/bootstrap-select/bootstrap-select.min.js') }}" charset="utf-8"></script>
@endpush
<style>
    .searchable-select .dropdown-item.active,
    .searchable-select .dropdown-item:active {
        background-color: #e5e5e5;
        color: #000;
    }
</style>
