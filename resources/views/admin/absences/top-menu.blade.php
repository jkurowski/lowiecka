@include('components.top-menu', [
    'items' => [
        [
            'name' => 'Nieobecności',
            'icon' => 'fe-calendar',
            'route' => route('admin.absences.index'),
            'active' => request()->routeIs('admin.absences.index'),
        ],
        [
            'name' => 'Nowa nieobecność',
            'icon' => 'fe-plus',
            'route' => route('admin.absences.create'),
            'active' => request()->routeIs('admin.absences.create'),
        ],
    ],
])
