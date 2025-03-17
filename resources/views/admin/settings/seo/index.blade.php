@extends('admin.settings.index')

@section('settings')
    <form method="POST" action="{{ route('admin.settings.seo.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="container-fluid p-0">
            <div class="card p-4">
                <div class="card-body p-3 control-col12">

                    <div class="row w-100 form-group">
                        @include('form-elements.html-input-text-count', [
                            'label' => 'Nazwa strony',
                            'sublabel' => 'Meta tag - title / ilość znaków: 50 - 60',
                            'name' => 'page_title',
                            'value' => settings()->get('page_title'),
                            'maxlength' => 255,
                            'required' => 1,
                        ])
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.html-input-text-count', [
                            'label' => 'Opis strony',
                            'sublabel' => 'Meta tag - description / ilość znaków: 120 - 158',
                            'name' => 'page_description',
                            'value' => settings()->get('page_description'),
                            'maxlength' => 255,
                            'required' => 1,
                        ])
                    </div>

                    <div class="row w-100">
                        @include('form-elements.input-text', [
                            'label' => 'Adres strony',
                            'sublabel' => 'URL strony',
                            'name' => 'page_url',
                            'value' => settings()->get('page_url'),
                            'required' => 1,
                        ])
                    </div>


                    <div class="row w-100 form-group">
                        <div class="col-12 col-form-label control-label"></div>
                        <div class="col-12 control-input">
                            <div class="btn btn-primary" id="pagespeed">PageSpeed Insights</div>
                            <div class="btn btn-primary" id="facebookcrawler">Facebook Crawler</div>
                        </div>
                    </div>

                    <div class="row w-100">
                        @include('form-elements.input-text', [
                            'label' => 'Adres e-mail',
                            'sublabel' => 'Adres e-mail do formularza kontaktowego',
                            'name' => 'page_email',
                            'value' => settings()->get('page_email'),
                            'required' => 1,
                        ])
                    </div>

                    <div class="row w-100">
                        <div class="col-12">
                            <div class="section">Podgląd wyszukiwarki Google</div>
                        </div>
                    </div>

                    <div class="row w-100 form-group">
                        <div class="col-12 col-form-label control-label"></div>
                        <div class="col-12 control-input" style="line-height: normal">
                            <h3
                                style="font-family: arial,sans-serif;font-size:18px;line-height: 1.2;margin:0;font-weight: normal;color:#1a0dab;">
                                {{ settings()->get('page_title') }}</h3>
                            <div style="line-height: 14px"><cite
                                    style="font-size:14px;line-height: 16px;color: #006621;font-style: normal;font-family: arial,sans-serif">{{ settings()->get('page_description') }}</cite>
                            </div>
                            <span
                                style="font-family: arial,sans-serif;font-size:13px;line-height:18px;color: #545454">{{ settings()->get('page_url') }}</span>
                        </div>
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', [
                            'label' => 'Autor strony',
                            'sublabel' => 'Meta tag - author',
                            'name' => 'page_author',
                            'value' => settings()->get('page_author'),
                        ])
                    </div>

                    <div class="row w-100 form-group">
                        <label for="page_robots" class="col-12 col-form-label control-label">
                            <div class="text-start w-100">
                                Indeksowanie<br><span>Meta tag - robots</span>
                            </div>
                        </label>
                        <div class="col-12 control-input d-flex align-items-center">
                            <select class="form-select" name="page_robots" id="page_robots">
                                <option value="noindex, nofollow"<?php if(settings()->get("page_robots") == 'noindex, nofollow'){?> selected<?php } ?>>noindex,
                                    nofollow</option>
                                <option value="index, follow" <?php if(settings()->get("page_robots") == 'index, follow'){?> selected<?php } ?>>index, follow
                                </option>
                                <option value="index, nofollow" <?php if(settings()->get("page_robots") == 'index, nofollow'){?> selected<?php } ?>>index,
                                    nofollow</option>
                                <option value="noindex, follow" <?php if(settings()->get("page_robots") == 'noindex, follow'){?> selected<?php } ?>>noindex,
                                    follow</option>
                            </select>
                        </div>
                    </div>

                    <div class="row w-100 form-group d-none">
                        @include('form-elements.html-input-file', [
                            'label' => 'Favicon',
                            'sublabel' => '(wymiary: max 200 px / max 200 px)',
                            'name' => 'page_favicon',
                            'file' => settings()->get('page_favicon'),
                            'file_preview' => 'uploads/',
                            'file_preview_style' => 'border:1px solid #d5d5d5;width:20px',
                        ])
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.textarea', [
                            'label' => 'Plik',
                            'sublabel' => '<code>robots.txt</code>',
                            'name' => 'robots_txt',
                            'value' => $robots,
                            'rows' => 6,
                        ])
                    </div>

                    <div class="row w-100">
                        <div class="col-12">
                            <div class="section">Dodatkowe ustawienia</div>
                        </div>
                    </div>

                    <div class="row w-100 form-group d-none">
                        @include('form-elements.html-input-file', [
                            'label' => 'Logo',
                            'sublabel' => '(wymiary: 40 px / 200 px)',
                            'name' => 'page_logo',
                            'value' => settings()->get('page_logo'),
                        ])
                    </div>

                    <div class="row w-100 form-group d-none">
                        @include('form-elements.input-text', [
                            'label' => 'Logo - atrybut ALT',
                            'name' => 'page_logo_alt',
                            'value' => settings()->get('page_logo_alt'),
                        ])
                    </div>

                    <div class="row w-100 form-group d-none">
                        @include('form-elements.input-text', [
                            'label' => 'Logo - atrybut Title',
                            'name' => 'page_logo_title',
                            'value' => settings()->get('page_logo_title'),
                        ])
                    </div>

                    <div class="row w-100 form-group d-none">
                        @include('form-elements.input-text', [
                            'label' => 'Klucz Google Maps',
                            'name' => 'google_maps_api',
                            'value' => settings()->get('google_maps_api'),
                        ])
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.textarea', [
                            'label' => 'Kod w <code>&lt;head&gt;</code>',
                            'sublabel' =>
                                'Kod zostanie wklejony po otworzeniu znacznika &lt;head&gt; na każdej podstronie.',
                            'name' => 'scripts_head',
                            'value' => settings()->get('scripts_head'),
                            'rows' => 7,
                        ])
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.textarea', [
                            'label' => 'Kod po otworzeniu <code>&lt;body&gt;</code>',
                            'sublabel' =>
                                'Kod zostanie wklejony po otworzeniu znacznika &lt;body&gt; na każdej podstronie.',
                            'name' => 'scripts_afterbody',
                            'value' => settings()->get('scripts_afterbody'),
                            'rows' => 7,
                        ])
                    </div>

                    <div class="row w-100 form-group">
                        @include('form-elements.textarea', [
                            'label' => 'Kod przed zamknięciem <code>&lt;body&gt;</code>',
                            'sublabel' =>
                                'Kod zostanie wklejony przed zamknięciem znacznika &lt;body&gt; na każdej podstronie.',
                            'name' => 'scripts_beforebody',
                            'value' => settings()->get('scripts_beforebody'),
                            'rows' => 7,
                        ])
                    </div>


                    <div class="row w-100 d-none">
                        <div class="col-12">
                            <div class="section">Google reCaptcha</div>
                        </div>
                    </div>

                    <div class="row w-100 form-group d-none">
                        @include('form-elements.input-text', [
                            'label' => 'Klucz witryny',
                            'name' => 'recaptcha_site_key',
                            'value' => settings()->get('recaptcha_site_key'),
                        ])
                    </div>

                    <div class="row w-100 form-group d-none">
                        @include('form-elements.html-input-password', [
                            'label' => 'Tajny klucz',
                            'name' => 'recaptcha_secret_key',
                            'value' => settings()->get('recaptcha_secret_key'),
                        ])
                    </div>

                    <div class="row w-100">
                        <div class="col-12">
                            <div class="section">SMS Api</div>
                        </div>
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', [
                            'label' => 'Token',
                            'name' => 'sms_api_token',
                            'value' => settings()->get('sms_api_token'),
                        ])
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', [
                            'label' => 'Sender',
                            'name' => 'sms_api_sender',
                            'value' => settings()->get('sms_api_sender'),
                        ])
                    </div>

                    <div class="row w-100">
                        <div class="col-12">
                            <div class="section">SMTP Mailing</div>
                        </div>
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', [
                            'label' => 'Host',
                            'name' => 'mailing_host',
                            'value' => settings()->get('mailing_host'),
                        ])
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', [
                            'label' => 'Port',
                            'name' => 'mailing_port',
                            'value' => settings()->get('mailing_port'),
                        ])
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', [
                            'label' => 'Username',
                            'name' => 'mailing_username',
                            'value' => settings()->get('mailing_username'),
                        ])
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', [
                            'label' => 'Password',
                            'name' => 'mailing_password',
                            'type' => 'password',
                            'value' => settings()->get('mailing_password'),
                        ])
                    </div>
                    <div class="row w-100 form-group">
                        <label for="mailing_encryption" class="col-12 col-form-label control-label">
                            <div class="text-start w-100">
                                Encryption
                            </div>
                        </label>
                        <div class="col-12 control-input d-flex align-items-center">
                            <select class="form-select" name="mailing_encryption" id="form_mailing_encryption">
                                <option value=""<?php if(settings()->get("mailing_encryption") == ''){?> selected<?php } ?>>Brak</option>
                                <option value="ssl"<?php if(settings()->get("mailing_encryption") == 'ssl'){?> selected<?php } ?>>SSL</option>
                                <option value="tls"<?php if(settings()->get("mailing_encryption") == 'tls'){?> selected<?php } ?>>TLS</option>
                                <option value="starttls"<?php if(settings()->get("mailing_encryption") == 'starttls'){?> selected<?php } ?>>STARTTLS</option>
                            </select>
                        </div>

                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', [
                            'label' => 'From Address',
                            'name' => 'mailing_from_address',
                            'value' => settings()->get('mailing_from_address'),
                        ])
                    </div>
                    <div class="row w-100 form-group">
                        @include('form-elements.input-text', [
                            'label' => 'From Name',
                            'name' => 'mailing_from_name',
                            'value' => settings()->get('mailing_from_name'),
                        ])
                    </div>
                    <div class="row w-100 form-group mt-5">

                        @include('form-elements.input-text', [
                            'label' => 'Testowy email',
                            'name' => 'test_email',
                            'sublabel' => 'Wpisz email na który chcesz wysłać testowy email',
                            'value' => settings()->get('test_email'),
                        ])
                        <div class="col-12 mt-4 text-center">
                            <button type="button" class="btn btn-primary" id="send_test_email">Sprawdź połączenie
                                SMTP</button>
                        </div>
                        <div class="col-12 mt-3">
                            <div id="test_email_result"></div>
                        </div>


                    </div>

                </div>
            </div>
            <div class="form-group form-group-submit">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <input name="submit" id="submit" value="Zapisz" class="btn btn-primary" type="submit">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
    <script>
        @if (session('success'))
            toastr.options = {
                closeButton: !0,
                progressBar: !0,
                positionClass: "toast-bottom-right",
                timeOut: "3000"
            };
            toastr.success("{{ session('success') }}");
        @endif
        $(document).ready(function() {
            $(".input-url .form-control").each(function() {
                if ($(this).val()) {
                    $(this).next(".input-group-append").removeClass('d-none').addClass('d-block');
                }
            });
            $(".input-group-append button").click(function() {
                const url = $(this).offsetParent().children('input').val();
                window.open(url, '_blank');
            });
            $('#pagespeed').click(function() {
                const url = $("#form_page_url").val();
                window.open('https://developers.google.com/speed/pagespeed/insights/?url=' + url, '_blank');
            });

            $('[name=page_email]').tagify({
                'autoComplete.enabled': false
            });



        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('send_test_email').addEventListener('click', async function() {
                const url = '{{ route('admin.smtp-test.checkSMTP') }}';
                const button = this;
                button.classList.add('loading');
                const body = {
                    host: document.getElementById('form_mailing_host').value,
                    port: document.getElementById('form_mailing_port').value,
                    username: document.getElementById('form_mailing_username').value,
                    password: document.getElementById('form_mailing_password').value,
                    encryption: document.getElementById('form_mailing_encryption').value,
                    from_address: document.getElementById('form_mailing_from_address').value,
                    from_name: document.getElementById('form_mailing_from_name').value,
                    to_address: document.getElementById('form_test_email').value,
                }
                const response = await sendTestEmail(url, body);
                button.classList.remove('loading');
                console.log(response);


            });

            async function sendTestEmail(url, body) {
                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(body)
                    });

                    if (response.ok) {
                        const data = await response.json();
                        toastr.success(data.message);
                        return data;
                    } else {
                        const data = await response.json();
                        console.error(data);
                        toastr.error('Wystąpił błąd podczas wysyłania emaila. Sprawdź poprawność danych SMTP');
                    }

                } catch (error) {
                    console.error(error);
                    toastr.error('Wystąpił błąd podczas wysyłania emaila. Sprawdź poprawność danych SMTP');
                }
            }
        });
    </script>
@endpush
<style>
    .btn.loading {
        pointer-events: none;
        opacity: 0.5;
    }

    .btn.loading:after {
        content: "";
        display: inline-block;
        width: 1em;
        height: 1em;
        border: 0.15em solid currentColor;
        border-right-color: transparent;
        border-radius: 50%;
        animation: loading-spinner 0.75s linear infinite;
        vertical-align: middle;
        margin-left: 0.5em;
    }

    @keyframes loading-spinner {
        to {
            transform: rotate(360deg);
        }
    }
</style>
