<div class="card-header border-bottom card-nav">
    <nav class="nav">
        <a class="nav-link {{ Request::routeIs('admin.crm.clients.*') ? ' active' : '' }}" href="{{ route('admin.crm.clients.index') }}">
            <span class="fe-home"></span>Klienci
        </a>
        <a class="nav-link {{ Request::routeIs('admin.crm.offer.index') ? ' active' : '' }}" href="{{ route('admin.crm.offer.index') }}">
            <span class="fe-file"></span>Oferty
        </a>
        <a class="nav-link {{ Request::routeIs('admin.crm.offer.drafts') ? ' active' : '' }}" href="{{ route('admin.crm.offer.drafts') }}">
            <span class="fe-file"></span>Szkice ofert
        </a>
        <a class="nav-link {{ Request::routeIs('admin.crm.offer.templates') ? ' active' : '' }}" href="{{ route('admin.crm.offer.templates') }}">
            <span class="fe-file"></span>Szablony ofert
        </a>
        <a class="nav-link {{ Request::routeIs('admin.crm.offer.content-templates') ? ' active' : '' }}" href="{{ route('admin.crm.offer.content-templates') }}">
            <span class="fe-file"></span>Szablony treści
        </a>
    </nav>
</div>