function initEventEditModal(t = null){

    const modal = document.getElementById('portletModal'),
        calendarEventModal = new bootstrap.Modal(modal),
        form = document.getElementById('modalForm'),
        inputInvestment = $('#inputInvestment'),
        inputDepartment = $('#inputDepartment'),
        inputProperty = $('#inputProperty'),
        inputStorage = $('#inputStorage'),
        inputParking = $('#inputParking'),
        inputDate = $('#inputDate'),
        inputTime = $('#inputTime'),
        inputActivity = $('#inputActivity'),
        inputClientId = $('#inputClientId'),
        inputNote = $('#inputNote'),
        inputAllDay = $('#inputAllDay'),
        inputEventId = $('#inputEventId'),
        clientItem = document.querySelector('#selectedClientItem');

    calendarEventModal.show();
    modal.addEventListener('shown.bs.modal', function () {
        fetchInvestmentProperties();

        inputDate.datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            language: "pl"
        });

        const elements = document.querySelectorAll(".btn-group label");
        for (let i = 0; i < elements.length; i++) {
            elements[i].addEventListener("click", function(e) {
                const input = document.getElementById("inputActivity");
                input.placeholder = e.currentTarget.dataset.bsTitle;
            });
        }
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger : 'hover'
            })
        });
    })

    modal.addEventListener('hidden.bs.modal', function () {
        $('#portletModal').remove();
        refreshCalendar();
    })

    const alert = $('.alert-danger');
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        jQuery.ajax({
            url: route('admin.crm.calendar.event.update', { id: inputEventId.val() }),
            method: 'PUT',
            data: {
                '_token': token,
                'investment_id': inputInvestment.val(),
                'property_id': inputProperty.val(),
                'parking_id': inputParking.val(),
                'storage_id': inputStorage.val(),
                'department_id': inputDepartment.val(),
                'start': inputDate.val(),
                'end': inputDate.val(),
                'time': inputTime.val(),
                'name': inputActivity.val(),
                'note': inputNote.val(),
                'client_id': inputClientId.val(),
                'allday': inputAllDay.val(),
                'type': $('input[name="type"]:checked').val(),
                'active': $('input[name="active"]:checked').val() ? 1 : 0
            },
            success: function () {
                calendarEventModal.hide();
                toastr.options =
                    {
                        "closeButton": true,
                        "progressBar": true
                    }
                toastr.success("Wpis został zaktualizowany");
            },
            error: function (result) {
                if (result.responseJSON.data) {
                    alert.html('');
                    $.each(result.responseJSON.data, function (key, value) {
                        alert.show();
                        alert.append('<span>' + value + '</span>');
                    });
                }
            },
            complete: () => {
                // Re-enable the submit button
                submitButton.disabled = false;
            }
        });
    });
}


function initEventModal(t = null){
    const modal = document.getElementById('portletModal'),
        calendarEventModal = new bootstrap.Modal(modal),
        form = document.getElementById('modalForm'),
        inputInvestment = $('#inputInvestment'),
        inputDepartment = $('#inputDepartment'),

        inputProperty = $('#inputProperty'),
        inputStorage = $('#inputStorage'),
        inputParking = $('#inputParking'),

        inputDate = $('#inputDate'),
        inputTime = $('#inputTime'),
        inputActivity = $('#inputActivity'),
        inputClientId = $('#inputClientId'),
        inputNote = $('#inputNote'),
        inputAllDay = $('#inputAllDay');

    calendarEventModal.show();
    modal.addEventListener('shown.bs.modal', function () {

        inputDate.datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            language: "pl"
        });

        const elements = document.querySelectorAll(".btn-group label");
        for (let i = 0; i < elements.length; i++) {
            elements[i].addEventListener("click", function(e) {
                const input = document.getElementById("inputActivity");
                input.placeholder = e.currentTarget.dataset.bsTitle;
            });
        }
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger : 'hover'
            })
        });
    })

    modal.addEventListener('hidden.bs.modal', function () {
        $('#portletModal').remove();
        refreshCalendar();
    })

    // Init modal form
    const alert = $('.alert-danger');
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;

        $.ajax({
            url: "/admin/crm/calendar",
            method: 'POST',
            data: {
                '_token': token,
                'investment_id': inputInvestment.val(),
                'department_id': inputDepartment.val(),
                'property_id': inputProperty.val(),
                'parking_id': inputParking.val(),
                'storage_id': inputStorage.val(),
                'start': inputDate.val(),
                'end': inputDate.val(),
                'time': inputTime.val(),
                'name': inputActivity.val(),
                'note': inputNote.val(),
                'client_id': inputClientId.val(),
                'allday': inputAllDay.val(),
                'type': $('input[name="type"]:checked').val()
            },
            success: function () {
                calendarEventModal.hide();
                toastr.options =
                    {
                        "closeButton": true,
                        "progressBar": true
                    }
                toastr.success("Wpis został poprawnie dodany");
            },
            error: function (result) {
                if (result.responseJSON.data) {
                    alert.html('');
                    $.each(result.responseJSON.data, function (key, value) {
                        alert.show();
                        alert.append('<span>' + value + '</span>');
                    });
                }
            },
            complete: () => {
                // Re-enable the submit button
                submitButton.disabled = false;
            }
        });
    });
}