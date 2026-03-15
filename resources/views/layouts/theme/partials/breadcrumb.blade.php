<div class="page-header">
    <div class="page-title">
        <a href="" aria-label="Ir al inicio">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
        </a>
    </div>

    <nav class="breadcrumb-one" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="">Inicio</a>
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
</div>