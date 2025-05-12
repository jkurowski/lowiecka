<header id="header">
    <div class="container">
        <div class="row">
            <div class="col-3">
                <div id="logo" class="d-flex justify-content-start align-items-center h-100">
                    <a href="/">
                        <img src="{{ asset('images/logo.png') }}" alt="" width="223">
                    </a>
                </div>
            </div>
            <div class="col-9">
                <div class="row">
                    <div class="col-12">
                        <div class="top text-end">
                            <a href="">+48 690 256 457</a>
                        </div>
                    </div>
                    <div class="col-12">
                        <nav>
                            <ul class="mb-0 list-unstyled h-100">
                                <li class="{{ Request::routeIs('front.investment') ? 'active' : '' }}"><a href="{{ route('front.investment') }}">O INWESTYCJI</a></li>
                                <li class="{{ Request::routeIs('front.developro.*') ? 'active' : '' }}"><a href="{{ route('front.developro.show') }}">MIESZKANIA</a></li>
                                <li><a href="{{ route('static.page', ['page' => 'lokalizacja']) }}">LOKALIZACJA</a></li>
                                <li class="{{ Request::routeIs('front.gallery.*') ? 'active' : '' }}"><a href="{{ route('front.gallery.index') }}">GALERIA</a></li>
                                <li class="{{ Request::routeIs('front.investor') ? 'active' : '' }}"><a href="{{ route('front.investor') }}">INWESTOR</a></li>
                                <li><a href="{{ route('static.page', ['page' => 'finansowanie']) }}">FINANSOWANIE</a></li>
                                <li class="{{ Request::routeIs('front.contact') ? 'active' : '' }}"><a href="{{ route('front.contact') }}">KONTAKT</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
