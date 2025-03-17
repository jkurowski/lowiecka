@section('meta_title', '- ' . $cardTitle)
<!doctype html>
<html lang="pl">

<head>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>DeveloPro {{ $cardTitle }}</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>

<body class="lang-pl">
    <div id="root"></div>

    <script>
        @php
            function isPreviousPageInvestmentGeneratorCreate($previousUrl)
            {
                $previousUrl = url()->previous();
                if (preg_match('/\/admin\/email\/generator\/(\d+)\/create/', $previousUrl, $matches)) {
                    $number = intval($matches[1]);
                    $isGreaterThanZero = $number > 0;
                    return $isGreaterThanZero;
                }

                return false;
            }

            function isPreviousPageWithNewsletterCreate($previousUrl)
            {
                $previousUrl = url()->previous();
                if (preg_match('/\/admin\/email\/generator\/(\d+)\/create/', $previousUrl, $matches)) {
                    $number = intval($matches[1]);
                    $isZero = $number == 0;
                    return $isZero;
                }
                return false;
            }
            $backURL = '';

            if (isPreviousPageInvestmentGeneratorCreate(url()->previous())) {
                $backURL = route('admin.email.generator.index', ['investment_id' => $investment_id]);
            } elseif (isPreviousPageWithNewsletterCreate(url()->previous())) {
                $backURL = route('admin.mass-mail.newsletter-templates');
            } elseif (url()->previous() == route('admin.crm.offer.templates.create')) {
                $backURL = route('admin.crm.offer.templates');
            } else {
                $backURL = url()->previous();
            }
        @endphp
        window.email = window.email || {};
        window.email.generator = window.email.generator || {};

        window.email.generator.offerLink = 'link do oferty'
        window.email.generator.templateJSON = @json($template_json);
        window.email.generator.csrf = '{{ csrf_token() }}';
        window.email.generator.templateID = '{{ $id }}';
        window.email.generator.allFilesURL = '{{ route('files.index') }}';
        window.email.generator.searchFileURL = '{{ route('files.search') }}';
        window.email.generator.clientPanelLink = '{{ route('front.login') }}';
        window.email.generator.preview =
            '{{ route('front.email-template-preview.show', ['investment_id' => $investment_id, 'id' => $id]) }}';

        window.email.generator.backURL = '{{ $backURL }}';
        window.email.generator.filesURL = '{{ route('admin.file.index') }}';

        window.email.generator.updateTemplate =
            '{{ route('admin.email.generator.update-template', ['investment_id' => $investment_id]) }}';
        window.email.generator.templateType = '{{ $template_type }}';
    </script>


</body>
<script src="{{ asset('js/template-generator.js') }}" type="module"></script>

</html>
