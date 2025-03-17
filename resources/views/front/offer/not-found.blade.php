@extends('layouts.page', ['body_class' => 'homepage'])

@section('content')
    <div id="page-content" class="offer-not-found">
        <div class="container">
            <div class="error-content text-center py-5">
                <h1 class="error-title mb-4">{{ __('Oferta wygasła') }}</h1>
                
                <div class="error-message mb-4">
                    <p>{{ __('Przepraszamy, oferta, której szukasz, nie jest już dostępna lub została usunięta.') }}</p>
                </div>


            </div>
        </div>
    </div>
@endsection
