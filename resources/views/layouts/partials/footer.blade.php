<footer class="page-footer bg-black text-white">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-5 text-center text-lg-start">
                <div class="footer-box">
                    <img src="{{ asset('../images/logo-footer.png') }}" alt="" width="238" height="52" class="mb-4 flogo">
                    <p>Adres inwestycji:</p>
                    <p>ul. Łowicka 100</p>
                    <p>96-100 Skierniewice</p>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="footer-box">
                    <h4 class="be-vietnam-pro-semibold">Biuro Sprzedaży</h4>
                    <p>EPS Development Sp. z o.o.</p>
                    <p>Kozietulskiego 14</p>
                    <p>96-100 Skierniewice</p>
                    <p>&nbsp;</p>
                    <p><a href="mailto:biuro@epsdevelopment.pl">biuro@epsdevelopment.pl</a></p>
                    <p><a href="mailto:+48690256457">690-256-457</a></p>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <div class="footer-box">
                    <h4 class="be-vietnam-pro-semibold">Menu</h4>
                    <ul class="list-unstyled mb-0">
                        <li><a href="{{ route('front.investment') }}">O inwestycji</a></li>
                        <li><a href="{{ route('front.developro.show') }}">Mieszkania</a></li>
                        <li><a href="{{ route('static.page', ['page' => 'lokalizacja']) }}">Lokalizacja</a></li>
                        <li><a href="{{ route('front.gallery.index') }}">Galeria</a></li>
                        <li><a href="{{ route('front.investor') }}">Inwestor</a></li>
                        <li><a href="{{ route('front.contact') }}">Kontakt</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-2">
                <div class="footer-box">
                    <h4 class="be-vietnam-pro-semibold">Informacje</h4>
                    <ul class="list-unstyled mb-0">
                        <li class="d-none"><a href="">Regulamin</a></li>
                        <li><a href="{{ route('static.page', ['page' => 'polityka-prywatnosci']) }}">Polityka prywatności</a></li>
                        <li class="d-none"><a href="">Ciasteczka</a></li>
                        <li><a href="{{ route('static.page', ['page' => 'finansowanie']) }}">Finansowanie</a></li>
                        <li class="d-none"><a href="">Pod klucz</a></li>
                    </ul>
                    <div class="mt-4">
                        <a href="http://epsdevelopment.pl/" target="_blank">
                            <img src="{{ asset('../images/eps-dev-logo.png') }}" alt="" width="150" height="83">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <p>© 2025 Łowicka 100</p>
                </div>
                <div class="col-12 col-sm-6 text-center text-sm-end">
                    <p>Realizacja: <a href="https://www.4dl.pl/" target="_blank">4Dl.pl</a> | Powered by: <a href="https://www.developro.pl/" target="_blank">DeveloPro.pl</a></p>
                </div>
            </div>
        </div>
    </div>
</footer>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600&family=DM+Serif+Display&display=swap" rel="stylesheet">
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/slick.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
