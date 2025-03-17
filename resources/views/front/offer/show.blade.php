<!DOCTYPE html>
<html>
<head>
    <title>Oferta numer: {{ $offer->id }} z dnia {{ date('Y-m-d', strtotime($offer->created_at)) }}</title>
    <meta robots="noindex, nofollow">
    <!-- Styles -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/offert/offer.min.css') }}" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

</head>
<body>
<div id="page-content">
    @if (!$offer->is_new_client)
        <div class="container">
            <div class="row">
                <div class="col-12 mt-5">
                    @if (session('success'))
                        <div class="alert alert-success mb-3">{{ session('success') }}</div>
                    @endif
                    <h1>Oferta numer: {{ $offer->id }} z dnia {{ date('Y-m-d', strtotime($offer->created_at)) }}</h1>
                    <hr>
                    {!! $offer->message !!}
                </div>
            </div>
        </div>
        @if ($selectedOffer)
            @include('front.offer.property_list', ['properties' => $selectedOffer])
        @endif
    @else
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                    <div class="py-5">
                        @include('front.offer.new-client-form')
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
</body>
</html>