@isset($sublabel)
    {!! Form::label($name, '<div class="text-right">'.$label.' <span class="text-danger d-inline">*</span><br><span>'.$sublabel.'</span></div>', ['class' => 'col-12 col-form-label control-label pb-2 required'], false) !!}
@else
    {!! Form::label($name, '<div class="text-right">'.$label.' <span class="text-danger d-inline">*</span></div>', ['class' => 'col-12 col-form-label control-label pb-2 required'], false) !!}
@endif

<div class="@isset($class) {{ $class }} @else {{ 'col-12 control-input position-relative d-flex align-items-center' }} @endisset">
    @isset($selected)
        {!! Form::select($name.'[]', $select, $selected, array('class' => 'form-control selectpicker', 'multiple')) !!}
    @else
        {!! Form::select($name.'[]', $select, [], array('class' => 'form-control selectpicker', 'multiple')) !!}
    @endif
    @if($errors->first($name))<div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>@endif
</div>
@push('scripts')
<link href="{{ asset('/js/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
<script src="{{ asset('/js/bootstrap-select/bootstrap-select.min.js') }}" charset="utf-8"></script>
@endpush