@extends('admin.layout')
@section('meta_title', '- '.$cardTitle)

@section('content')
    @if(Route::is('admin.contract.template.edit'))
        <form method="POST" action="{{route('admin.contract.template.update', $entry)}}" enctype="multipart/form-data">
            @method('PUT')
            @else
                <form method="POST" action="{{route('admin.contract.template.store')}}" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="container">
                        <div class="card-head container">
                            <div class="row">
                                <div class="col-12 pl-0">
                                    <h4 class="page-title"><i class="fe-book-open"></i><a href="{{route('admin.contract.index')}}" class="p-0">Generator dokumentów</a><span class="d-inline-flex me-2 ms-2">/</span>{{ $cardTitle }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card mt-3">
                            @include('form-elements.back-route-button')
                            <div class="card-body control-col12">

                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Nazwa dokumentu', 'name' => 'name', 'value' => $entry->name, 'required' => 1])
                                </div>

                                <div class="row w-100 form-group">
                                    @include('form-elements.html-input-text', ['label' => 'Opis dokumentu', 'name' => 'description', 'value' => $entry->description, 'required' => 1])
                                </div>

                                <div class="row w-100 form-group">
                                    <div class="col-4">
                                        <div class="alert alert-primary mt-3" role="alert">
                                            <p>W szablonie możesz używać dowolnych zmiennych, które podczas generowania zostaną zastąpione wprowadzonymi danymi. Aby użyć zmiennej wpisz w edytorze jej nazwę w nawiasach "[ ]" np. [Firma], [Nazwisko].</p>
                                        </div>
                                        <label for="usedVariables" class="col-form-label control-label">Tagi użyte w dokumencie</label>
                                        <div id="usedVariables"></div>

                                        <label for="proposalVariables" class="col-form-label control-label">Proponowane tagi</label>
                                        <div id="proposalVariables">
                                            <a href="#" class="btn btn-primary">Klient: imię i nazwisko</a>
                                            <a href="#" class="btn btn-primary">Klient: e-mail</a>
                                            <a href="#" class="btn btn-primary">Klient: telefon</a>
                                            <a href="#" class="btn btn-primary">Klient: nr. dowodu</a>
                                            <a href="#" class="btn btn-primary">Klient: PESEL</a>
                                            <a href="#" class="btn btn-primary">Klient: NIP</a>
                                            <a href="#" class="btn btn-primary">Klient: Regon</a>
                                            <a href="#" class="btn btn-primary">Sprzedawca: imię i nazwisko</a>
                                            <a href="#" class="btn btn-primary">Sprzedawca: nr. dowodu</a>
                                            <a href="#" class="btn btn-primary">Data: dzien.miesiąc.rok</a>
                                            <a href="#" class="btn btn-primary">Data: dzien</a>
                                            <a href="#" class="btn btn-primary">Data: miesiac</a>
                                            <a href="#" class="btn btn-primary">Data: rok</a>
                                            <a href="#" class="btn btn-primary">Adres: miasto</a>
                                            <a href="#" class="btn btn-primary">Adres: ulica</a>
                                            <a href="#" class="btn btn-primary">Adres: lokal</a>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        @include('form-elements.textarea-fullwidth', ['label' => 'Treść dokumentu', 'name' => 'text', 'value' => $entry->text, 'rows' => 21, 'class' => 'tinymce', 'required' => 1])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="hiddenUniqueMatches" name="tags">
                    @include('form-elements.submit', ['name' => 'submit', 'value' => 'Zapisz szablon'])
                </form>
                @endsection
                @push('scripts')
                    <script src="{{ asset('/js/editor/tinymce.min.js') }}" charset="utf-8"></script>
                    <script>
                        tinymce.init({
                            selector: ".tinymce",
                            language: "pl",
                            skin: "oxide",
                            content_css: '/js/editor/skins/content/default/pdf-generator.css',
                            branding: false,
                            height: 1200,
                            plugins: [
                                "code advlist autolink link image lists charmap print preview hr anchor pagebreak",
                                "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                                "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
                            ],
                            toolbar1: "formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat | gallery | responsivefilemanager | code",
                            relative_urls: false,
                            image_advtab: true,
                            external_filemanager_path:"/js/editor/plugins/filemanager/",
                            filemanager_title:"kCMS Filemanager" ,
                            external_plugins: { "filemanager" : "{{ asset('/js/editor/plugins/filemanager/plugin.min.js') }}"}, // Target your textarea or input field
                            paste_preprocess: function(editor, args) {
                                // Log the content to debug
                                console.log('Before paste:', args.content);

                                // Remove unwanted inline styles from table, td, th, tr
                                args.content = args.content.replace(/<(table|td|th|tr)[^>]*>/g, function(match) {
                                    return match.replace(/(width|height|padding|style|margin|font-size|color)="[^"]*"/g, '');
                                });

                                // Remove <dd> and <dl> tags
                                args.content = args.content.replace(/<dl[^>]*>/g, '').replace(/<\/dl>/g, '');  // Remove <dl> and </dl>
                                args.content = args.content.replace(/<dd[^>]*>/g, '').replace(/<\/dd>/g, '');  // Remove <dd> and </dd>

                                // Log the cleaned content
                                console.log('After paste:', args.content);
                            },
                            setup: function (editor) {
                                // Initialize proposal variables for insertion
                                function initPredefinedVariables() {
                                    const links = document.querySelectorAll('#proposalVariables a');
                                    links.forEach(function (link) {
                                        link.addEventListener('click', function (e) {
                                            e.preventDefault(); // Prevent default behavior
                                            const variable = link.textContent;
                                            editor.insertContent('[' + variable + ']'); // Insert into editor
                                        });
                                    });
                                }

                                // Update the hidden input and dynamically manage used variables
                                function updateUsedVariables() {
                                    const content = editor.getContent();
                                    const matches = content.match(/\[(.*?)\]/g);
                                    const usedVariablesContainer = document.getElementById('usedVariables');
                                    usedVariablesContainer.innerHTML = ''; // Clear previous entries

                                    if (matches) {
                                        const uniqueVariables = [...new Set(matches.map(match => match.replace(/[\[\]]/g, '')))];
                                        uniqueVariables.forEach(variable => {
                                            const link = document.createElement('a');
                                            link.href = '#';
                                            link.textContent = variable;
                                            link.classList.add('btn', 'btn-primary');

                                            // On click, insert the variable into the editor
                                            link.addEventListener('click', function (e) {
                                                e.preventDefault();
                                                editor.insertContent('[' + variable + ']');
                                            });

                                            usedVariablesContainer.appendChild(link);
                                            usedVariablesContainer.appendChild(document.createTextNode(' '));
                                        });

                                        // Update hidden input
                                        document.getElementById('hiddenUniqueMatches').value = JSON.stringify(uniqueVariables);
                                    }
                                }

                                // Listen for content changes to update used variables
                                editor.on('change input', function () {
                                    updateUsedVariables();
                                    addPageBreakLines();
                                });

                                // Initialize on editor load
                                editor.on('init', function () {
                                    initPredefinedVariables(); // Set up proposal variables
                                    updateUsedVariables(); // Update used variables if content exists
                                    addPageBreakLines();
                                });

                                function addPageBreakLines() {
                                    // Get the content body container
                                    var contentBody = editor.getBody();

                                    // Remove any previously added page break lines
                                    var existingLines = contentBody.querySelectorAll('.page-break-line');
                                    existingLines.forEach(function(line) {
                                        line.remove();
                                    });

                                    // Determine the height of the content body
                                    var contentHeight = contentBody.scrollHeight;

                                    // Add a red line every 1123px (A4 page height)
                                    for (var i = 1123; i < contentHeight; i += 1123) {
                                        var line = document.createElement('div');
                                        line.classList.add('page-break-line');
                                        line.style.top = i + 'px'; // Place the line at each 1123px interval
                                        contentBody.appendChild(line);
                                    }
                                }
                            }
                        });
                    </script>
        @endpush
