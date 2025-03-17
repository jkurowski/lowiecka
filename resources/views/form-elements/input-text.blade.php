{{-- [
    'label' => 'Label',
    'name' => 'input_name',
    'required' => null / 1,
    'value' => $form->value,
    'sublabel' => 'Sub-label'
] --}}

@props(['label', 'name', 'required' => null, 'value' => null, 'sublabel' => null, 'type' => 'text'])
<label for="form_{{ $name }}" class="col-12 col-form-label control-label pb-2">
    <div class="text-start w-100">{!! $label !!}
        @isset($required)
            <span class="text-danger d-inline">*</span>
        @endisset
        @isset($sublabel)
            <br><span>{!! $sublabel !!}</span>
        @endisset
    </div>
</label>
<div class="col-12 control-input d-flex align-items-center position-relative">
    <input type="{{ $type }}" id="form_{{ $name }}" value="{{ old($name, $value) }}"
        class="form-control @error($name) is-invalid @enderror" name="{{ $name }}"
        @if ($type == 'password') style="padding-right: 2.5em;" @endif
        type="text"@isset($required) required @endisset>
    @if ($errors->first($name))
        <div class="invalid-feedback d-block">{{ $errors->first($name) }}</div>
    @endif
    @if ($type == 'password')
        <span class="password-toggle position-absolute" style="right: 2em;">
            <i class="fe-eye"></i>
        </span>

        <script defer>
            const togglePassword = document.querySelectorAll('.password-toggle');
            if (togglePassword.length > 0) {
                togglePassword.forEach(item => {
                    item.addEventListener('click', function(e) {
                        const password = document.querySelector('#form_{{ $name }}');
                        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                        // toggle icon
                        item.children[0].classList.toggle('fe-eye');
                        item.children[0].classList.toggle('fe-eye-off');

                        password.setAttribute('type', type);
                    });
                });
            }
        </script>
    @endif
</div>
