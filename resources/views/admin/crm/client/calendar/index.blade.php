@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><i class="fe-calendar"></i><a href="{{route('admin.crm.clients.index')}}">Klienci</a><span
                            class="d-inline-flex me-2 ms-2">/</span><a
                            href="{{ route('admin.crm.clients.show', $client->id) }}">{{$client->name}}</a><span
                            class="d-inline-flex me-2 ms-2">/</span>Kalendarz</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit"></div>
            </div>
        </div>
        @include('admin.crm.client.client_shared.menu')
        <div class="row">
            <div class="col-3">
                @include('admin.crm.client.client_shared.aside')
            </div>
            <div class="col-9">
                <div class="card mt-3">
                    <div class="card-body card-body-rem">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @routes('events')
    @push('scripts')
        <script src="{{ asset('/js/fullcalendar/main.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/fullcalendar/pl.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/moment.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/client-calendar.min.js') }}" charset="utf-8"></script>

        <link href="{{ asset('/js/fullcalendar/main.min.css') }}" rel="stylesheet">
        <link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
        <script>
            const token = '{{ csrf_token() }}';
            let calendar;

            document.addEventListener('DOMContentLoaded', function () {
                const calendarEl = document.getElementById('calendar');
                calendar = new FullCalendar.Calendar(calendarEl, {
                    expandRows: true,
                    timeZone: 'local',
                    eventSources: [
                        {
                            url: route('admin.crm.clients.events.show', {{$client->id}})
                        }
                    ],
                    headerToolbar: {
                        end: 'dayGridMonth,timeGridWeek,today prev,next'
                    },
                    initialView: 'dayGridMonth',
                    locale: 'pl',
                    nowIndicator: true,
                    selectable: true,
                    displayEventTime: true,
                    eventDisplay: 'block',
                    allDayText: '',
                    slotDuration: '00:60:00',
                    slotLabelFormat: [
                        {hour: 'numeric', minute: '2-digit'},
                    ],
                    editable: true,
                    eventDrop: function (info) {
                        jQuery.ajax({
                            type: 'PUT',
                            data: {
                                '_token': token,
                                'date': info.event.startStr,
                                'allday': info.event.allDay,
                            },
                            url: route('admin.crm.calendar.event.move', info.event.id),
                            success: function () {
                                toastr.options =
                                    {
                                        "closeButton": true,
                                        "progressBar": true
                                    }
                                toastr.success("Wpis został zaktualizowany");
                            },
                            error: function () {
                                console.log('eventDrop error');
                            }
                        });
                    },
                    dateClick: function (info) {
                        const date = info.dateStr;
                        const allDay = info.allDay;

                        if (window.isDateClickInProgress) return;
                        window.isDateClickInProgress = true;

                        jQuery.ajax({
                            type: 'POST',
                            data: {
                                '_token': token,
                                'date': date,
                                'allDay': +allDay
                            },
                            url: route('admin.crm.clients.events.create', {{$client->id}}),
                            success: function(response) {
                                if(response) {
                                    $('body').append(response);
                                    initEventModal();
                                } else {
                                    alert('Error');
                                }
                            },
                            error: function() {
                                alert('An error occurred.');
                            },
                            complete: function() {
                                window.isDateClickInProgress = false;
                            }
                        });
                    },
                    eventContent: function (arg) {
                        const event = arg.event;
                        return {html: event.title}
                    },
                    eventDidMount: function (info) {
                        const event_date = info.event.start.toLocaleDateString('pl-PL', {weekday: 'long', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'});

                        info.el.setAttribute('data-bs-toggle', 'tooltip');
                        info.el.setAttribute('data-bs-html', 'true');
                        info.el.setAttribute('title', info.event.extendedProps.name +'<span>'+ event_date +'</span>'|| 'No description available');
                        info.el.setAttribute('template', '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>');
                        new bootstrap.Tooltip(info.el, {
                            template: `
                                <div class="tooltip" role="tooltip">
                                    <div class="tooltip-arrow"></div>
                                    <div class="tooltip-inner"></div>
                                </div>`
                        });

                        const popover = new bootstrap.Popover(info.el, {
                            container: 'body',
                            trigger: 'click focus',
                            placement: 'bottom',
                            sanitize: false,
                            html: true,
                            title: info.event.extendedProps.name,
                            content: '<div class="popover-time">' + event_date + '</div>' + (info.event.extendedProps.note ? '<div class="popover-note">' + info.event.extendedProps.note + '</div>' : '') + '<div class="popover-footer"><button type="button" id="btn-confirm" class="btn btn-primary btn-sm action-button" data-remove-id="' + info.event.id + '"><i class="las la-trash-alt"></i></button><div class="form-check"><input class="form-check-input" name="popoverActive" type="checkbox" value="' + info.event.extendedProps.active + '" id="btn-toggle"><label class="form-check-label" for="btn-toggle">Wykonane</label> <button class="btn btn-primary btn-sm" type="button" id="btn-edit" data-edit-id="' + info.event.id + '">Edytuj</button></div>'
                        });
                        info.el.addEventListener('shown.bs.popover', () => {
                            const delButton = document.getElementById('btn-confirm')
                            const toggleButton = document.getElementById('btn-toggle')
                            const editButton = document.getElementById('btn-edit')
                            const checkbox = info.event.extendedProps.active;
                            const iDiv = document.createElement('div');
                            iDiv.className = 'popover-backdrop';
                            document.getElementById('calendar').appendChild(iDiv);

                            var active = info.event.extendedProps.active;

                            if (active) {
                                console.log("Set checkbox true");
                                toggleButton.checked = true;
                            } else {
                                console.log("Set checkbox false");
                                toggleButton.checked = false;
                            }

                            delButton.addEventListener("click", function () {
                                jQuery.confirm({
                                    title: "Potwierdzenie usunięcia",
                                    message: "Czy na pewno chcesz usunąć?",
                                    buttons: {
                                        Tak: {
                                            "class": "btn btn-primary",
                                            action: function () {
                                                jQuery.ajax({
                                                    url: route('admin.crm.calendar.event.destroy', info.event.id),
                                                    type: "DELETE",
                                                    data: {
                                                        _token: '{{ csrf_token() }}'
                                                    },
                                                    success: function () {
                                                        toastr.options =
                                                            {
                                                                "closeButton": true,
                                                                "progressBar": true
                                                            }
                                                        toastr.success("Wpis został poprawnie usunięty");
                                                        info.event.remove();
                                                        popover.hide();
                                                        iDiv.remove();
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

                            toggleButton.addEventListener("click", function () {
                                jQuery.ajax({
                                    url: route('admin.crm.calendar.event.changeStatus', info.event.id),
                                    type: "POST",
                                    data: {
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function (e) {
                                        if (e.status === 0) {
                                            info.event.setProp("color", '#1f9110');
                                        } else {
                                            info.event.setProp("color", '#3788d8');
                                        }
                                        popover.dispose();
                                        iDiv.remove();
                                        calendar.refetchEvents();
                                    }
                                })
                            });

                            editButton.addEventListener("click", function () {
                                // Disable the button to prevent multiple clicks
                                editButton.disabled = true;

                                jQuery.ajax({
                                    url: route('admin.crm.clients.events.edit', [{{$client->id}}, info.event.id]),
                                    type: 'GET',
                                    data: {
                                        _token: token
                                    },
                                    success: function (response) {
                                        if (response) {
                                            popover.hide();
                                            iDiv.remove();
                                            $('body').append(response);
                                            initEventEditModal();
                                        } else {
                                            alert('Error');
                                        }
                                    },
                                    complete: function() {
                                        editButton.disabled = false;
                                    }
                                });
                            });

                            iDiv.addEventListener("click", function () {
                                popover.hide();
                                iDiv.remove();
                            });
                        })
                    },
                });
                calendar.render();
            });

            function refreshCalendar(){
                console.log("Refresh calendar");
                calendar.refetchEvents();
            }
        </script>
    @endpush
@endsection
