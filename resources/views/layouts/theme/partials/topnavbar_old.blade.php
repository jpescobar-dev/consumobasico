@php
    $isDashboard = request()->routeIs('dashDte', 'dashElectricidad', 'dashAguaPotable');
    $isJurisdiccion = request()->routeIs('cfinancieros.*', 'ccostos.*', 'proveedores.*', 'clientesmedidores.*');
    $isPresupuesto = request()->routeIs('items.*', 'asignaciones.*', 'catalogos.*');
    $isMercadoPublico = request()->routeIs('licitaciones.*', 'ordenescompras.*');
    $isIniciativas = request()->routeIs('proyectos.*');
    $isDocumentos = request()->routeIs('dtes.*', 'excel.import-form');
    $isConsumos = request()->routeIs(
        'consultas.electricidad.*',
        'dashboard.electricidad.filtro',
        'reportes.consumo-electricidad.index',
        'consultas.agua.*'
    );
    $isSistema = request()->routeIs('estados.*', 'users.*');
@endphp

<div class="topbar-nav header navbar" role="banner">
    <nav id="topbar">
        <ul class="navbar-nav theme-brand flex-row text-center">
            <li class="nav-item theme-text">
                <a href="{{ url('home') }}" class="nav-link">CAPJ</a>
            </li>
        </ul>

        <ul class="list-unstyled menu-categories" id="topAccordion">
            <li class="menu single-menu {{ $isDashboard ? 'active' : '' }}">
                <a href="#menu-dashboard" data-toggle="collapse" aria-expanded="{{ $isDashboard ? 'true' : 'false' }}" class="dropdown-toggle autodroprown">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>

                <ul class="collapse submenu list-unstyled {{ $isDashboard ? 'show' : '' }}" id="menu-dashboard" data-parent="#topAccordion">
                    <li class="{{ request()->routeIs('dashDte') ? 'active' : '' }}">
                        <a href="{{ route('dashDte') }}">Documentos</a>
                    </li>
                    <li class="{{ request()->routeIs('dashElectricidad') ? 'active' : '' }}">
                        <a href="{{ route('dashElectricidad') }}">Electricidad</a>
                    </li>
                    <li class="{{ request()->routeIs('dashAguaPotable') ? 'active' : '' }}">
                        <a href="{{ route('dashAguaPotable') }}">Agua Potable</a>
                    </li>
                </ul>
            </li>

            <li class="menu single-menu {{ $isJurisdiccion ? 'active' : '' }}">
                <a href="#menu-jurisdiccion" data-toggle="collapse" aria-expanded="{{ $isJurisdiccion ? 'true' : 'false' }}" class="dropdown-toggle autodroprown">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 21h18"></path>
                            <path d="M5 21V7l7-4 7 4v14"></path>
                            <path d="M9 21v-6h6v6"></path>
                        </svg>
                        <span>Jurisdicción</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>

                <ul class="collapse submenu list-unstyled {{ $isJurisdiccion ? 'show' : '' }}" id="menu-jurisdiccion" data-parent="#topAccordion">
                    <li class="{{ request()->routeIs('cfinancieros.*') ? 'active' : '' }}">
                        <a href="{{ route('cfinancieros.index') }}">Centros Financieros</a>
                    </li>
                    <li class="{{ request()->routeIs('ccostos.*') ? 'active' : '' }}">
                        <a href="{{ route('ccostos.index') }}">Centros Costos</a>
                    </li>
                    <li class="{{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
                        <a href="{{ route('proveedores.index') }}">Proveedores</a>
                    </li>
                    <li class="{{ request()->routeIs('clientesmedidores.*') ? 'active' : '' }}">
                        <a href="{{ route('clientesmedidores.index') }}">Cliente Medidor</a>
                    </li>
                </ul>
            </li>

            <li class="menu single-menu {{ $isPresupuesto ? 'active' : '' }}">
                <a href="#menu-presupuesto" data-toggle="collapse" aria-expanded="{{ $isPresupuesto ? 'true' : 'false' }}" class="dropdown-toggle autodroprown">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        <span>Presupuesto</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>

                <ul class="collapse submenu list-unstyled {{ $isPresupuesto ? 'show' : '' }}" id="menu-presupuesto" data-parent="#topAccordion">
                    <li class="sub-sub-submenu-list {{ request()->routeIs('items.*', 'asignaciones.*', 'catalogos.*') ? 'active' : '' }}">
                        <a href="#submenu-presupuesto-catalogo" data-toggle="collapse" aria-expanded="{{ request()->routeIs('items.*', 'asignaciones.*', 'catalogos.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                            Catálogo Presupuestario
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>

                        <ul class="collapse list-unstyled sub-submenu {{ request()->routeIs('items.*', 'asignaciones.*', 'catalogos.*') ? 'show' : '' }}" id="submenu-presupuesto-catalogo" data-parent="#menu-presupuesto">
                            <li class="{{ request()->routeIs('items.*') ? 'active' : '' }}">
                                <a href="{{ route('items.index') }}">Items</a>
                            </li>
                            <li class="{{ request()->routeIs('asignaciones.*') ? 'active' : '' }}">
                                <a href="{{ route('asignaciones.index') }}">Asignaciones</a>
                            </li>
                            <li class="{{ request()->routeIs('catalogos.*') ? 'active' : '' }}">
                                <a href="{{ route('catalogos.index') }}">Cuentas</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="menu single-menu {{ $isMercadoPublico ? 'active' : '' }}">
                <a href="#menu-mercado-publico" data-toggle="collapse" aria-expanded="{{ $isMercadoPublico ? 'true' : 'false' }}" class="dropdown-toggle autodroprown">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a1 1 0 0 0 1 .79h9.72a1 1 0 0 0 1-.76L23 6H6"></path>
                        </svg>
                        <span>Mercado Público</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>

                <ul class="collapse submenu list-unstyled {{ $isMercadoPublico ? 'show' : '' }}" id="menu-mercado-publico" data-parent="#topAccordion">
                    <li class="{{ request()->routeIs('licitaciones.*') ? 'active' : '' }}">
                        <a href="{{ route('licitaciones.index') }}">Licitaciones</a>
                    </li>
                    <li class="{{ request()->routeIs('ordenescompras.*') ? 'active' : '' }}">
                        <a href="{{ route('ordenescompras.index') }}">Órdenes de Compra</a>
                    </li>
                </ul>
            </li>

            <li class="menu single-menu {{ $isIniciativas ? 'active' : '' }}">
                <a href="#menu-iniciativas" data-toggle="collapse" aria-expanded="{{ $isIniciativas ? 'true' : 'false' }}" class="dropdown-toggle autodroprown">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        <span>Iniciativas</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>

                <ul class="collapse submenu list-unstyled {{ $isIniciativas ? 'show' : '' }}" id="menu-iniciativas" data-parent="#topAccordion">
                    <li class="{{ request()->routeIs('proyectos.*') ? 'active' : '' }}">
                        <a href="{{ route('proyectos.index') }}">Listado</a>
                    </li>
                </ul>
            </li>

            <li class="menu single-menu {{ $isDocumentos ? 'active' : '' }}">
                <a href="#menu-documentos-tributarios" data-toggle="collapse" aria-expanded="{{ $isDocumentos ? 'true' : 'false' }}" class="dropdown-toggle autodroprown">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                        <span>Documentos Tributarios</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>

                <ul class="collapse submenu list-unstyled {{ $isDocumentos ? 'show' : '' }}" id="menu-documentos-tributarios" data-parent="#topAccordion">
                    <li class="{{ request()->routeIs('dtes.*') ? 'active' : '' }}">
                        <a href="{{ route('dtes.index') }}">Índice</a>
                    </li>
                    <li class="{{ request()->routeIs('excel.import-form') ? 'active' : '' }}">
                        <a href="{{ route('excel.import-form') }}">Importar DTEs</a>
                    </li>
                </ul>
            </li>

            <li class="menu single-menu {{ $isConsumos ? 'active' : '' }}">
                <a href="#menu-consumos-basicos" data-toggle="collapse" aria-expanded="{{ $isConsumos ? 'true' : 'false' }}" class="dropdown-toggle autodroprown">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13 2L6 12h5l-1 10 7-10h-5l1-10z"></path>
                        </svg>
                        <span>Consumos Básicos</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>

                <ul class="collapse submenu list-unstyled {{ $isConsumos ? 'show' : '' }}" id="menu-consumos-basicos" data-parent="#topAccordion">
                    <li class="sub-sub-submenu-list {{ request()->routeIs('consultas.electricidad.*', 'dashboard.electricidad.filtro', 'reportes.consumo-electricidad.index') ? 'active' : '' }}">
                        <a href="#submenu-electricidad" data-toggle="collapse" aria-expanded="{{ request()->routeIs('consultas.electricidad.*', 'dashboard.electricidad.filtro', 'reportes.consumo-electricidad.index') ? 'true' : 'false' }}" class="dropdown-toggle">
                            Electricidad
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>

                        <ul class="collapse list-unstyled sub-submenu {{ request()->routeIs('consultas.electricidad.*', 'dashboard.electricidad.filtro', 'reportes.consumo-electricidad.index') ? 'show' : '' }}" id="submenu-electricidad" data-parent="#menu-consumos-basicos">
                            <li class="{{ request()->routeIs('consultas.electricidad.*') ? 'active' : '' }}">
                                <a href="{{ route('consultas.electricidad.index') }}">Consumo Electricidad</a>
                            </li>
                            <li class="{{ request()->routeIs('dashboard.electricidad.filtro') ? 'active' : '' }}">
                                <a href="{{ route('dashboard.electricidad.filtro') }}">Reportes con Filtros</a>
                            </li>
                            <li class="{{ request()->routeIs('reportes.consumo-electricidad.index') ? 'active' : '' }}">
                                <a href="{{ route('reportes.consumo-electricidad.index') }}">Consulta General</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sub-sub-submenu-list {{ request()->routeIs('consultas.agua.*') ? 'active' : '' }}">
                        <a href="#submenu-agua" data-toggle="collapse" aria-expanded="{{ request()->routeIs('consultas.agua.*') ? 'true' : 'false' }}" class="dropdown-toggle">
                            Agua
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>

                        <ul class="collapse list-unstyled sub-submenu {{ request()->routeIs('consultas.agua.*') ? 'show' : '' }}" id="submenu-agua" data-parent="#menu-consumos-basicos">
                            <li class="{{ request()->routeIs('consultas.agua.*') ? 'active' : '' }}">
                                <a href="{{ route('consultas.agua.index') }}">Consumo Agua Potable</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="menu single-menu {{ $isSistema ? 'active' : '' }}">
                <a href="#menu-sistema" data-toggle="collapse" aria-expanded="{{ $isSistema ? 'true' : 'false' }}" class="dropdown-toggle autodroprown">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h.09a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v.09a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        <span>Sistema</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>

                <ul class="collapse submenu list-unstyled {{ $isSistema ? 'show' : '' }}" id="menu-sistema" data-parent="#topAccordion">
                    <li class="{{ request()->routeIs('estados.*') ? 'active' : '' }}">
                        <a href="{{ route('estados.index') }}">Estados</a>
                    </li>
                    <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}">Usuarios</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>