<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>@yield('title', config('app.name', 'CAPJ'))</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/animate/animate.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/components/custom-modal.css') }}" rel="stylesheet" type="text/css" />

    <style>
        .layout-px-spacing {
            min-height: calc(100vh - 184px) !important;
        }

        .bg-primary,
        .btn-primary {
            background-color: #19133a !important;
            border-color: #19133a !important;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: #140f31 !important;
            border-color: #140f31 !important;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 0;
            margin: 30px 0 16px;
        }

        .page-title {
            display: flex;
            align-items: center;
            margin: 0;
            padding: 0;
            border: 0;
            flex-shrink: 0;
        }

        .page-title a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: inherit;
            text-decoration: none;
        }

        .breadcrumb-one {
            display: flex;
            align-items: center;
            margin: 0;
            justify-content: flex-end;
        }

        .breadcrumb-one .breadcrumb {
            display: flex;
            align-items: center;
            padding: 0;
            margin: 0;
            background: transparent;
        }

        .breadcrumb-one .breadcrumb-item {
            display: flex;
            align-items: center;
        }

        .breadcrumb-one .breadcrumb-item a {
            color: #888ea8;
            text-decoration: none;
        }

        .breadcrumb-one .breadcrumb-item.active,
        .breadcrumb-one .breadcrumb-item.active span {
            color: #1b55e2;
            font-weight: 600;
        }

        .breadcrumb-one .breadcrumb-item + .breadcrumb-item {
            padding-left: 0;
        }

        .breadcrumb-one .breadcrumb-item + .breadcrumb-item::before {
            color: #515365;
            font-size: 0;
            content: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 24 24" fill="none" stroke="%23555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>');
            padding: 0 6px;
        }

        .page-heading {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            width: 100%;
            text-align: right;
        }

        .page-heading-title {
            margin: 0 0 6px;
            font-size: 1.6rem;
            font-weight: 700;
            color: #3b3f5c;
            line-height: 1.2;
        }

        .page-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: 12px;
            flex-shrink: 0;
        }

        .layout-px-spacing > .d-flex.justify-content-between.align-items-center,
        .layout-px-spacing > .d-flex.justify-content-between.align-items-center.mb-3 {
            justify-content: flex-end !important;
        }

        .layout-px-spacing > .d-flex.justify-content-between.align-items-center > div:not(:last-child),
        .layout-px-spacing > .d-flex.justify-content-between.align-items-center.mb-3 > div:not(:last-child) {
            display: none;
        }

        .alert-layout {
            margin: 0 0 1rem;
        }

        .nav-dropdowns .nav-link {
            position: relative;
        }

        .header-action-badge {
            position: absolute;
            top: 4px;
            right: -4px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            font-size: 11px;
            line-height: 1;
        }

        .header-dropdown-title {
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 700;
            color: #3b3f5c;
            border-bottom: 1px solid #ebedf2;
        }

        .header-dropdown-body {
            max-height: 320px;
            overflow-y: auto;
        }

        .header-dropdown-empty {
            padding: 14px 16px;
            color: #888ea8;
            font-size: 13px;
        }

        .header-item-title {
            font-size: 13px;
            font-weight: 600;
            color: #3b3f5c;
            margin-bottom: 2px;
        }

        .header-item-text {
            font-size: 12px;
            color: #888ea8;
            line-height: 1.4;
            margin-bottom: 2px;
        }

        .header-item-time {
            font-size: 11px;
            color: #bfc9d4;
        }

        .header-item-icon {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(27, 85, 226, 0.08);
            color: #1b55e2;
            flex-shrink: 0;
            margin-right: 12px;
        }

        @media (max-width: 575px) {
            .page-header {
                gap: 8px;
                margin-top: 20px;
            }
        }
    </style>

    @yield('styles')
    @stack('styles')
    @livewireStyles
</head>
<body class="sidebar-noneoverflow">

    <div class="header-container">
        @include('layouts.theme.partials.header')
    </div>

    <div class="main-container" id="container">
        <div class="overlay"></div>
        <div class="search-overlay"></div>

        @include('layouts.theme.partials.topnavbar')

        <div id="content" class="content">
            <div class="layout-px-spacing">
                @hasSection('title')
                    <div class="page-header">
                        <div class="page-heading">
                            <h1 class="page-heading-title">
                                @hasSection('title2')
                                    @yield('title2')
                                @else
                                    @yield('title')
                                @endif
                            </h1>

                            @include('layouts.theme.partials.breadcrumb')
                        </div>

                        @hasSection('header_actions')
                            <div class="page-header-actions">
                                @yield('header_actions')
                            </div>
                        @endif
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-layout" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>

            @include('layouts.theme.partials.footer')
        </div>
    </div>

    <script src="{{ asset('assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.App && typeof App.init === 'function') {
                App.init();
            }
        });
    </script>

    @yield('scripts')
    @stack('scripts')
    @livewireScripts
</body>
</html>
