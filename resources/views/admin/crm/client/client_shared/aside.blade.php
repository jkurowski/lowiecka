<div class="card mt-3">
    <div class="card-border-header"><i class="fe-user"></i> Dane kontaktowe</div>
    <div class="card-body card-body-rem">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Imię <b class="float-end">{{$client->name}}</b></li>
            <li class="list-group-item">E-mail <b class="float-end">{{$client->mail}}</b></li>
            @if($client->phone)<li class="list-group-item">Telefon <b class="float-end">{{$client->phone}}</b></li>@endif
            @if($client->ip)<li class="list-group-item">IP <b class="float-end">{{$client->ip}}</b></li>@endif
            @if($client->host)<li class="list-group-item">Host <b class="float-end">{{$client->host}}</b></li>@endif
            @if($client->browser)<li class="list-group-item">Przeglądarka: <b class="float-end">{{$client->browser}}</b></li>@endif
            <li class="list-group-item">Data rejestracji: <b class="float-end">{{$client->created_at}}</b></li>
            @if($client->updated_at)<li class="list-group-item">Ostatnia aktualizacja: <b class="float-end">{{$client->updated_at}}</b></li>@endif
            <li class="list-group-item">&nbsp;</li>
            <li class="list-group-item">Status sprzedaży: <b class="float-end">🚧</b></li>
            <li class="list-group-item">Inwestycja: <b class="float-end">🚧</b></li>

            @foreach ($clientRules as $rule)
                <li class="list-group-item">{!! clientRule($rule) !!}</li>
            @endforeach

            @if($client->room)<li class="list-group-item">Pokoje: <b class="float-end">{{$client->room}}</b></li>@endif
            @if($client->area)<li class="list-group-item">Metraż: <b class="float-end">{{$client->area}}</b></li>@endif
            @if($client->budget)<li class="list-group-item">Budżet: <b class="float-end">{{$client->budget}}</b></li>@endif
            @if($client->purpose)<li class="list-group-item">Przeznaczenie: <b class="float-end">{{$client->purpose}}</b></li>@endif
        </ul>
    </div>
</div>
@if($clientLastNotes->count() > 0)
<div class="card mt-3">
    <div class="card-border-header">
        <div class="row">
            <div class="col-6"><i class="fe-book"></i> Notatki </div>
            <div class="col-6 d-flex justify-content-end"><a href="{{ route('admin.crm.clients.notes', $client->id) }}" class="btn btn-primary">Przeglądaj</a></div>
        </div>
    </div>
    <div class="card-body card-body-rem">
        <ul class="list-group list-group-flush">
            @foreach($clientLastNotes as $note)
                <li class="list-group-item">{{ $note->created_at->diffForHumans() }}<span class="float-end w-50">{!! $note->text !!}</span></li>
            @endforeach
        </ul>
    </div>
</div>
@endif
@if($clientLastEvents->count() > 0)
<div class="card mt-3">
    <div class="card-border-header">
        <div class="row">
            <div class="col-6"><i class="fe-calendar"></i> Kalendarz </div>
            <div class="col-6 d-flex justify-content-end"><a href="{{ route('admin.crm.clients.calendar', $client->id) }}" class="btn btn-primary">Przeglądaj</a></div>
        </div>
    </div>
    <div class="card-body card-body-rem">
        <ul class="list-group list-group-flush">
            @foreach($clientLastEvents as $event)
            <li class="list-group-item">{{ $event->created_at->diffForHumans() }}<div class="float-end w-50"><span class="side-event side-event-active-{{ $event->active }}">{!! $event->title !!}</span></div></li>
            @endforeach
        </ul>
    </div>
</div>
@endif
@if($clientLastFiles->count() > 0)
<div class="card mt-3">
    <div class="card-border-header">
        <div class="row">
            <div class="col-6"><i class="fe-file"></i> Ostatnie pliki </div>
            <div class="col-6 d-flex justify-content-end"><a href="{{ route('admin.crm.clients.files', $client->id) }}" class="btn btn-primary">Zarządzaj</a></div>
        </div>
    </div>
    <div class="card-body card-body-rem">
        <ul class="list-group list-group-flush">
            @foreach($clientLastFiles as $file)
                <li class="list-group-item">{{ $file->created_at->diffForHumans() }}<span class="float-end w-50"><a href="{{ asset('/uploads/user_files/'.$file->file) }}" target="_blank">{{ $file->name }} ({{parseFilesize($file->size)}})</a></span></li>
            @endforeach
        </ul>
    </div>
</div>
@endif
