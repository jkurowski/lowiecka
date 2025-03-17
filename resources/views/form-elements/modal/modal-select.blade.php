<div class="form-group row">
    <label
            for="input{{ $name }}"
            class="col-3 col-form-label control-label @isset($required) required @endisset text-end">{{ $label }}
        @isset($required) <span class="text-danger d-inline w-auto ps-1">*</span> @endisset
    </label>

    <div class="col-12 control-input position-relative d-flex align-items-center flex-column">
        @if(isset($selected))
            {!! Form::select($name, $select, $selected, array('class' => 'form-select', 'id' => $name.'Select', $disabled ?? null)) !!}
        @else
            {!! Form::select($name, $select, [], array('class' => 'form-select', 'id' => $name.'Select', $disabled ?? null)) !!}
        @endif
        @if($errors->first($name))<div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>@endif
    </div>
</div>