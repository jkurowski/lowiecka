@props([
    'pageTitle' => $pageTitle ?? 'Kontakt',
    'investmentName' => $investmentName ?? null,
    'investmentId' => $investmentName ?? null,
    'propertyId' => $propertyId ?? null,
    'back' => $back ?? false,
    'method' => 'POST'
])

@if($propertyId)
    <form id="contact-form" autocomplete="off" action="" method="{{ $method }}" class="contact-form validateForm">
        @csrf
        @else
            <form id="contact-form" autocomplete="off" action="{{ route('front.contact.send') }}" method="post" class="validateForm">
                @endif
                @csrf
                @if($investmentId)
                    <input name="investment_id" type="hidden" value="{{ $investmentId }}">
                @endif

                @if($investmentName)
                    <input name="investment_name" type="hidden" value="{{ $investmentName }}">
                @endif

                @if($back)
                    <input name="back" type="hidden" value="{{ $back }}">
                @endif

                <input type="hidden" name="page" value="{{ $pageTitle }}">
                <div class="row">
                    <div class="col-12">
                        @if (session('success'))
                            <div class="alert alert-success border-0 mb-3">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-warning border-0 mb-3">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="list-unstyled">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating mb-4">
                            <input placeholder="Imię"
                                   name="name"
                                   id="form_name"
                                   class="validate[required] form-control @error('form_name') is-invalid @enderror"
                                   type="text"
                                   value="{{ old('form_name') }}">
                            <label for="form_name">Imię<span class="text-danger">*</span></label>

                            @error('form_name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating mb-4">
                            <input placeholder="Nazwisko"
                                   name="lastname"
                                   id="form_lastname"
                                   class="form-control @error('form_lastname') is-invalid @enderror"
                                   type="text"
                                   value="{{ old('form_lastname') }}">
                            <label for="form_lastname">Nazwisko</label>

                            @error('form_name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating mb-4">
                            <input placeholder="Adres e-mail"
                                   name="email"
                                   id="form_email"
                                   class="validate[required] form-control @error('form_email') is-invalid @enderror"
                                   type="text"
                                   value="{{ old('form_email') }}">
                            <label for="form_email">Adres e-mail <span class="text-danger">*</span></label>

                            @error('form_email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-floating mb-4">
                            <input placeholder="Telefon"
                                   name="phone"
                                   id="form_phone"
                                   class="validate[required] form-control @error('form_phone') is-invalid @enderror"
                                   type="text"
                                   value="{{ old('form_phone') }}">
                            <label for="form_phone">Telefon <span class="text-danger">*</span></label>

                            @error('form_phone')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating mb-4">
                            <textarea placeholder="Wiadomość"
                                      rows="5"
                                      cols="1"
                                      name="message"
                                      id="form_message"
                                      class="validate[required] form-control @error('form_message') is-invalid @enderror">{{ old('form_message') }}</textarea>
                            <label class="align-items-start" for="form_message">Wiadomość <span class="text-danger">*</span></label>
                            @error('form_message')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    @isset($rules)
                        @foreach ($rules as $r)
                            <div class="form-check @error('rule_'.$r->id) is-invalid @enderror">
                                <input class="form-check-input @if($r->required === 1) validate[required] @endif" type="checkbox" value="1" id="rule_{{$r->id}}" name="rule_{{$r->id}}" data-prompt-position="topLeft:70px">
                                <label class="form-check-label" for="rule_{{$r->id}}">
                                    {!! $r->text !!}
                                    @error('rule_'.$r->id)
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </label>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="col-12 text-center text-md-start pt-md-3">
                    <script type="text/javascript">
                        document.write("<button type=\"submit\" class=\"g-recaptcha bttn\" data-sitekey=\"{{ config('services.recaptcha_v3.siteKey') }}\" data-callback=\"onRecaptchaSuccess\" data-action=\"submitContact\" style=\"min-width: revert;\">WYŚLIJ WIADOMOŚĆ</button>");
                    </script>
                    <noscript>Do poprawnego działania, Java musi być włączona.</noscript>
                </div>
            </form>

            @push('scripts')
                <script src="{{ asset('js/validation.js') }}" charset="utf-8"></script>
                <script src="{{ asset('js/pl.js') }}" charset="utf-8"></script>
                <script src="https://www.google.com/recaptcha/api.js"></script>
                <script type="text/javascript">
                    $(document).ready(function(){
                        $(".validateForm").validationEngine({
                            validateNonVisibleFields: true,
                            updatePromptsPosition:true,
                            promptPosition : "topRight:-154px",
                            autoPositionUpdate: false
                        });
                    });

                    function onRecaptchaSuccess(token) {
                        $(".validateForm").validationEngine('updatePromptsPosition');
                        const isValid = $(".validateForm").validationEngine('validate');
                        if (isValid) {
                            $("#contact-form").submit();
                        } else {
                            grecaptcha.reset();
                        }
                    }

                    @if (session('success') || session('warning') || $errors->any())
                    $(window).on('load', function () {
                        const aboveHeight = $('header').outerHeight();
                        $('html, body').stop().animate({
                            scrollTop: $('.validateForm').offset().top - aboveHeight - 80
                        }, 1000, 'easeInOutExpo');
                    });
                    @endif
                </script>
    @endpush


