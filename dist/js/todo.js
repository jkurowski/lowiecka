const modal = document.getElementById('customModal'),
    inputX = document.querySelector('input[name="x"]'),
    inputY = document.querySelector('input[name="y"]');

let modalBody = modal.querySelector('.modal-content'), clickedButton = null;

restoreLoader();

document.addEventListener('DOMContentLoaded', () => {
    document.body.addEventListener('click', (event) => {
        if (event.target.classList.contains('open-modal')) {
            const clickedButton = event.target;
            const propertyId = clickedButton.getAttribute('data-id');
            const taskId = clickedButton.getAttribute('data-task-id');
            loadForm(propertyId, taskId);
        }
    });
});

modal.addEventListener('shown.bs.modal', () => {
    if (clickedButton) {
        const propertyId = clickedButton.getAttribute('data-id');
        const taskId = clickedButton.getAttribute('data-task-id');

        console.log(propertyId);
        console.log(taskId);

        loadForm(propertyId, taskId);
    }
});

modal.addEventListener('hidden.bs.modal', () => {
    console.log("hidden.bs.modal");

    restoreLoader();

    if (inputX && inputY) {
        inputX.value = '';
        inputY.value = '';
    } else {
        console.error('Input elements not found');
    }
});

function loadForm(propertyId, taskId = null) {
    let url = `/admin/crm/handover/modal`;
    if (taskId) {
        url += `/${taskId}`;
    }
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Błąd podczas ładowania formularza');
            }
            return response.text();
        })
        .then(html => {
            modalBody.innerHTML = html;
            return Promise.resolve();
        })
        .then(() => {
            initModalForm(propertyId);
        })
        .catch(error => {
            modalBody.innerHTML = `<div class="alert alert-danger mb-0">Wystąpił błąd: ${error.message}</div>`;
        });
}

function initModalForm(propertyId) {
    const form = document.getElementById('modalForm'); // Znajdź formularz
    if (!form) {
        console.error("Nie znaleziono formularza w modalu!");
        return;
    }

    const inputDate = $('#inputDate');
    inputDate.datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        language: "pl",
        autoclose: true
    });

    const Note=({id,created_at,user,text,completed,x,y})=>{
        const attributes = [];

        if (x !== undefined && x !== null) {
            attributes.push(`data-x="${x}"`);
        }
        if (y !== undefined && y !== null) {
            attributes.push(`data-y="${y}"`);
        }
        const attributesString = attributes.length ? ' ' + attributes.join(' ') : '';

        return `<li class="list-group-item p-0 task" data-task-id="${id}"${attributesString}><div class="todo-content-wrapper"><div class="widget-content-left p-2 me-2"><div class="custom-checkbox custom-control"><input class="custom-control-input" id="task${id}" type="checkbox" ${completed?'checked':''}><label class="custom-control-label" for="task${id}">&nbsp;</label></div></div><div class="todo-content-left p-2"><div class="todo-heading">${text}</div><div class="todo-author gap-2 mt-2"><i class="fe-user"></i> ${user} &nbsp;&nbsp;<i class="fe-calendar"></i> ${created_at}</div></div></div><div class="todo-subheading p-2"><div class="row"><div class="col-12"><div class="btn-group w-100" role="group"><button type="button" class="btn btn-primary open-modal" data-bs-toggle="modal" data-bs-target="#customModal" data-task-id="${id}">Edytuj</button><button type="button" class="btn btn-danger" onclick="deleteTask(${id})">Usuń</button><button type="button" class="btn btn-secondary" onclick="viewAttachments(${id})">Załącznik</button></div></div></div></div></li>`};

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const errorHolder = document.querySelector('.alert.alert-danger');
        const clearErrors = () => {
            if (errorHolder) {
                errorHolder.innerHTML = '';
            }
        };

        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        const formData = new FormData(this);
        formData.append('x', inputX.value);
        formData.append('y', inputY.value);

        fetch(`/admin/crm/handover/${propertyId}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    clearErrors();
                    const bootstrapModal = bootstrap.Modal.getInstance(modal);
                    bootstrapModal.hide();
                    const noteData = {
                        id: data.task.id,
                        text: data.task.text,
                        completed: data.task.completed,
                        x: data.task.x,
                        y: data.task.y,
                        user: `${data.task.user.name} ${data.task.user.surname}`,
                        created_at: data.task.due_date
                    };

                    $('#todo').prepend(Note(noteData));
                    form.reset();
                }
            })
            .catch(data => {
                clearErrors();
                loadErrors(data, errorHolder);
                submitButton.disabled = false;
            });
    });
}

function restoreLoader() {
    modalBody.innerHTML = '<div class="d-flex justify-content-center p-5"><div class="spinner-border text-info" role="status"><span class="visually-hidden">Loading...</span></div></div>';
}

function loadErrors(data, errorHolder) {
    if (data.data) {
        const errors = data.data;
        let errorMessages = '<ul>';
        for (const field in errors) {
            if (errors.hasOwnProperty(field)) {
                errors[field].forEach(function(error) {
                    errorMessages += `<li>${error}</li>`;
                });
            }
        }
        errorMessages += '</ul>';
        errorHolder.innerHTML = errorMessages;
        errorHolder.style.display = 'block';
    } else {
        console.log('[loadErrors] Missing data');
    }
}