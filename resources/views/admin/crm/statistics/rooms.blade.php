@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-bar-chart-line-"></i>Statystyki</h4>
                </div>
            </div>
        </div>

        @include('admin.crm.statistics.statistics_shared.menu')

        <div class="card mt-3">
            <div class="card-header card-nav mt-0">
                <nav class="nav">
                    <div class="container-fluid">
                        <form class="row">
                            <div class="col">
                                <label for="form_campaign" class="form-label">Inwestycja</label>
                                <select class="form-select" id="form_investment" name="investment">
                                    <option value="">Wszystkie</option>
                                    @foreach ($investments as $name => $id)
                                        <option value="{{ $id }}"
                                            @if (request('investment') == $id) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="form_room" class="form-label">Pokoje</label>
                                <select class="form-select" id="form_room" name="room">
                                    <option value="">Wszystkie</option>
                                    @foreach ($uniqueRooms as $ur)
                                        <option value="{{ $ur }}"
                                            @if (request('room') == $ur) selected @endif>{{ $ur }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="form_status" class="form-label">Status</label>
                                <select class="form-select" id="form_status" name="status">
                                    <option value="">Wszystkie</option>
                                    @foreach (\App\Helpers\RoomStatusMaper::getAll() as $status => $name)
                                        <option value="{{ $status }}"
                                            @if (request('status') == $status) selected @endif>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="form_date_from" class="form-label">Data od</label>
                                        <input type="text" class="form-control" id="form_date_from" name="date_from"
                                            value="{{ request()->get('date_from') }}">
                                    </div>
                                    <div class="col-6">
                                        <label for="form_date_to" class="form-label">Data do</label>
                                        <input type="text" class="form-control" id="form_date_to" name="date_to"
                                            value="{{ request()->get('date_to') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100" id="form_button">Generuj</button>
                            </div>
                        </form>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div id="portlets" class="card mt-3 bg-transparent shadow-none">
            <div class="card-body card-body-rem p-0">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="row" data-statuses-row>
                            @foreach ($statusCounts as $status => $count)
                                <div class="col-3">
                                    <div class="floor-status floor-status-{{ $status }} rounded" role="button"
                                        data-status="{{ $status }}" data-selected='true'>
                                        {{ roomStatus($status) }}<b class="float-end">{{ $count }}</b>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12 d-flex align-items-center">
                                        <div class="portlet-title">Najczęściej odwiedzane</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <ul class="list-group list-group-flush">
                                    @foreach ($topProperties as $tp)
                                        <li class="list-group-item" data-item-status="{{ $tp->status }}">
                                            <div class="ms-0 me-auto text-start line-status-{{ $tp->status }}">
                                                <p>{{ $tp->name }}</p>
                                                <small class="text-muted">(pokoje: {{ $tp->rooms }})</small>
                                            </div>
                                            <span class="badge">{{ $tp->views }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    {{-- @dump($lowestProperties)
                    @dump($topProperties) --}}
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-12 d-flex align-items-center">
                                        <div class="portlet-title">Najrzadziej odwiedzane</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body text-center">
                                <ul class="list-group list-group-flush">
                                    @foreach ($lowestProperties as $tp)
                                        <li class="list-group-item" data-item-status="{{ $tp->status }}">
                                            <div class="ms-0 me-auto text-start line-status-{{ $tp->status }}">
                                                <p>{{ $tp->name }}</p>
                                                <small class="text-muted">(pokoje: {{ $tp->rooms }})</small>
                                            </div>
                                            <span class="badge">{{ $tp->views }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-4">
        @include('admin.crm.statistics._reserved-properties')
    </div>
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.min.js') }}" charset="utf-8"></script>
        <script src="{{ asset('/js/datepicker/bootstrap-datepicker.pl.min.js') }}" charset="utf-8"></script>
        <link href="{{ asset('/js/datepicker/bootstrap-datepicker3.css') }}" rel="stylesheet">
        <script>
            $('#form_date_to, #form_date_from').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                language: "pl"
            });


            document.addEventListener('DOMContentLoaded', () => {
                // State 
                const state = {
                    selectedStatuses: new Set() // Set because of unique values
                };

                // DOM Elements
                const statusesRow = document.querySelector('[data-statuses-row]');
                if (!statusesRow) return; // Guard clause

                const handleInitialState = () => {
                    statusesRow.querySelectorAll('.floor-status').forEach(el => {
                        if (el.dataset.selected === 'true') state.selectedStatuses.add(el.dataset.status);
                    })
                    console.log('Initial State:', Array.from(state.selectedStatuses));
                }

                // Functions
                const handleStatusClick = (e) => {
                    const target = e.target.closest('.floor-status');
                    if (!target) return;

                    const status = target.dataset.status;
                    const isSelected = target.dataset.selected === 'true';

                    // Toggle selection
                    target.dataset.selected = (!isSelected).toString();

                    // Update state
                    isSelected ? state.selectedStatuses.delete(status) : state.selectedStatuses.add(status);

                    // Dispatch custom event
                    window.dispatchEvent(new CustomEvent('selectedStatusChanged', {
                        detail: {
                            statusId: status,
                            selected: !isSelected,
                            selectedStatuses: Array.from(state.selectedStatuses)
                        }
                    }));
                    setElementOpacity(target, !isSelected);
                };
                const setElementOpacity = (el, selected = false) => {
                    el.style.transition = 'opacity 0.3s ease';
                    el.style.opacity = selected ? 1 : 0.3;
                }

                const handleSelectedStatusChanged = (e) => {
                    const {
                        statusId,
                        selected,
                        selectedStatuses
                    } = e.detail;
                    console.log('@start');
                    console.log('Clicked Status:', statusId);
                    console.log('Selected:', selected);
                    console.log('Selected Statuses:', selectedStatuses);
                    console.log('@end');


                    const items = document.querySelectorAll('[data-item-status]');
                    items.forEach(el => {
                        const isVisible = selectedStatuses.includes(el.dataset.itemStatus);
                        setItemVisibility(el, isVisible);
                    })

                }
                const setItemVisibility = (el, visible = true) => {
                    el.style.display = visible ? 'flex' : 'none';
                }

                // Execution
                handleInitialState();
                statusesRow.addEventListener('click', handleStatusClick);
                window.addEventListener('selectedStatusChanged', handleSelectedStatusChanged);
            });
        </script>
    @endpush
@endsection
