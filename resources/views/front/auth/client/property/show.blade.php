@extends('front.auth.client.layouts.layout')

@section('content')
    <div class="container-fluid">
        <div class="card-head container-fluid">
            <div class="row">
                <div class="col-12 pl-0">
                    <h4 class="page-title"><i class="fe-tablet"></i>Podgląd mieszkania</h4>
                </div>
            </div>
        </div>

        <div class="container-fluid p-0" id="portlets">
            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body card-body-rem">
                            <div class="row">
                                <div class="col-3">
                                    @if ($property->file)
                                        <picture>
                                            <source type="image/webp"
                                                    srcset="{{ asset('/investment/property/thumbs/webp/' . $property->file_webp) }}">
                                            <source type="image/jpeg"
                                                    srcset="{{ asset('/investment/property/thumbs/' . $property->file) }}">
                                            <img src="{{ asset('/investment/property/thumbs/' . $property->file) }}"
                                                 alt="{{ $property->name }}">
                                        </picture>
                                    @else
                                        <img src="https://placehold.co/600x400?text=Brak+zdjęcia" alt="">
                                    @endif
                                </div>
                                <div class="col-6">
                                    <div class="status-badge mb-2 d-flex">
                                        <span class="badge room-list-status-{{ $property->status }}">{{ roomStatus($property->status) }}</span>
                                    </div>
                                    <h2>{{ $property->name }}</h2>
                                    <h4>{{ $property->investment->name }}</h4>
                                    <hr>
                                    <ul>
                                        <li>Powierzchnia: {{ $property->area }}m<sup>2</sup></li>
                                        <li>Ilość pokoi: {{ $property->rooms }}</li>
                                        <li>Piętro: {{ $property->floor->number }}</li>
                                        <li>Aneks kuchenny</li>
                                        <li>Data podpisania umowy: 03.07.2024</li>
                                    </ul>
                                </div>
                                <div class="col-3 d-flex justify-content-center align-items-center text-center">
                                    <div>
                                        @if($property->price)
                                            <h4>Kwota za całość:</h4>
                                            <h3>{{ number_format($property->price, 2, '.', ' ') }} zł</h3>
                                        @endif

                                        @if($latestPayment)
                                            <div id="upcomingPayment">
                                                <h4>Kwota najbliższej płatności:</h4>
                                                <h3>{{ $latestPayment->amount }}</h3>
                                            </div>

                                            <div id="upcomingDate">
                                                <h4 class="mt-3">Najbliższy termin płatności:</h4>
                                                <h3>{{ $latestPayment->due_date }}</h3>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 pt-3 border-top">
                                <div class="col-2 d-flex align-items-center text-center">
                                    <h4>Powiązane produkty:</h4>
                                </div>
                                <div class="col-3 border-start text-center">
                                    <h4><i class="fe-check-circle text-success"></i> Komórka lokatorska nr. 4</h4>
                                </div>
                                <div class="col-3 border-start text-center">
                                    <h4><i class="fe-check-circle text-success"></i> Miejsce postojowe nr. 28</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-6">
                    <div class="card h-100">
                        <div class="card-body p-0">
                            <table class="table data-table mb-0 w-100">
                                <thead class="thead-default">
                                <tr>
                                    <th>Termin</th>
                                    <th class="text-center">Wartość</th>
                                    <th class="text-center">Kwota</th>
                                    <th class="text-center">Status</th>
                                </tr>
                                </thead>
                                <tbody class="content" id="tableContent">
                                @foreach ($property->payments as $item)
                                    <tr id="recordsArray_{{ $item->id }}">
                                        <td>{{ $item->due_date }}</td>
                                        <td class="text-center">
                                            {{ $item->percent }}%
                                        </td>
                                        <td class="text-center">
                                            <b>{{ number_format($item->amount, 2, '.', ' ') }} zł</b>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $dueDate = \Carbon\Carbon::parse($item->due_date);
                                                $currentDate = \Carbon\Carbon::now();
                                            @endphp

                                            @if($currentDate->gt($dueDate) && $item->status == 0)
                                                <div class="paid-status paid-status-failed">
                                                    <i class="fe-calendar"></i>
                                                </div>
                                            @elseif($currentDate->gt($dueDate) && $item->status == 1)
                                                <div class="paid-status paid-status-paid">
                                                    <i class="fe-check-square"></i>
                                                </div>
                                            @else
                                                <div class="paid-status paid-status-pending">
                                                    <i class="fe-clock"></i>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-6 d-flex align-items-center">
                                    <div class="portlet-title">Pliki</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-overflow">
                                <table class="table mb-0" id="sortable">
                                    <thead class="thead-default">
                                    <tr>
                                        <th>Kategoria</th>
                                        <th>Nazwa</th>
                                        <th>Data przyjęcia</th>
                                        <th>Status</th>
                                        <th>Opiekun</th>
                                        <th class="text-end"><a href="#" class="btn btn-primary">Dodaj zgłoszenie</a></th>
                                    </tr>
                                    </thead>
                                    <tbody class="content">
                                    @foreach($client->issues as $i)
                                        <tr>
                                            <td>{{ $i->type }}</td>
                                            <td>{{ $i->title }}</td>
                                            <td>{{ $i->start }}</td>
                                            <td><span class="badge issue-status-{{$i->status}}">{{ issueStatus($i->status) }}</span></td>
                                            <td>{{ $i->user->name }} {{ $i->user->surname }}</td>
                                            <td class="text-end">
                                                <a href="#"
                                                   class="btn action-edit-button action-button me-1"
                                                   data-bs-toggle="tooltip"
                                                   data-placement="top"
                                                   data-bs-title="Pokaż zgłoszenie">
                                                    <i class="fe-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
