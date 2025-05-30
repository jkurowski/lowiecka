function countTasksByStageId(stageId) {
    const $stage = $('.stage[data-stage-id="' + stageId + '"]');
    let totalTasks = 0;
    $stage.each(function() {
        const tasksCount = $(this).find('.task').length;
        $(this).find('.badge').text(tasksCount);
        totalTasks += tasksCount;
    });
    return totalTasks;
}

$(function() {
    // Create task
    $stages.on('click', '.dropdown-item-create', function(event){
        const target = event.target;
        const parent = target.closest(".stage");
        jQuery.ajax({
            type: 'POST',
            data: {
                '_token': token,
                'stage_id': parent.dataset.stageId,
                'board_id': board_id
            },
            url: route('admin.crm.board.task.form'),
            success: function(response) {
                if(response) {
                    $('body').append(response);
                    initTaskModal(board_id);
                } else {
                    alert('Error');
                }
            }
        });
    });

    // Edit task
    $stages.on('click', '.dropdown-item-edit', function(event){
        const target = event.target;
        const parent = target.closest(".task");
        jQuery.ajax({
            type: 'POST',
            data: {
                '_token': token,
                'id': parent.dataset.taskId,
                'board_id': board_id
            },
            url: route('admin.crm.board.task.form'),
            success: function(response) {
                if(response) {
                    $('body').append(response);
                    initTaskModal(board_id);
                } else {
                    alert('Error');
                }
            }
        });
    });

    // Assign task
    $stages.on('click', '.dropdown-item-assign', function(event){
        const target = event.target;
        const parent = target.closest(".task");
        jQuery.ajax({
            type: 'POST',
            data: {
                '_token': token,
                'id': parent.dataset.taskId,
                'board_id': board_id
            },
            url: route('admin.crm.board.task.assign'),
            success: function(response) {
                if(response) {
                    $('body').append(response);
                    initAssignModal(parent.dataset.taskId);
                } else {
                    alert('Error');
                }
            }
        });
    });

    // Remove task
    $stages.on('click', '.dropdown-item-delete', function (event) {
        const target = event.target;
        const parent = target.closest(".task");
        const stage = target.closest(".stage");
        $.confirm({
            title: "Potwierdzenie usunięcia",
            message: "Czy na pewno chcesz usunąć?",
            buttons: {
                Tak: {
                    "class": "btn btn-primary",
                    action: function () {
                        $.ajax({
                            url: route('admin.crm.board.task.destroy', parent.dataset.taskId),
                            type: "DELETE",
                            data: {
                                '_token': token
                            },
                            success: function () {
                                toastr.options =
                                    {
                                        "closeButton": true,
                                        "progressBar": true
                                    }
                                toastr.success("Wpis został poprawnie usunięty");

                                parent.style.height = "0px"
                                parent.remove();

                                countTasksByStageId(stage.dataset.stageId);
                            }
                        })
                    }
                },
                Nie: {
                    "class": "btn btn-secondary",
                    action: function () {
                    }
                }
            }
        })
    });

    // Create new stage
    $stages.on('click', '.dropdown-stage-create', function(event){
        const target = event.target;
        const parent = target.closest(".stage");
        jQuery.ajax({
            type: 'POST',
            data: {
                '_token': token,
                'stage_id': parent.dataset.stageId,
                'board_id': board_id
            },
            url: route('admin.crm.board.stage.form'),
            success: function(response) {
                if(response) {
                    $('body').append(response);
                    initStageModal(board_id);
                } else {
                    alert('Error');
                }
            }
        });
    });

    // Edit new stage
    $stages.on('click', '.dropdown-stage-edit', function(event){
        const target = event.target;
        const parent = target.closest(".stage");
        jQuery.ajax({
            type: 'POST',
            data: {
                '_token': token,
                'id': parent.dataset.stageId,
                'board_id': board_id
            },
            url: route('admin.crm.board.stage.form'),
            success: function(response) {
                if(response) {
                    $('body').append(response);
                    initStageModal(board_id);
                } else {
                    alert('Error');
                }
            }
        });
    });

    // Remove stage
    $stages.on('click', '.dropdown-stage-delete', function(event){
        const target = event.target;
        const parent = target.closest(".stage");
        $.confirm({
            title: "Potwierdzenie usunięcia",
            message: "Czy na pewno chcesz usunąć?",
            buttons: {
                Tak: {
                    "class": "btn btn-primary",
                    action: function() {
                        $.ajax({
                            url: route('admin.crm.board.stage.destroy', parent.dataset.stageId),
                            type: "DELETE",
                            data: {
                                '_token': token
                            },
                            success: function() {
                                toastr.options =
                                    {
                                        "closeButton" : true,
                                        "progressBar" : true
                                    }
                                toastr.success("Etap został poprawnie usunięty");
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
});

function initAssignModal(task_id){
    const modal = document.getElementById('portletModal'),
        boardTaskModal = new bootstrap.Modal(modal),
        form = document.getElementById('modalForm'),
        $inputTaskId = $('#inputTaskId'),
        $inputDate = $('#inputDate'),
        $inputHour = $('#inputTime'),
        $inputClient = $('#inputClient'),
        $inputClientId = $('#inputClientId');

    boardTaskModal.show();

    const users = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: '/admin/user/get-list'
        }
    });

    $inputDate.datepicker({
        format: 'yyyy-mm-dd',
        todayHighlight: true,
        language: "pl"
    });

    modal.addEventListener('shown.bs.modal', () => {
        users.clearPrefetchCache();
        users.initialize();
        $inputClient.typeahead({
                hint: true,
                highlight: true,
                minLength: 3
            },
            {
                name: 'users',
                templates: {
                    suggestion: function (data) {
                        return '<div class="item">' +
                            '<div class="row">' +
                            '<div class="col-12"><h4>'+ data.name +' '+ data.surname +'</h4></div>' +
                            '<div class="col-12">' + (data.email ? '<span>E: ' + data.email + '</span>' : '') + '</div>' +
                            '<div class="col-12">' + (data.phone ? '<span>T: ' + data.phone + '</span>' : '') + '</div>' +
                            '</div>' +
                            '</div>';
                    }
                },
                display: 'value',
                source: users
            });
    });
    $inputClient.on('typeahead:select', function (ev, suggestion) {
        $inputClientId.val(suggestion.id);
        $inputClient.val(suggestion.name);
        $inputClient.typeahead('val', suggestion.name +' '+ suggestion.surname)
    });

    document.getElementById('inputClient').addEventListener('input', () => {
        $inputClientId.val(0);
    })
    modal.addEventListener('hidden.bs.modal', () => {
        $('#portletModal').remove();
        users.clearPrefetchCache();
    })

    const $alert = $('.alert-danger');
    form.addEventListener('submit', (e)=> {
        e.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        $.ajax({
            url: route('admin.crm.board.task.assign.save'),
            method: 'POST',
            async: false,
            data: {
                '_token': token,
                'task_id': $inputTaskId.val(),
                'user_id': $inputClientId.val(),
                'start': $inputDate.val(),
                'time': $inputHour.val()
            },
            success: function(result) {
                toastr.options =
                    {
                        "closeButton" : true,
                        "progressBar" : true
                    }
                toastr.info("Wpis został poprawnie zapisany");
                boardTaskModal.hide();
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
}

function initTaskModal(board_id){
    const modal = document.getElementById('portletModal'),
        boardTaskModal = new bootstrap.Modal(modal),
        form = document.getElementById('modalForm'),
        $inputTaskId = $('#inputTaskId'),
        $inputStageId = $('#inputStageId'),
        $inputName = $('#inputName'),
        $inputClient = $('#inputClient'),
        $inputClientId = $('#inputClientId');

    boardTaskModal.show();

    const users = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: {
            url: '/admin/rodo/clients'
        }
    });

    modal.addEventListener('shown.bs.modal', () => {
        users.clearPrefetchCache();
        users.initialize();
        $inputClient.typeahead({
                hint: true,
                highlight: true,
                minLength: 3
            },
            {
                name: 'users',
                display: function (item) {
                    let displayString = item.name + ' - ' + item.mail;
                    if (item.phone) {
                        displayString += ' - ' + item.phone;
                    }
                    return displayString;
                },
                source: users
            });
        $inputClient.bind('typeahead:select', function(ev, suggestion) {
            $inputClientId.val(suggestion.id);
        });

        // TODO: Task z notatnika
        //@if($task->client)
        //   $inputClient.typeahead('val', '{{$task->client->name}}');
        //   $inputClientId.val({{$task->client->id}});
        //@endif
    })
    document.getElementById('inputClient').addEventListener('input', () => {
        $inputClientId.val(0);
    })
    modal.addEventListener('hidden.bs.modal', () => {
        $('#portletModal').remove();
        users.clearPrefetchCache();
    })

    const $alert = $('.alert-danger');
    form.addEventListener('submit', (e)=> {
        e.preventDefault();

        const Task = ({ id, name, client_id, created_at }) => `<div class="task" data-task-id="${id}"><div class="task-body"><div class="task-name w-100">${name}</div><div class="task-desc w-100">${client_id}</div><div class="task-date w-50 d-flex align-items-center">${created_at}</div><div class="task-action d-flex align-items-center justify-content-end w-50"><a role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fe-more-horizontal-"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item dropdown-item-edit" href="#">Edytuj zadanie</a></li><li><a class="dropdown-item dropdown-item-delete" href="#">Usuń zadanie</a></li></ul></div></div>`;

        $.ajax({
            url: "/admin/crm/board/task",
            method: 'POST',
            async: false,
            data: {
                '_token': token,
                'board_id': board_id,
                'id': $inputTaskId.val(),
                'name': $inputName.val(),
                'client_id': $inputClientId.val(),
                'stage_id': $inputStageId.val()
            },
            success: function(result) {
                if (result.action === 'created') {
                    const $stageColumn = $(".stage-tasks[data-stage-id=" + result.stage_id + "]");
                    $stageColumn.append([
                        {
                            id: result.id,
                            name: result.name,
                            client_id: result.client_id,
                            created_at: result.created_at
                        },
                    ].map(Task).join(''));

                    // TODO: Dodać zapis sortowania tabeli po dodaniu task`a


                    countTasksByStageId(result.stage_id);

                    toastr.options =
                        {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                    toastr.success("Wpis został poprawnie dodany");
                } else if (result.action === 'updated') {
                    $(".task[data-task-id=" + result.id + "] ").replaceWith([
                        {
                            id: result.id,
                            name: result.name,
                            client_id: result.client_id,
                            created_at: result.created_at
                        },].map(Task).join(''));
                    toastr.options =
                        {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                    toastr.info("Wpis został poprawnie zapisany");
                }
                boardTaskModal.hide();
            },
            error : function(result){
                if(result.responseJSON.errors)
                {
                    $alert.html('');
                    $.each(result.responseJSON.errors, function(key, value){
                        $alert.show();
                        $alert.append('<span>'+value+'</span>');
                    });
                }
            }
        });
    });
}

function initStageModal(board_id) {
    const modal = document.getElementById('portletModal'),
        boardStageModal = new bootstrap.Modal(modal),
        form = document.getElementById('modalForm'),
        $inputStageId = $('#inputStageId'),
        $inputCurrentStageId = $('#inputCurrentStageId'),
        $inputName = $('#inputName');

    boardStageModal.show();
    modal.addEventListener('hidden.bs.modal', () => {
        modal.remove();
    });

    const $alert = $('.alert-danger');

    const Stage = ({ id, name}) => `<div class="stage" data-stage-id="${id}"><div class="stage-title"><span title="Ilość zadań: 0"><span class="badge text-bg-secondary">0</span><i>${name}</i></span><a role="button" data-bs-toggle="dropdown" aria-expanded="false" class="dropdown-menu-dots"><i class="fe-more-horizontal-"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item dropdown-stage-create" href="#">Dodaj etap</a></li><li><a class="dropdown-item dropdown-stage-edit" href="#">Edytuj etap</a></li><li><a class="dropdown-item dropdown-stage-delete" href="#">Usuń etap</a></li><li><hr class="dropdown-divider"></li><li><a class="dropdown-item dropdown-item-create" href="#">Dodaj zadanie</a></li></ul></div><div class="stage-tasks ui-sortable" data-stage-id="${id}"></div></div>`;

    form.addEventListener('submit', (e)=> {
        e.preventDefault();
        $.ajax({
            url: "/admin/crm/board/stage",
            method: 'POST',
            async: false,
            data: {
                '_token': token,
                'board_id': board_id,
                'name': $inputName.val(),
                'id': $inputStageId.val(),
                'current_stage_id': $inputCurrentStageId.val()
            },
            success: function(result) {
                if (result.action === 'created') {
                    $([{
                        id: result.id,
                        name: result.name
                    }].map(Stage)
                        .join(''))
                        .insertAfter($(".stage[data-stage-id=" + result.current_stage_id + "]"));

                    const items = $stages.sortable('toArray', {attribute: 'data-stage-id'});
                    jQuery.ajax({
                        type: 'POST',
                        data: {
                            '_token': token,
                            'items': items
                        },
                        url: '/admin/crm/board/stage/sort',
                        success: function () {
                            toastr.options =
                                {
                                    "closeButton" : true,
                                    "progressBar" : true
                                }
                            toastr.success("Utworzono nowy etap");
                            $stages.sortable('refresh');
                            $('.stage[data-stage-id="0"]').remove();
                        },
                        error: function () {
                            toastr.options =
                                {
                                    "closeButton" : true,
                                    "progressBar" : true,
                                    "preventDuplicates": true
                                }
                            toastr.error("Wystąpił błąd podczas zapisu");
                        }
                    });

                } else if (result.action === 'updated') {

                    $(".stage[data-stage-id=" + result.id + "] .stage-title span i").text(result.name);
                    toastr.options =
                        {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                    toastr.info("Etap został zaktualizowany");
                }
                boardStageModal.hide();
            },
            error : function(result){
                if(result.responseJSON.errors)
                {
                    $alert.html('');
                    $.each(result.responseJSON.errors, function(key, value){
                        $alert.show();
                        $alert.append('<span>'+value+'</span>');
                    });
                }
            }
        })
    });
}