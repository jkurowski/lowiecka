<ul class="list-group list-group-flush">
    <li class="list-group-item @if(Route::is('front.developro.plan')) active @endif"><a href="{{ route('front.developro.plan', ['slug' => $investment->slug]) }}">Plan inwestycji</a></li>
    <li class="list-group-item @if(Route::is('front.developro.show')) active @endif"><a href="{{ route('front.developro.show', ['slug' => $investment->slug]) }}">Opis inwestycji</a></li>
    @if($investment->pages->count() > 0)
        @foreach($investment->pages as $invest_page)
            <li class="list-group-item @isset($investment_page) @if($invest_page->id == $investment_page->id) active @endif @endisset"><a href="{{ route('front.developro.page', ['slug' => $investment->slug, 'page' => $invest_page->slug]) }}">{{ $invest_page->title }}</a></li>
        @endforeach
    @endif
</ul>