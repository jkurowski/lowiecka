@extends('layouts.iframe')
@section('content')
    <div id="plan-holder">
        <x-iframes.filters :$uniqueRooms :areaRange="$investment->area_range" />
    </div>

    <x-iframes.properties-list :$investment :$properties />
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
            // $(".validateForm").validationEngine({
            //     validateNonVisibleFields: true,
            //     updatePromptsPosition: true,
            //     promptPosition: "topRight:-137px"
            // });
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