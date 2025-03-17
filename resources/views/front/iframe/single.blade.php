@extends('layouts.iframe')
@section('content')
    <div id="property">
        <div class="container">
            <div class="row">
                <div class="col-12 col-xl-5">
                    <div class="property-desc">
                        <a href="{{ route('front.iframe.withContactForm', 'ruczaj-korso-iii') }}" class="bttn">WRÓĆ DO LISTY</a>

                        <h1 class="mb-4">{{ $property->name }}</h1>

                        <div class="room-status room-status-{{ $property->status }}">
                            {{ roomStatus($property->status) }}
                        </div>
                        @if($property->status == 1)
                            @if($property->highlighted)
                                @if($property->price_brutto)<s class="text-muted">@money($property->price_brutto)</s>@endif
                                @if($property->promotion_price)<h6 class="propertyPrice">@money($property->promotion_price)</h6>@endif
                            @else
                                @if($property->price_brutto)
                                    <h6 class="propertyPrice">@money($property->price_brutto)</h6>
                                @endif
                            @endif
                        @endif

                        <ul class="list-unstyled">
                            <li>Pokoje:<span>{{ $property->rooms }}</span></li>
                            <li>Powierzchnia:<span>{{ $property->area }} m<sup>2</sup></span></li>
                            @if ($property->garden_area)
                                <li>Ogródek:<span>{{ $property->garden_area }} m<sup>2</sup></span></li>
                            @endif
                            @if ($property->balcony_area)
                                <li>Balkon:<span>{{ $property->balcony_area }} m<sup>2</sup></span></li>
                            @endif
                            @if ($property->balcony_area_2)
                                <li>Balkon 2:<span>{{ $property->balcony_area_2 }} m<sup>2</sup></span></li>
                            @endif
                            @if ($property->terrace_area)
                                <li>Taras:<span>{{ $property->terrace_area }} m<sup>2</sup></span></li>
                            @endif
                            @if ($property->loggia_area)
                                <li>Loggia:<span>{{ $property->loggia_area }} m<sup>2</sup></span></li>
                            @endif
                            @if ($property->parking_space)
                                <li>Miejsce postojowe:<span>{{ $property->parking_space }}</span></li>
                            @endif
                            @if ($property->garage)
                                <li>Garaż:<span>{{ $property->garage }}</span></li>
                            @endif
                        </ul>

                        @if ($property->file_pdf)
                            <a href="{{ asset('/investment/property/pdf/' . $property->file_pdf) }}" target="_blank" class="bttn">POBIERZ PLAN .PDF</a>
                        @endif
                    </div>

                    <div class="property-img">
                        @if ($property->file)
                            <a href="{{ asset('/investment/property/' . $property->file) }}" class="swipebox" target="_top">
                                <picture>
                                    <source type="image/webp"
                                        srcset="{{ asset('/investment/property/thumbs/webp/' . $property->file_webp) }}">
                                    <source type="image/jpeg"
                                        srcset="{{ asset('/investment/property/thumbs/' . $property->file) }}">
                                    <img src="{{ asset('/investment/property/thumbs/' . $property->file) }}"
                                        alt="{{ $property->name }}">
                                </picture>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-xl-7 ps-3 ps-xl-5">
                    <div id="property-form">
                        <div class="container">
                            <div class="row d-flex">
                                <div class="col-12">
                                    <div id="form-messages" class='mt-3'></div>
                                    <form method="post" id="contact-form"
                                        action="{{ route('front.iframe.single.contact', $property->id) }}" class="validateForm">
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-12 form-input">
                                                <label for="form_name">Imię <span class="text-danger">*</span></label>
                                                <input name="name" id="form_name"
                                                    class="validate[required] form-control @error('name') is-invalid @enderror"
                                                    type="text" value="{{ old('name') }}">
                                            </div>
                                            <div class="col-12 col-sm-6 form-input">
                                                <label for="form_email">E-mail <span class="text-danger">*</span></label>
                                                <input name="email" id="form_email"
                                                    class="validate[required] form-control @error('email') is-invalid @enderror"
                                                    type="text" value="{{ old('email') }}">
                                            </div>
                                            <div class="col-12 col-sm-6 form-input">
                                                <label for="form_phone">Telefon <span class="text-danger">*</span></label>
                                                <input name="phone" id="form_phone"
                                                    class="validate[required] form-control @error('phone') is-invalid @enderror"
                                                    type="text" value="{{ old('phone') }}">
                                            </div>
                                            <div class="col-12 mt-1 form-input">
                                                <label for="form_message">Treść wiadomości <span
                                                        class="text-danger">*</span></label>
                                                <textarea rows="5" cols="1" name="message" id="form_message"
                                                    class="validate[required] form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                                            </div>
                                            <div class="col-12">
                                                @if($obligation)
                                                    <div class="rodo-obligation mt-3">
                                                        <p>{!! $obligation->obligation !!}</p>
                                                    </div>
                                                @endif
                                                <div class="rodo-rules">
                                                    @foreach ($rules as $r)
                                                        <div class="col-12 @error('rule_'.$r->id) is-invalid @enderror">
                                                            <div class="rodo-rule clearfix">
                                                                <input name="rule_{{$r->id}}" id="rule_{{$r->id}}" value="1" type="checkbox" @if($r->required === 1) class="validate[required]" @endif data-prompt-position="topLeft:0">
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
                                                    <input name="page" type="hidden" value="{{ $property->name }}">
                                                    <script type="text/javascript">
                                                        document.write("<button class=\"bttn\" type=\"submit\">WYŚLIJ WIADOMOŚĆ</button>");
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
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <link href="{{ asset('css/iframe/'.$investment->slug.'.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <script src="{{ asset('/js/validation.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/pl.js') }}" charset="utf-8"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', async () => {
            async function fetchToken(){
                const response = await fetch("{{ route('front.iframe.token') }}")
                const data = await response.json();
                return data.token;
            }
            const token  = await fetchToken();
            const form = document.getElementById('contact-form');
            form.addEventListener('submit', (e) => {
                e.preventDefault();

                const formData = new FormData(form);
                
                fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': token
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        const formMessagesDiv = document.getElementById('form-messages');
                        const clearErrors = () => {
                            const errorElements = document.querySelectorAll('.alert.alert-danger.border-0');
                            errorElements.forEach(element => element.remove());
                        };

                        if (data.status === 'success') {
                            console.log("success");

                            clearErrors();
                            form.reset();
                            formMessagesDiv.innerHTML = `<div class="alert alert-success border-0">${data.message}</div>`;
                        } else {
                            formMessagesDiv.innerHTML = '';

                            clearErrors();

                            if (data.data) {
                                const errors = data.data;

                                Object.keys(errors).forEach(field => {
                                    const fieldErrors = errors[field];

                                    fieldErrors.forEach(errorMessage => {
                                        const errorElement = document.createElement('div');
                                        errorElement.classList.add('alert', 'alert-danger', 'border-0');
                                        errorElement.innerHTML = errorMessage;

                                        const fieldElement = document.querySelector(`[name="${field}"]`);
                                        if (fieldElement) {
                                            fieldElement.insertAdjacentElement('afterend', errorElement);
                                        } else {
                                            formMessagesDiv.appendChild(errorElement);
                                        }
                                    });
                                });
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        })

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
    @if (isset($custom_css))
        <style>
            {{ $custom_css }}
        </style>
    @endif
@endpush

