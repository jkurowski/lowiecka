<!doctype html>
<html lang="pl">

<head>
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <title>DeveloPro @yield('meta_title')</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="robots" content="noindex, nofollow">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Prefetch -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/admin.min.css') }}">

    @stack('style')

</head>

<body class="lang-pl">
    <div id="admin">
        <div class="sidemenu-holder">
            <div id="sidemenu">
                <ul class="list-unstyled mb0">
                    @php
                        $modules = explode(',', env('MODULES', ''));
                    @endphp
                    <li class="active">
                        <a class='d-flex gap-1 align-items-center' href="{{ route('admin.settings.seo.index') }}">
                            <i class="fe-home"></i>
                            <span> DeveloCMS </span>
                            <i class='fe-settings ms-auto with-hover'></i>
                        </a>

                        <ul class="sub-menu">
                            @if (in_array('page', $modules))
                                <li {{ Request::routeIs('admin.page.*') ? 'class=active' : '' }}>
                                    <a href="{{ route('admin.page.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span> Strony</a>
                                </li>
                            @endif
                            @if (in_array('slider', $modules))
                                <li {{ Request::routeIs('admin.slider.*') ? 'class=active' : '' }}>
                                    <a href="{{ route('admin.slider.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span> Slider</a>
                                </li>
                            @endif
                                <li {{ Request::routeIs('admin.gallery.*') ? 'class=active' : '' }}>
                                    <a href="{{ route('admin.gallery.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span> Galeria</a>
                                </li>
                            @if (in_array('user', $modules))
                                <li {{ Request::routeIs('admin.user.*') ? 'class=active' : '' }}>
                                    <a href="{{ route('admin.user.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span> Użytkownicy</a>
                                </li>
                            @endif
                            @if (in_array('greylist', $modules))
                                <li {{ Request::routeIs('admin.greylist.*') ? 'class=active' : '' }}>
                                    <a href="{{ route('admin.greylist.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span> Blokada dostępu</a>
                                </li>
                            @endif
                            @if (in_array('settings', $modules))
                                <li {{ Request::routeIs('admin.settings.*') ? 'class=active' : '' }}>
                                    <a href="{{ route('admin.settings.seo.index') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span> Ustawienia</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li class="active">
                        <a class='d-flex gap-1 align-items-center' href="{{ route('admin.crm.settings.index') }}">
                            <i class="fe-cpu"></i>
                            <span> DeveloCRM </span>
                            <i class='fe-settings ms-auto with-hover'></i>
                        </a>
                        <ul class="sub-menu">
                            <li {{ Request::routeIs('admin.crm.inbox.*') ? 'class=active' : '' }}>
                                <a href="{{ route('admin.crm.inbox.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Leads</a>
                            </li>
                            <li {{ Request::routeIs('admin.crm.statistics.*') ? 'class=active' : '' }}>
                                <a href="{{ route('admin.crm.statistics.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Statystyki</a>
                            </li>
                            <li class="{{ Request::routeIs('admin.developro.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.developro.investment.index') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span> Inwestycje
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>
        <div id='modalNewCompany'></div>
        <div id="modalNewUser"></div>
        <div id="content">
            <header id="header-navbar">
                <h1><a href="" class="logo"><span>kCMS v4.2</span></a></h1>

                <a href="#" id="togglemenu"><span class="fe-menu"></span></a>
                <div class="user">
                    <ul>
                        <li class="pt-0 pb-0">
                            <a href="{{ route('admin.crm.calendar.index') }}"
                               class="header-btn">
                                <i class="fe-calendar"></i> Kalendarz
                            </a>
                        </li>
                        <li class="pt-0 pb-0">
                            <a href="#"
                               class="header-btn btn-add-user">
                                <i class="fe-plus-square"></i> Nowy lead
                            </a>
                        </li>
                        <li class="pt-0 pb-0">
                            <a href="#"
                               class="header-btn btn-add-company"
                               id="btnNewCompany">
                                <i class="fe-plus-square"></i> Nowa firma
                            </a>
                        </li>
                        <li class="pt-0 pb-0">
                            <a href="{{ route('admin.crm.offer.create') }}"
                               class="header-btn">
                                <i class="fe-plus-square"></i> Nowa oferta
                            </a>
                        </li>
                        <li><span class="fe-calendar"></span> <span id="livedate"><?= date('d-m-Y') ?></span></li>
                        <li><span class="fe-clock"></span> <span id="liveclock"></span></li>
                        <li><span class="fe-user"></span> Witaj: <b>{{ Auth::user()->name }}</b></li>
                        <li>
                            <a title="Idź do strony" href="/" target="_blank">
                                <span class="fe-monitor"></span> Idź do strony
                            </a>
                        </li>
                        <li>
                            <a title="Wyloguj" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><span class="fe-lock"></span> Wyloguj</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </header>
            <div class="content">
                @yield('submenu')

                @yield('content')
            </div>
        </div>
    </div>

    <!--Google font style-->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- jQuery -->
    <script src="{{ asset('/js/jquery.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/bootstrap.bundle.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/jquery-ui.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/cms.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('/js/utils.js') }}" charset="utf-8"></script>
    <script>
        // Company Modal Handler
        document.getElementById('btnNewCompany').addEventListener('click', async (e) => {
            e.preventDefault();
            const button = $(e.currentTarget);
            button.css('pointer-events', 'none');

            const modalHolder = document.getElementById('modalNewCompany');
            modalHolder.innerHTML = '';

            try {
                const response = await fetch('{{ route('admin.crm.clients.create-company') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const html = await response.text();

                if (html) {
                    modalHolder.innerHTML = html;
                    const modalElement = modalHolder.querySelector('.modal');
                    if (modalElement) {
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                        handleCompanyModalForm(modal);
                        button.css('pointer-events', 'auto');
                    }
                } else {
                    toastr.error('Wystąpił błąd podczas ładowania widoku');
                }
            } catch (error) {
                toastr.error('Wystąpił błąd podczas ładowania widoku');
                console.error('Error:', error);
            }
        });

        const getGusData = (selector) => {

            const button = document.querySelector(selector);
            if (!button) return;
            button.addEventListener('click', async (e) => {
                e.preventDefault();
                const nip = document.querySelector('#modalNewCompany #nip').value;
                if (!nip) return;

                try {
                    const response = await fetch('{{ route('admin.nip.index') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            nip: nip
                        })
                    })
                    if (response.ok) {
                        const data = await response.json();
                        if (data.status === 'success') {
                            toastr.success(data.message || 'Dane zostały pobrane pomyślnie');
                            fillFormFields(data.data[0]);
                        }
                    } else {
                        const data = await response.json();
                        toastr.error(data.message);
                    }

                } catch (error) {
                    console.error(error);


                }
            });
        };

        function fillFormFields(data) {
            const fieldsMapping = {
                'regon': 'regon',
                'nip': 'nip',
                'name': 'company_name',
                'province': 'province',
                'district': 'district',
                'community': 'community',
                'city': 'city',
                'propertyNumber': 'house_number',
                'apartmentNumber': 'apartment_number',
                'zipCode': 'post_code',
                'street': 'street',
                'postCity': 'post_city'
            };

            Object.keys(fieldsMapping).forEach(key => {
                const field = document.querySelector(`#modalNewCompany [name="${fieldsMapping[key]}"]`);
                console.log(`#modalNewCompany[name="${fieldsMapping[key]}"]`)
                if (field) {
                    field.value = data[key] || '';
                }
            });
            const addressField = document.querySelector('#modalNewCompany #address');
            if (addressField) {
                addressField.value = `${data.street ?? null} ${data.city ?? null} ${data.apartmentNumber ?? null} ${data.propertyNumber ?? null}, ${data.zipCode ?? null} ${data.postCity ?? null}`;
            }
        }

        function handleCompanyModalForm(modal) {
            getGusData('#modalNewCompany #get_data_from_gus')
            const form = document.getElementById('companyModalForm');
            const alert = form.querySelector('.alert-danger');
            modalFooter = document.querySelector('#companyModalForm .modal-footer');
            modalLoader = document.getElementById('modal-loader');
            modalLoader.style.display = 'none';

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;

                modalFooter.style.display = 'none';
                modalLoader.style.display = 'block';

                const formData = new FormData(form);
                for (const [key, value] of formData.entries()) {
                    console.log(key, value);
                }

                try {
                    const response = await fetch('{{ route('admin.crm.clients.store-modal') }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        method: 'POST',
                        body: formData
                    })
                    if (response.ok) {
                        toastr.success('Firma została dodana pomyślnie');
                        submitButton.disabled = false;
                        modal.hide();
                        modal.dispose();
                    } else {
                        const result = await response.json();
                        if (result.data) {
                            alert.innerHTML = '';
                            Object.values(result.data).forEach(value => {
                                alert.style.display = 'block';
                                alert.innerHTML += `<span>${value}</span>`;
                            });
                        }

                        submitButton.disabled = false;
                        modalFooter.style.display = 'flex';
                        modalLoader.style.display = 'none';
                    }
                } catch (error) {
                    console.error(error);
                    toastr.error('Wystąpił błąd podczas wysyłania formularza');
                }
            });
        }

        // User Modal Handler - Converted to modern JavaScript
        document.querySelector(".btn-add-user").addEventListener('click', async (e) => {
            e.preventDefault();
            const button = $(e.currentTarget);
            button.css('pointer-events', 'none');

            const modalHolder = document.getElementById('modalNewUser');
            modalHolder.innerHTML = '';

            try {
                const response = await fetch('{{ route('admin.crm.clients.create') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const html = await response.text();

                if (html) {
                    modalHolder.innerHTML = html;
                    const modalElement = modalHolder.querySelector('.modal');
                    if (modalElement) {
                        initUserModal('store', modalElement);
                    }
                    button.css('pointer-events', 'auto');
                } else {
                    toastr.error('Wystąpił błąd podczas ładowania widoku');
                }
            } catch (error) {
                toastr.error('Wystąpił błąd podczas ładowania widoku');
                console.error('Error:', error);
            }
        });

        // Separate initialization functions for each modal type
        function initUserModal(action = 'update', modalElement) {
            const bootstrapModal = new bootstrap.Modal(modalElement);
            const form = modalElement.querySelector('form');
            const inputName = modalElement.querySelector('#inputName');
            const inputSurname = modalElement.querySelector('#inputSurname');
            const inputEmail = modalElement.querySelector('#inputEmail');
            const alert = modalElement.querySelector('.alert-danger');
            modalFooter = document.querySelector('#portletModal .modal-footer');
            modalLoader = document.getElementById('modal-loader');
            modalLoader.style.display = 'none';

            bootstrapModal.show();

            modalElement.addEventListener('shown.bs.modal', function() {
                const tooltipTriggerList = modalElement.querySelectorAll('[data-bs-toggle="tooltip"]');
                tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el, { trigger: 'hover' }));
            });

            modalElement.addEventListener('hidden.bs.modal', function() {
                modalElement.remove();
            });

            const url = action === 'update' ? '' : '{{ route('admin.crm.clients.store-modal') }}';
            const method = action === 'update' ? 'PUT' : 'POST';

            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                modalFooter.style.display = 'none';
                modalLoader.style.display = 'block';

                const rules = {};
                modalElement.querySelectorAll('input[type=checkbox][name^=rule]').forEach(checkbox => {
                    rules[checkbox.name] = checkbox.checked ? 1 : 0;
                });

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            name: inputName.value,
                            lastname: inputSurname.value,
                            email: inputEmail.value,
                            status: 1,
                            source: 1,
                            ...rules
                        })
                    });

                    if (response.ok) {
                        bootstrapModal.hide();
                        toastr.options = {
                            closeButton: true,
                            progressBar: true
                        };
                        toastr.success("Nowy lead dodany");
                        submitButton.disabled = false;
                    } else {
                        const result = await response.json();
                        if (result.data) {
                            alert.innerHTML = '';
                            Object.values(result.data).forEach(value => {
                                alert.style.display = 'block';
                                alert.innerHTML += `<span>${value}</span>`;
                            });
                        }

                        submitButton.disabled = false;
                        modalFooter.style.display = 'flex';
                        modalLoader.style.display = 'none';
                    }
                } catch (error) {
                    console.error(error);
                    toastr.error('Wystąpił błąd podczas wysyłania formularza');
                }
            });
        }
    </script>
    @stack('scripts')
</body>

</html>
