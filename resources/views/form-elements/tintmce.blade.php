<script src="{{ asset('/js/editor/tinymce.min.js') }}" charset="utf-8"></script>
<script>
    tinymce.init({
        selector: ".tinymce",
        language: "pl",
        skin: "oxide",
        content_css: 'default',
        branding: false,
        height: 400,
        plugins: [
            "code advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor gallery responsivefilemanager code @if(isset($templates) && $templates) template @endif"
        ],
        toolbar1: "formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat | gallery | responsivefilemanager | code @if(isset($templates) && $templates) template @endif",
        @if(isset($templates) && $templates)
        templates: function (success) {
            fetch('{{ route('admin.crm.offer.content-templates.show') }}')
                .then(response => response.json())
                .then(data => success(data))
                .catch(error => console.error('Error loading templates:', error));
        },
        @endif
        relative_urls: false,
        image_advtab: true,
        external_filemanager_path:"/js/editor/plugins/filemanager/",
        filemanager_title:"kCMS Filemanager" ,
        external_plugins: { "filemanager" : "{{ asset('/js/editor/plugins/filemanager/plugin.min.js') }}"}
    });
</script>
