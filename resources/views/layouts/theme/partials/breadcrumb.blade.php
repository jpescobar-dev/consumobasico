<nav class="breadcrumb-one" aria-label="breadcrumb">
    <ol class="breadcrumb justify-content-end">
        <li class="breadcrumb-item">
            <a href="{{ url('/') }}">Inicio</a>
        </li>

        @hasSection('title2')
            <li class="breadcrumb-item">
                <span>@yield('title')</span>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <span>@yield('title2')</span>
            </li>
        @else
            <li class="breadcrumb-item active" aria-current="page">
                <span>@yield('title')</span>
            </li>
        @endif
    </ol>
</nav>
