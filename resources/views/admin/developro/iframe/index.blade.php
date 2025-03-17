@extends('admin.layout')
@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-home"></i><a
                            href="{{ route('admin.developro.investment.index') }}">Inwestycje</a><span
                            class="d-inline-flex me-2 ms-2">/</span><a
                            href="{{ route('admin.developro.investment.edit', $investment) }}">{{ $investment->name }}</a><span
                            class="d-inline-flex me-2 ms-2">/</span>Osadź na stronie</h4>
                </div>
            </div>
        </div>
        @include('admin.developro.investment_shared.menu')
        <div class="card mt-3">
            <div class="p-4 ">
                <div class="row">
                    <div class="col-md-6">
                        <p class='col-form-label pb-3 control-label'>
                            Skopiuj poniższy kod i wklej go w odpowiednie miejsce na swojej stronie internetowej.
                        </p>
                        <code id='iframe-code'>
                            &lt;style&gt;
                            iframe {
                            width: {{ $iframeSettings['preview_width'] }}%;
                            height: {{ $iframeSettings['preview_height'] }}px;
                            }

                            &lt;/style&gt;

                            &lt;iframe src="{{ route('front.iframe.index', $investment) }}" width="{{ $iframeSettings['preview_width'] }}%" height="{{ $iframeSettings['preview_height'] }}px"
                            frameborder="0"&gt;&lt;/iframe&gt;
                        </code>
                        <p class='mt-3'>
                            <button class="btn btn-primary" id="copy-iframe-code">Skopiuj kod</button>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="mt-5">
                            @if (Session::has('success'))
                                <p class="alert alert-success mb-3">{{ Session::get('success') }}</p>
                            @endif
                            <form action="{{ route('admin.developro.investment.iframe.store', $investment) }}"
                                method="POST">
                                @csrf
                                <div>
                                    <div class="form-group border-0 mb-0 d-inline-block">
                                        <label for="bg_color" class="form-label">Kolor tła:</label>
                                        <input class="form-control form-control-color" type="color" id="bg_color"
                                            name="bg_color" value="{{ old('bg_color', $iframeSettings['bg_color']) }}">
                                    </div>

                                    <div class="form-group border-0 mb-0 d-inline-block ms-4">
                                        <label for="box_offer_bg_color" class="form-label">Kolor tła ofert:</label>
                                        <input class="form-control form-control-color" type="color"
                                            id="box_offer_bg_color" name="box_offer_bg_color"
                                            value="{{ old('box_offer_bg_color', $iframeSettings['box_offer_bg_color']) }}">
                                    </div>

                                    <div class="form-group border-0 mb-0 d-inline-block ms-4">
                                        <label for="text_color" class="form-label">Kolor tekstu:</label>
                                        <input class="form-control form-control-color" type="color" id="text_color"
                                            name="text_color"
                                            value="{{ old('text_color', $iframeSettings['text_color']) }}">
                                    </div>

                                    <div class="form-group border-0 mb-0 d-inline-block ms-4">
                                        <label for="link_color" class="form-label">Kolor linków:</label>
                                        <input class="form-control form-control-color" type="color" id="link_color"
                                            name="link_color"
                                            value="{{ old('link_color', $iframeSettings['link_color']) }}">
                                    </div>

                                    <div class="form-group border-0 mb-0 d-inline-block ms-4">
                                        <label for="link_hover_color" class="form-label">Kolor linków po najechaniu:</label>
                                        <input class="form-control form-control-color" type="color" id="link_hover_color"
                                            name="link_hover_color"
                                            value="{{ old('link_hover_color', $iframeSettings['link_hover_color']) }}">
                                    </div>
                                </div>

                                <div class="form-group border-0 mb-0">
                                    <label for="box_offer_margin" class="form-label">Odległość między ofertami (px):</label>
                                    <input type="text" id="box_offer_margin" name="box_offer_margin"
                                        value="{{ old('box_offer_margin', $iframeSettings['box_offer_margin']) }}"
                                        class="form-control">
                                </div>
                                <div class="form-group border-0 mb-0">
                                    <label for="box_offer_padding" class="form-label">Padding ofert (px):</label>
                                    <input type="text" id="box_offer_padding" name="box_offer_padding"
                                        value="{{ old('box_offer_padding', $iframeSettings['box_offer_padding']) }}"
                                        class="form-control">
                                </div>

                                <div class="form-group border-0 mb-0">
                                    <label for="font_family" class="form-label">Czcionka:</label>
                                    <select id="font_family" name="font_family" class="form-control">
                                        @foreach (['Arial', 'Helvetica', 'Times New Roman', 'Courier', 'Verdana', 'Georgia', 'Palatino', 'Garamond', 'Bookman', 'Comic Sans MS', 'Trebuchet MS', 'Arial Black', 'Impact'] as $font)
                                            <option value="{{ $font }}"
                                                {{ old('font_family', $iframeSettings['font_family']) == $font ? 'selected' : '' }}>
                                                {{ $font }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group border-0 mb-0">
                                    <label for="font_size" class="form-label">Rozmiar czcionki (px):</label>
                                    <input type="number" id="font_size" name="font_size" min="8" max="24"
                                        value="{{ old('font_size', $iframeSettings['font_size']) }}"
                                        class="form-control">
                                </div>

                                <div class="form-group border-0 mb-0">
                                    <label for="box_offer_title_font_size" class="form-label">Rozmiar czcionki tytułów ofert (px):</label>
                                    <input type="number" id="box_offer_title_font_size" name="box_offer_title_font_size" min="8" max="24"
                                        value="{{ old('box_offer_title_font_size', $iframeSettings['box_offer_title_font_size']) }}"
                                        class="form-control">
                                </div>

                                @include('form-elements.textarea', [
                                    'name' => 'custom_css',
                                    'label' => 'Własne style CSS',
                                    'value' => old('custom_css', $iframeSettings['custom_css']),
                                    'rows' => 10,
                                ])

                                <div class="form-group border-0 mb-0 mt-3">
                                    <label for="preview_width" class="form-label">Szerokość podglądu (%):</label>
                                    <input type="number" id="preview_width" name="preview_width" min="10"
                                        max="100"
                                        value="{{ old('preview_width', $iframeSettings['preview_width']) }}"
                                        class="form-control">
                                </div>

                                <div class="form-group border-0 mb-0">
                                    <label for="preview_height" class="form-label">Wysokość podglądu (px):</label>
                                    <input type="number" id="preview_height" name="preview_height" min="200"
                                        max="1000"
                                        value="{{ old('preview_height', $iframeSettings['preview_height']) }}"
                                        class="form-control">
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary">Zapisz</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <iframe src="{{ route('front.iframe.index', $investment) }}"
                    width="{{ $iframeSettings['preview_width'] }}%" height="{{ $iframeSettings['preview_height'] }}px"
                    frameborder="0"></iframe>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const copyToClipboard = async (text) => {
                try {
                    await navigator.clipboard.writeText(text);
                    toastr.success('Skopiowano kod do schowka');
                } catch (err) {
                    toastr.error('Nie udało się skopiować kodu');
                }
            };

            const copyButton = document.getElementById('copy-iframe-code');
            const iframeCode = document.getElementById('iframe-code');
            copyButton.addEventListener('click', () => copyToClipboard(iframeCode.innerText));
        });
    </script>
@endpush
