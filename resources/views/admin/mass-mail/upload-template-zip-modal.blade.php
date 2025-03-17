<div class="modal fade" id="uploadTemplateModal" tabindex="-1" aria-labelledby="uploadTemplateModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 text-white" id="uploadTemplateModalLabel">Prześlij szablon</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                        class='fe-x'></i></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-4">
                        <p>Prześlij własny szablon wiadomości, który zostanie wysłany do wybranych
                            użytkowników.
                            Przesyłany plik .zip powinien zawierać plik szablonu w formacie html a
                            obrazy
                            użyte
                            w szablonie muszą być umieszczone w folderze <strong>images</strong>,
                            a ich ścieżki w szablonie muszą być w formacie
                            <strong>images/nazwa_obrazu.rozszerzenie</strong>
                        </p>
                    </div>

                    <div>
                        <input type="file" class="form-control" id="custom-html-template" accept=".zip">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                <button type="submit" class="btn btn-primary" form="edit-templates-form">Zapisz</button>
            </div>
        </div>
    </div>
</div>
<script defer>
    document.addEventListener('DOMContentLoaded', () => {

        const initializeUploadTemplateManager = (uploadCustomTemplateRoute) => {
            const uploadCustomTemplateInput = document.getElementById('custom-html-template');

            uploadCustomTemplateInput.addEventListener('change', async (e) => {
                const file = e.target.files[0];
                const formData = new FormData();
                formData.append('file', file);

                try {
                    const response = await fetch(uploadCustomTemplateRoute, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: formData,
                    });
                    const data = await response.json();

                    if (response.ok) {
                        toastr.success('Szablon został załadowany');
                        // setTimeout(() => {
                        //     window.location.reload();
                        // }, 1000);

                        logErrors(data.errors);
                    } else {
                        toastr.error('Wystąpił błąd podczas przesyłania szablonu');
                        uploadCustomTemplateInput.value = null;

                        logErrors(data.errors);
                    }
                } catch (error) {
                    console.error(error);
                    uploadCustomTemplateInput.value = null;
                    toastr.error('Wystąpił błąd podczas przesyłania szablonu');
                }

            });
        }

        const uploadCustomTemplateRoute = "{{ route('admin.mass-mail.custom-template.upload') }}"
        initializeUploadTemplateManager(uploadCustomTemplateRoute);
    });
</script>
