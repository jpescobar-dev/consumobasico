@php
    $navItems = [
        ['label' => 'Dashboard', 'icon' => 'fas fa-border-all', 'route' => 'dashboard', 'fallback' => url('/dashboard')],
        ['label' => 'Jurisdicción', 'icon' => 'fas fa-landmark', 'route' => 'jurisdiccion.index', 'fallback' => '#'],
        ['label' => 'Presupuesto', 'icon' => 'fas fa-dollar-sign', 'route' => 'presupuesto.index', 'fallback' => '#'],
        ['label' => 'Mercado Público', 'icon' => 'fas fa-shopping-cart', 'route' => 'mercado-publico.index', 'fallback' => '#'],
        ['label' => 'Iniciativas', 'icon' => 'fas fa-gear', 'route' => 'iniciativas.index', 'fallback' => '#'],
        ['label' => 'Documentos Tributarios', 'icon' => 'far fa-file-lines', 'route' => 'documentos-tributarios.index', 'fallback' => '#'],
        ['label' => 'Consumos Básicos', 'icon' => 'fas fa-bolt', 'route' => 'consumos-basicos.index', 'fallback' => '#'],
        ['label' => 'Sistema', 'icon' => 'fas fa-sliders', 'route' => 'usuarios.index', 'fallback' => '#'],
    ];

    $resolveUrl = function ($item) {
        return !empty($item['route']) && \Illuminate\Support\Facades\Route::has($item['route'])
            ? route($item['route'])
            : ($item['fallback'] ?? '#');
    };

    $isActive = function ($route) {
        return !empty($route) && \Illuminate\Support\Facades\Route::has($route) && request()->routeIs($route);
    };
@endphp

<nav class="topnavbar navbar navbar-expand-lg" aria-label="Menú principal">
    <button class="navbar-toggler topnavbar-toggler" type="button" data-toggle="collapse" data-target="#capjTopNavbar" aria-controls="capjTopNavbar" aria-expanded="false" aria-label="Mostrar menú principal">
        <i class="fas fa-bars"></i>
        <span>Menú</span>
    </button>

    <div class="collapse navbar-collapse" id="capjTopNavbar">
        <ul class="navbar-nav topnavbar-nav">
            @foreach($navItems as $item)
                <li class="nav-item topnavbar-item {{ $isActive($item['route']) ? 'active' : '' }}">
                    <a class="nav-link topnavbar-link" href="{{ $resolveUrl($item) }}" title="{{ $item['label'] }}">
                        <i class="{{ $item['icon'] }} topnavbar-icon"></i>
                        <span class="topnavbar-text">{{ $item['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
