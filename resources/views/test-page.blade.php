@extends('layouts.registerform')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div id="logo">
                    <img src="{{ asset('images/logo.svg') }}" alt="Logo" width="262" height="87" class="img-fluid header-logo" loading="eager">
                </div>
            </div>
            <div class="col-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2 py-5">
                <div class="form-bg">
                    <h1 class="text-center">Formularz kontaktowy</h1>
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form method="post" id="contact-form" action="{{route('front.test-page.store')}}" class="validateForm container-fluid">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-12 col-sm-6 form-input">
                                <label for="form_name">Imię <span class="text-danger">*</span></label>
                                <input name="name"
                                       id="form_name"
                                       class="validate[required] shadow-none form-control @error('name') is-invalid @enderror"
                                       type="text"
                                       value="{{ old('name') }}">
                            </div>
                            <div class="col-12 col-sm-6 form-input">
                                <label for="form_surname">Nazwisko <span class="text-danger">*</span></label>
                                <input name="surname"
                                       id="form_surname"
                                       class="validate[required] shadow-none form-control @error('surname') is-invalid @enderror"
                                       type="text"
                                       value="{{ old('surname') }}">
                            </div>
                            <div class="col-12 col-sm-6 form-input">
                                <label for="form_email">E-mail <span class="text-danger">*</span></label>
                                <input name="email"
                                       id="form_email"
                                       class="validate[required] shadow-none form-control @error('email') is-invalid @enderror"
                                       type="text"
                                       value="{{ old('email') }}">
                            </div>
                            <div class="col-12 col-sm-6 form-input">
                                <label for="form_phone">Telefon <span class="text-danger">*</span></label>
                                <input name="phone"
                                       id="form_phone"
                                       class="validate[required] shadow-none form-control @error('phone') is-invalid @enderror"
                                       type="text"
                                       value="{{ old('phone') }}">
                            </div>
                            <div class="col-12">
                                <div class="rodo-rules">
                                    @foreach ($rules as $r)
                                        <div class="col-12 @error('rule_'.$r->id) is-invalid @enderror mt-3">
                                            <div class="rodo-rule d-flex align-items-start">
                                                <input name="rule_{{$r->id}}" id="rule_{{$r->id}}" value="1" type="checkbox" @if($r->required === 1) class="validate[required] mt-2 me-3" @endif data-prompt-position="topLeft:0">
                                                <label for="rule_{{$r->id}}" class="rules-text">
                                                    {!! $r->text !!}
                                                    @error('rule_'.$r->id)
                                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="row row-form-submit">
                            <div class="col-12 pt-3">
                                <div class="input text-center">
                                    <script type="text/javascript">
                                        document.write("<button class=\"btn btn-primary shadow-none\" type=\"submit\">Zapisz dane</button>");
                                    </script>
                                    <noscript>Do poprawnego działania, Java musi być włączona.</noscript>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <script src="{{ asset('/js/validation.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/pl.js') }}" charset="utf-8"></script>
    <script type="text/javascript">


        $(document).ready(function() {
            $(".validateForm").validationEngine({
                validateNonVisibleFields: true,
                updatePromptsPosition: true,
                promptPosition: "topRight:-137px"
            });
        });
        @if (session('success') || session('warning'))
            $(window).load(function() {
                const aboveHeight = $('header').outerHeight();
                $('html, body').stop().animate({
                    scrollTop: $('.alert').offset().top - aboveHeight
                }, 1500, 'easeInOutExpo');
            });
        @endif
    </script>
@endpush

