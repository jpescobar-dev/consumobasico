@php
    $headerMessages = collect($headerMessages ?? []);
    $headerNotifications = collect($headerNotifications ?? []);
@endphp

<header class="header navbar navbar-expand-sm">
    <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom" aria-label="Alternar menú">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu">
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </a>

    <div class="nav-logo align-self-center">
        <a class="navbar-brand" href="#">
            <span class="navbar-brand-name">+ CAPJ</span>
        </a>
    </div>

    <ul class="navbar-item flex-row mr-auto"></ul>

    @auth
        <ul class="navbar-item flex-row nav-dropdowns">

            <li class="nav-item dropdown message-dropdown order-lg-0 order-1">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="messageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail">
                        <path d="M4 4h16v16H4z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>

                    @if($headerMessages->count() > 0)
                        <span class="badge badge-primary header-action-badge">{{ $headerMessages->count() }}</span>
                    @endif
                </a>

                <div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="messageDropdown">
                    <div class="header-dropdown-title">Mensajes</div>

                    <div class="header-dropdown-body">
                        @forelse($headerMessages as $message)
                            <div class="dropdown-item">
                                <div class="media">
                                    <img
                                        src="{{ $message['avatar'] ?? asset('img/90x90.jpg') }}"
                                        class="img-fluid mr-2"
                                        alt="{{ $message['title'] ?? 'Mensaje' }}"
                                        style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;"
                                    >

                                    <div class="media-body">
                                        <div class="header-item-title">{{ $message['title'] ?? 'Mensaje' }}</div>

                                        @if(!empty($message['text']))
                                            <div class="header-item-text">{{ $message['text'] }}</div>
                                        @endif

                                        @if(!empty($message['time']))
                                            <div class="header-item-time">{{ $message['time'] }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="header-dropdown-empty">
                                No hay mensajes nuevos.
                            </div>
                        @endforelse
                    </div>
                </div>
            </li>

            <li class="nav-item dropdown notification-dropdown order-lg-0 order-1">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>

                    @if($headerNotifications->count() > 0)
                        <span class="badge badge-danger header-action-badge">{{ $headerNotifications->count() }}</span>
                    @endif
                </a>

                <div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="notificationDropdown">
                    <div class="header-dropdown-title">Notificaciones</div>

                    <div class="header-dropdown-body">
                        @forelse($headerNotifications as $notification)
                            <div class="dropdown-item">
                                <div class="media align-items-start">
                                    <span class="header-item-icon">
                                        <i class="{{ $notification['icon'] ?? 'far fa-bell' }}"></i>
                                    </span>

                                    <div class="media-body">
                                        <div class="header-item-title">{{ $notification['title'] ?? 'Notificación' }}</div>

                                        @if(!empty($notification['text']))
                                            <div class="header-item-text">{{ $notification['text'] }}</div>
                                        @endif

                                        @if(!empty($notification['time']))
                                            <div class="header-item-time">{{ $notification['time'] }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="header-dropdown-empty">
                                No hay notificaciones nuevas.
                            </div>
                        @endforelse
                    </div>
                </div>
            </li>

            <li class="nav-item dropdown user-profile-dropdown order-lg-0 order-1">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="user-profile-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media">
                        <img src="{{ asset('img/90x90.jpg') }}" class="img-fluid" alt="{{ auth()->user()->name }}">
                        <div class="media-body align-self-center">
                            <h6 class="mb-0">
                                <span>Hola,</span> {{ auth()->user()->name }}
                            </h6>
                        </div>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>

                <div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="user-profile-dropdown">
                    <div class="dropdown-item">
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9 22 9 12 15 12 15 22"></polyline>
                            </svg>
                            Inicio
                        </a>
                    </div>

                    <div class="dropdown-item">
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            Cerrar sesión
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </li>
        </ul>
    @endauth
</header>