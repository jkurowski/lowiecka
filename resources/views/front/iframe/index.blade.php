@extends('layouts.iframe')
@section('content')
    <style>
        html {
            --bg-color: {{ $iframeSettings['bg_color'] }};
            --box-offer-title-font-size: {{ $iframeSettings['box_offer_title_font_size'] }}px;
            --text-color: {{ $iframeSettings['text_color'] }};
            --font-family: {{ $iframeSettings['font_family'] }}, sans-serif;
            --font-size: {{ $iframeSettings['font_size'] }}px;
            --link-color: {{ $iframeSettings['link_color'] }};
            --link-hover-color: {{ $iframeSettings['link_hover_color'] }};
            --box-offer-bg-color: {{ $iframeSettings['box_offer_bg_color'] }};
            --box-offer-margin: {{ $iframeSettings['box_offer_margin'] }};
            --box-offer-padding: {{ $iframeSettings['box_offer_padding'] }};
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: var(--font-family);
            font-size: var(--font-size);
        }

        a {
            color: var(--link-color);
        }

        a:hover {
            color: var(--link-hover-color);
        }

        .property-list-item {
            background-color: var(--box-offer-bg-color);
            margin: var(--box-offer-margin);
            padding: var(--box-offer-padding);
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .property-list-item h2 {
            font-size: var(--box-offer-title-font-size);
            font-weight: 600;
        }

        {{ $iframeSettings['custom_css'] }}
    </style>

    @if ($investment->show_properties == 1)
        @if ($investment->plan)
            <div id="plan-holder">
                <div class="plan-holder-info">Z planu budynku wybierz piętro lub <a href="#filtr" class="scroll-link"
                        data-offset="90"><b>użyj wyszukiwarki</b></a></div>
                <img src="{{ asset('/investment/plan/' . $investment->plan->file) }}" alt="{{ $investment->name }}"
                    id="invesmentplan" usemap="#invesmentplan">

                <x-iframes.filters :$uniqueRooms :areaRange="$investment->area_range" />
                <x-iframes.sort />

                @switch($investment->type)
                    @case(2)
                        <x-iframes.maps.type2 :$investment />
                    @break

                    @case(3)
                        <x-iframes.maps.type3 :$investment />
                    @break

                    @default
                @endswitch
            </div>
        @endif
    @endif

    <x-iframes.properties-list :$investment :$properties />
@endsection
