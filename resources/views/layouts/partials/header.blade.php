<header id="header">
    <div class="container">
        <div class="row">
            <div class="col-4 col-lg-2 col-xl-3">
                <div id="logo" class="d-flex justify-content-start align-items-center h-100">
                    <a href="/">
                        <img src="{{ asset('images/logo.png') }}" alt="" width="223" class="d-none d-lg-block">
                        <img src="{{ asset('images/logo-footer.png') }}" alt="" width="238" height="52" class="d-block d-lg-none">
                    </a>
                </div>
            </div>
            <div class="col-8 col-lg-10 col-xl-9">
                <div class="row">
                    <div class="col-12">
                        <div class="top text-end">
                            <a href="">+48 690 256 457</a>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-end">
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
                        <div id="triggermenu" class="d-inline-flex d-lg-none"><i class="las la-bars me-4"></i> MENU</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
