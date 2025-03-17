@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-6 pl-0">
                    <h4 class="page-title"><svg viewBox="0 0 24 24" width="19" height="19" stroke="#00acc1" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" style="margin-right:12px"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>Odbiór lokalu: {{ $property->name }}</h4>
                </div>
                <div class="col-6 d-flex justify-content-end align-items-center form-group-submit"></div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body card-body-rem">
                <div class="container-fluid pt-2 pb-2">
                    <div class="row">
                        <div class="col-8">
                            @if($property->file)
                                <canvas id="imageCanvas" style="width: 100%; height: auto; position: sticky; top: 80px"></canvas>
                                <script>
                                    const canvas = document.getElementById('imageCanvas');
                                    const ctx = canvas.getContext('2d');
                                    const image = new Image();

                                    let originalWidth, originalHeight;
                                    let points = [
                                        @foreach($property->todos as $task)
                                        @if($task->x && $task->y)  // Ensure both x and y exist
                                        {
                                            x: {{ $task->x }},
                                            y: {{ $task->y }},
                                            id: {{ $task->id }},
                                            text: "{{ addslashes($task->text) }}",  // Escaping text for JavaScript
                                        }
                                        @if(!$loop->last), @endif  // Add a comma unless it's the last item
                                        @endif
                                        @endforeach
                                    ];

                                    function addHoverToTask() {
                                        const taskList = document.getElementById('todo');

                                        taskList.addEventListener('mouseover', (e) => {
                                            let task = e.target;
                                            while (task && task.tagName !== 'LI') {
                                                task = task.parentNode;
                                            }

                                            if (task && task.tagName === 'LI') {
                                                const taskX = task.getAttribute('data-x');
                                                const taskY = task.getAttribute('data-y');

                                                if (taskX !== null && taskY !== null) {
                                                    const taskXValue = parseFloat(taskX);
                                                    const taskYValue = parseFloat(taskY);

                                                    const scale = canvas.width / originalWidth;
                                                    const scaledX = taskXValue * scale;
                                                    const scaledY = taskYValue * scale;

                                                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                                                    drawImage();
                                                    redrawPoints();
                                                    animatePulse(scaledX, scaledY, scale);
                                                }
                                            }
                                        });

                                        taskList.addEventListener('mouseout', (e) => {
                                            let task = e.target;
                                            while (task && task.tagName !== 'LI') {
                                                task = task.parentNode;
                                            }

                                            if (task && task.tagName === 'LI') {
                                                if (pulseAnimationFrame) {
                                                    cancelAnimationFrame(pulseAnimationFrame);
                                                    pulseAnimationFrame = null;
                                                }

                                                ctx.clearRect(0, 0, canvas.width, canvas.height);
                                                drawImage();
                                                redrawPoints();
                                            }
                                        });
                                    }

                                    let pulseAnimationFrame = null;
                                    function resizeCanvas() {
                                        const containerWidth = canvas.clientWidth;
                                        const scale = containerWidth / originalWidth;
                                        canvas.width = originalWidth * scale;
                                        canvas.height = originalHeight * scale;
                                        drawImage();
                                        redrawPoints();
                                    }

                                    function drawImage() {
                                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                                        ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
                                    }

                                    function drawPoint(x, y, radius, color) {
                                        ctx.beginPath();
                                        ctx.arc(x, y, radius, 0, 2 * Math.PI);
                                        ctx.fillStyle = color;
                                        ctx.fill();
                                        ctx.closePath();
                                    }

                                    function redrawPoints() {
                                        const scale = canvas.width / originalWidth;
                                        points.forEach(point => {
                                            const scaledX = point.x * scale;
                                            const scaledY = point.y * scale;
                                            drawPoint(scaledX, scaledY, 5, 'red');
                                        });
                                    }

                                    canvas.addEventListener('click', (e) => {
                                        const rect = canvas.getBoundingClientRect();
                                        const mouseX = e.clientX - rect.left;
                                        const mouseY = e.clientY - rect.top;

                                        const scale = canvas.width / originalWidth;
                                        const originalX = mouseX / scale;
                                        const originalY = mouseY / scale;

                                        points.push({ x: originalX, y: originalY, saved: false });
                                        drawPoint(mouseX, mouseY, 5, 'red');
                                        triggerModalButton(originalX, originalY);
                                    });

                                    canvas.addEventListener('touchstart', (e) => {
                                        const rect = canvas.getBoundingClientRect();
                                        const touchX = e.touches[0].clientX - rect.left;
                                        const touchY = e.touches[0].touches[0].clientY - rect.top;

                                        const scale = canvas.width / originalWidth;
                                        const originalX = touchX / scale;
                                        const originalY = touchY / scale;

                                        points.push({ x: originalX, y: originalY, saved: false });
                                        drawPoint(touchX, touchY, 5, 'red');
                                        triggerModalButton(originalX, originalY);
                                    });

                                    function triggerModalButton(x, y) {
                                        const button = document.querySelector('.open-modal[data-id="{{ $property->id }}"]');
                                        if (button) {
                                            inputX.value = x;
                                            inputY.value = y;
                                            button.click();
                                        }
                                    }

                                    function clearXY(){
                                        if (inputX && inputY) {
                                            inputX.value = '';
                                            inputY.value = '';
                                        } else {
                                            console.error('Input elements not found');
                                        }
                                    }

                                    function animatePulse(x, y, scale) {
                                        let radius = 5;
                                        let growing = true;

                                        function pulse() {
                                            const maxRadius = 10;
                                            const minRadius = 5;
                                            const pulseSpeed = 0.1;
                                            const color = 'blue';
                                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                                            drawImage();
                                            redrawPoints(scale);

                                            if (growing) {
                                                radius += pulseSpeed;
                                                if (radius >= maxRadius) {
                                                    growing = false;
                                                }
                                            } else {
                                                radius -= pulseSpeed;
                                                if (radius <= minRadius) {
                                                    growing = true;
                                                }
                                            }
                                            drawPoint(x, y, radius, color);
                                            pulseAnimationFrame = requestAnimationFrame(pulse);
                                        }
                                        pulse();
                                    }

                                    document.addEventListener('DOMContentLoaded', () => {
                                        image.src = '{{ asset('/investment/property/'.$property->file) }}';
                                        image.onload = function () {
                                            originalWidth = image.width;
                                            originalHeight = image.height;

                                            resizeCanvas();
                                            window.addEventListener('resize', resizeCanvas);  // Handle window resize as well
                                        };
                                        addHoverToTask();
                                        clearXY();
                                    });
                                </script>
                            @endif
                        </div>
                        <div class="col-4">
                            <div class="ps-2">
                                <h3>{{ $property->name }}</h3>

                                <ul class="list-unstyled list-handover">
                                    <li class="d-flex w-100 justify-content-between"><span>Inwestycja: </span><b>Nazwa inwestycji</b></li>
                                    <li class="d-flex w-100 justify-content-between"><span>Położenie: </span><b>Budynek A / Piętro 1</b></li>
                                    <li class="d-flex w-100 justify-content-between"><span>Klient: </span><b>Jan Kowalski</b></li>
                                </ul>

                                <h5 class="mt-4 mb-0">Lista usterek / zgłoszeń:</h5>
                                <ul id="todo" class="mt-3 list-group list-group-flush">
                                    @if($property->todos->isEmpty())
                                        {{-- TODO: Poprawić pojawianie sie tego --}}
                                        <li class="list-group-item text-center">Brak zgłoszeń</li>
                                    @else
                                        @foreach($property->todos as $task)
                                            <li class="list-group-item p-0 task" data-task-id="{{ $task->id }}" data-x="{{ $task->x }}" data-y="{{ $task->y }}">
                                                <div class="todo-content-wrapper">
                                                    <div class="widget-content-left p-2 me-2">
                                                        <div class="custom-checkbox custom-control">
                                                            <input
                                                                    class="custom-control-input"
                                                                    id="task{{$task->id}}"
                                                                    type="checkbox"
                                                                    {{ $task->completed ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="task{{$task->id}}">&nbsp;</label>
                                                        </div>
                                                    </div>
                                                    <div class="todo-content-left p-2">
                                                        <div class="todo-heading">{{$task->text}}</div>
                                                        <div class="todo-author gap-2 mt-2">
                                                            <i class="fe-user"></i> {{$task->user->name}} {{$task->user->surname}} &nbsp;&nbsp;
                                                            <i class="fe-calendar"></i> {{ $task->created_at->format('Y-m-d') }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="todo-subheading p-2">
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="btn-group w-100" role="group">
                                                                <button type="button" data-bs-toggle="modal" data-bs-target="#customModal" class="btn btn-primary open-modal" data-task-id="{{ $task->id }}">Edytuj</button>
                                                                <button type="button" class="btn btn-danger" onclick="deleteTask({{ $task->id }})">Usuń</button>
                                                                <button type="button" class="btn btn-secondary" onclick="addAttachments({{ $task->id }})">Załącznik</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="customModal" tabindex="-1" aria-labelledby="customModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content"></div>
        </div>
    </div>

    <div class="form-group form-group-submit">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex justify-content-end">
                    <input type="hidden" name="x" value="">
                    <input type="hidden" name="y" value="">
                    <a href="#" class="btn btn-primary open-modal" data-bs-toggle="modal" data-bs-target="#customModal" data-id="{{ $property->id }}">Dodaj wpis</a>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/todo.js') }}" charset="utf-8"></script>
        <link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">

        <script>
            function deleteTask(taskId) {
                $.confirm({
                    title: "Potwierdzenie usunięcia",
                    message: "Czy na pewno chcesz usunąć?",
                    buttons: {
                        Tak: {
                            "class": "btn btn-primary",
                            action: function() {
                                const yesButton = $('#confirmButtons .btn-primary');
                                yesButton.prop('disabled', true);
                                yesButton.addClass('disabled');
                                yesButton.text('Usuwanie...');
                                yesButton.off('click');

                                fetch(`/admin/crm/handover/${taskId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    },
                                })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {

                                            if (taskId) {
                                                const taskElement = document.querySelector(`.task[data-task-id="${taskId}"]`);
                                                if (taskElement) {
                                                    taskElement.remove();
                                                }
                                            }

                                            {{-- TODO: Dodac usuwanie punktu z canvy --}}

                                            toastr.options =
                                                {
                                                    "closeButton" : true,
                                                    "progressBar" : true
                                                }
                                            toastr.success("Notatka poprawnie usunięta");
                                        } else {
                                            alert('Failed to delete task');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Wystąpił błąd podczas usuwania zadania');
                                    })
                                    .finally(() => {
                                        yesButton.prop('disabled', false);
                                        yesButton.removeClass('disabled');
                                        yesButton.text('Tak');
                                    });
                            }
                        },
                        Nie: {
                            "class": "btn btn-secondary",
                            action: function() {}
                        }
                    }
                });
            }
        </script>
    @endpush
@endsection
