<div class="card-header border-bottom card-nav">
    <nav class="nav">
        <a class="nav-link {{ Request::routeIs('admin.crm.statistics.index') ? 'active' : '' }}" href="{{ route('admin.crm.statistics.index') }}"><span class="fe-inbox"></span> Formularze</a>
        <a class="nav-link {{ Request::routeIs('admin.crm.statistics.rooms') ? ' active' : '' }}" href="{{ route('admin.crm.statistics.rooms') }}"><span class="fe-home"></span> Mieszkania</a>
    </nav>
</div>