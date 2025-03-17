@php
    $subLabel = isset($sublabel) ? '<span>' . $sublabel . '</span>' : '';
    $labelClass = 'col-12 col-form-label control-label pb-2';
    $inputClass = 'form-control' . (isset($inputclass) ? ' ' . $inputclass : '');
    $divClass = $class ?? 'col-12 control-input position-relative d-flex align-items-center';
    $required = isset($required) && $required;
    $readonly = isset($readonly) && $readonly;
    $additionalAttributes = [];
    if (isset($pattern)) {
        $additionalAttributes['pattern'] = $pattern;
    }
@endphp

{!! Form::label(
    $name,
    '<div class="text-start">' . $label . ($required ? ' <span class="text-danger d-inline">*</span>' : '') . $subLabel . '</div>',
    ['class' => $labelClass . ($required ? ' required' : '')],
    false
) !!}

<div class="{{ $divClass }}">
    {!! Form::text(
        $name,
        old($name, $value),
        array_merge(
            ['class' => $inputClass],
            ($required ? ['required' => 'required'] : []),
            $additionalAttributes,
            ($readonly ? ['readonly' => 'readonly'] : [])
        )
    ) !!}
</div>
@if($errors->first($name))
    <div class="col-12 col-form-label control-label pb-2"></div>
    <div class="col-12 control-input invalid-feedback d-block">{{ $errors->first($name) }}</div>
@endif