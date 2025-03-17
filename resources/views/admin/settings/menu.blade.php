@php
    $modules = explode(',', env('SETTING_MODULES', ''));
@endphp
<div class="card-header border-bottom card-nav">
    <nav class="nav">
        @if (in_array('seo', $modules))
        <a class="nav-link {{ Request::routeIs('admin.settings.seo.index') ? 'active' : '' }}" href="{{ route('admin.settings.seo.index') }}"><span class="fe-globe"></span> Główne ustawienia</a>
        @endif
        @if (in_array('socialmedia', $modules))
        <a class="nav-link {{ Request::routeIs('admin.settings.social.index') ? ' active' : '' }}" href="{{ route('admin.settings.social.index') }}"><span class="fe-hash"></span> Social Media</a>
        @endif
        @if (in_array('log', $modules))
        <a class="nav-link {{ Request::routeIs('admin.log.*') ? 'active' : '' }}" href="{{route('admin.log.index')}}"><span class="fe-hard-drive"></span> Logi PA</a>
        @endif
        @if (in_array('popup', $modules))
        <a class="nav-link {{ Request::routeIs('admin.settings.popup.index') ? 'active' : '' }}" href="{{route('admin.settings.popup.index')}}"><span class="fe-airplay"></span> Baner na start</a>
        @endif
        @if (in_array('facebook', $modules))
        <a class="nav-link {{ Request::routeIs('admin.settings.facebook.*') ? 'active' : '' }}" href="{{route('admin.settings.facebook.index')}}"><span class="fe-facebook"></span> Facebook</a>
        @endif
        @if (in_array('customfields', $modules))
        <a class="nav-link {{ Request::routeIs('admin.crm.custom-fields.*') ? 'active' : '' }}" href="{{route('admin.crm.custom-fields.index')}}"><span class="fe-book-open"></span> Słowniki / etykiety</a>
        @endif
        @if (in_array('rodo', $modules))
        <a class="nav-link {{ Request::routeIs('admin.rodo.rules.index') ? ' active' : '' }}" href="{{ route('admin.rodo.rules.index') }}"><span class="fe-check-square"></span> RODO: regułki</a>
        <a class="nav-link {{Request::routeIs('admin.rodo.settings.index') ? ' active' : ''}}"  href="{{ route('admin.rodo.settings.index') }}"><span class="fe-settings"></span> RODO: ustawienia</a>
        @endif
    </nav>
</div>