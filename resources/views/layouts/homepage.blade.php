<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {!! settings()->get("scripts_head") !!}

    <title>{{ settings()->get("page_title") }}</title>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ settings()->get("page_description") }}">
    <meta name="robots" content="{{ settings()->get("page_robots") }}">
    <meta name="author" content="{{ settings()->get("page_author") }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/styles.min.css') }}?v=1802v1">
    <link rel="stylesheet" href="{{ asset('/css/slick.min.css') }}">

    <link rel="shortcut icon" href="/uploads/{{ settings()->get("page_favicon") }}">

    <!-- Preloads -->
    <link rel="preload" href="{{ asset('/images/logo.svg') }}" as="image" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- /Preloads -->

    <!-- Delaying the execution of scripts -->
    <style>fscript {display: none !important;}</style>
    <script>let scriptsExecuted=!1;const head=document.getElementsByTagName("head")[0]||document.documentElement,autoLoad=setTimeout(initScripts,3e3);function executeScripts(){var t=document.querySelectorAll("fscript");[].forEach.call(t,function(t){var e=document.createElement("script");if(e.type="text/javascript",t.hasAttributes())for(var i in t.attributes)t.attributes.hasOwnProperty(i)&&(e[t.attributes[i].name]=t.attributes[i].value||!0);else e.appendChild(document.createTextNode(t.innerHTML));head.insertBefore(e,head.firstChild)})}function initScripts(){!scriptsExecuted&&(clearTimeout(autoLoad),scriptsExecuted=!0,setTimeout(function(){"requestIdleCallback"in window?requestIdleCallback(executeScripts,{timeout:100}):executeScripts()},1e3))}window.addEventListener("scroll",function(){initScripts()},!1),document.onclick=function(){initScripts()};</script>
    <!-- /Delaying the execution of scripts -->

    @stack('style')
</head>
<body class="{{ !empty($body_class) ? $body_class : 'homepage' }}">
{!! settings()->get("scripts_afterbody") !!}

@include('layouts.partials.header')

@yield('content')

@include('layouts.partials.footer')

<!-- JS scripts -->
@stack('scripts')

@if (settings()->get('popup_status') == 77)
    <div class="modal" tabindex="-1" id="popModal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {!! settings()->get('popup_text') !!}
                </div>
            </div>
        </div>
    </div>
@endif
<script type="text/javascript">
    $(document).ready(function() {
        @if (settings()->get('popup_status') == 77)
        const popModal = new bootstrap.Modal(document.getElementById('popModal'), {
            keyboard: false
        });
        @endif

        @if ($popup == 77)
        popModal.show();
        setTimeout(function() {
            popModal.hide();
        }, {{ settings()->get('popup_timeout') }});
        @endif

        $("#slick-fluid .row").slick({
            centerMode: true,
            slidesToShow: 3,
        });
    });
</script>

{!! settings()->get("scripts_beforebody") !!}
</body>
</html>
