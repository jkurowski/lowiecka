<div class="alert alert-danger" style="display:none"></div>

<form method="POST" action="" id="noteForm">
    @include('form-elements.textarea-fullwidth', ['label' => 'Treść', 'name' => 'content', 'value' => '', 'rows' => 11, 'class' => 'tinymce', 'required' => 1])
    <div class="mb-3"></div>
    @include('form-elements.submit-static', ['name' => 'submit', 'value' => 'Zapisz'])
</form>
@routes('notes_api')
@push('scripts')
    <script src="{{ asset('/js/editor/tinymce.min.js') }}" charset="utf-8"></script>
    <script>
        function initTinyMCE(selector){
            tinymce.init({
                selector: selector,
                language: "pl",
                skin: "oxide",
                content_css: 'default',
                branding: false,
                menubar:false,
                statusbar: false,
                toolbar_location: 'bottom',
                height: 200,
                toolbar1: "bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | superscript subscript | numlist bullist outdent indent | responsivefilemanager",
                relative_urls: false,
                image_advtab: true,
                external_filemanager_path:"/js/editor/plugins/filemanager/",
                filemanager_title:"kCMS Filemanager" ,
                external_plugins: { "filemanager" : "{{ asset('/js/editor/plugins/filemanager/plugin.min.js') }}"}
            });
        }
        function addButtons(selector){
            let btn = document.createElement("button");
            btn.innerHTML = "Anuluj";
            btn.type = "button";
            btn.className = "btn btn-light btn-sm";
            selector.appendChild(btn);

            let submitBtn = document.createElement("button");
            submitBtn.innerHTML = "Zapisz";
            submitBtn.type = "submit";
            submitBtn.className = "btn btn-primary btn-sm";
            selector.appendChild(submitBtn);
        }

        initTinyMCE(".tinymce");

        const form = document.getElementById('noteForm');
        const contentInput = document.getElementById('form_content');
        const $noteList = $("#notes");
        const $alert = $('.alert-danger');

        contentInput.value = '';

        const Note = ({ id, created_at, user, text }) => `<div class="note" data-note-id="${id}"><div class="noteItemIcon"><i class="fe-file-text"></i></div><div class="noteItemContent"><div class="noteItemDate">${created_at}<span class="separator">·</span>${user}</div><div class="noteItemButtons"><a role="button" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-menu-dots"><i class="fe-more-horizontal-"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item dropdown-item-edit" href="#">Edytuj</a></li><li><a class="dropdown-item dropdown-item-delete" href="#">Usuń</a></li></ul></div><div class="noteItemText">${text}</div></div></div>`;

        form.addEventListener('submit', (e)=> {
            e.preventDefault();
            tinymce.triggerSave();

            $.ajax({
                url: '{{ route('admin.notes.store') }}',
                method: 'POST',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'text': contentInput.value,
                    'model_type': '{!! $model::class !!}',
                    'model_id': '{{ $model->id }}'

                },
                success: function(response) {
                    if (response.success === true) {

                        console.log(response.note.id);

                        $noteList.prepend([
                            {
                                id: response.note.id,
                                text: response.note.text,
                                user: response.note.user.name +' '+ response.note.user.surname,
                                created_at: response.note.created_at
                            },
                        ].map(Note).join(''));

                        toastr.options =
                            {
                                "closeButton" : true,
                                "progressBar" : true
                            }
                        toastr.success("Notatka pozytywnie dodana.");

                        tinymce.activeEditor.setContent("");
                    }
                },
                error : function(result){
                    if(result.responseJSON.data)
                    {
                        $alert.html('');
                        $.each(result.responseJSON.data, function(key, value){
                            $alert.show();
                            $alert.append('<span class="d-block">'+value+'</span>');
                        });
                    }
                }
            });
        });

        $noteList.on('click', '.dropdown-item-delete', function(event){
            const target = event.target;
            const parent = target.closest(".note");
            $.confirm({
                title: "Potwierdzenie usunięcia",
                message: "Czy na pewno chcesz usunąć?",
                buttons: {
                    Tak: {
                        "class": "btn btn-primary",
                        action: function() {
                            $.ajax({
                                url: route('admin.notes.destroy', {note: parent.dataset.noteId}),
                                type: "DELETE",
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function() {
                                    toastr.options =
                                        {
                                            "closeButton" : true,
                                            "progressBar" : true
                                        }
                                    toastr.success("Notatka poprawnie usunięta");
                                    parent.style.height = "0px"
                                    parent.remove();
                                }
                            })
                        }
                    },
                    Nie: {
                        "class": "btn btn-secondary",
                        action: function() {}
                    }
                }
            })
        });

        $noteList.on('click', '.dropdown-item-edit', function(event){
            const target = event.target;
            const parent = target.closest(".note");
            const noteDiv = parent.querySelector(".noteItemText");
            const noteButtons = parent.querySelector(".noteItemButtons");

            noteButtons.style.display = "none";
            noteDiv.innerHTML = '<textarea>' + noteDiv.innerHTML + '</textarea>';
            const noteTextarea = ".note[data-note-id=" + parent.dataset.noteId + "] textarea";
            initTinyMCE(noteTextarea);
            addButtons(noteDiv)
        });

        $noteList.on('click', '.btn-light', function(event){
            const target = event.target;
            const parent = target.closest(".note");
            const noteDiv = parent.querySelector(".noteItemText");
            const noteButtons = parent.querySelector(".noteItemButtons");
            const noteTextarea = parent.querySelector("textarea");

            noteButtons.style.display = "block";
            noteDiv.innerHTML = noteTextarea.value;
            noteTextarea.remove();
        });

        $noteList.on('click', '.btn-primary', function(event){
            const target = event.target;
            const parent = target.closest(".note");
            const noteDiv = parent.querySelector(".noteItemText");
            const noteButtons = parent.querySelector(".noteItemButtons");
            const noteTextarea = parent.querySelector("textarea");

            tinymce.triggerSave();

            $.ajax({
                url: route('admin.notes.update', {note: parent.dataset.noteId}),
                method: 'PUT',
                async: false,
                data: {
                    '_token': '{{ csrf_token() }}',
                    'text': noteTextarea.value,
                    'model_type': '{!! $model::class !!}',
                    'model_id': '{{ $model->id }}'
                },
                success: function(response) {
                    if (response.success === true) {
                        noteDiv.innerHTML = response.note.text;
                        toastr.options =
                            {
                                "closeButton" : true,
                                "progressBar" : true
                            }
                        toastr.success("Notatka zapisana poprawnie.");

                        noteButtons.style.display = "block";
                        noteTextarea.remove();
                    }
                },
                error : function(result){
                    if(result.responseJSON.data)
                    {
                        $alert.html('');
                        $.each(result.responseJSON.data, function(key, value){
                            $alert.show();
                            $alert.append('<span>'+value+'</span>');
                        });
                    }
                }
            });
        });
    </script>
@endpush