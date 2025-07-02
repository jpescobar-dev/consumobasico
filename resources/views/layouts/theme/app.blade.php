<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{asset('img/favicon.ico')}}"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tillana:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{asset('bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/plugins.css')}}" rel="stylesheet" type="text/css" />  
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link href="{{asset('plugins/animate/animate.css')}} " rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    
    <!--  BEGIN CUSTOM STYLE FILE  -->
    {{-- <link href="{{asset('assets/css/scrollspyNav.css')}} " rel="stylesheet" type="text/css" /> --}}
    <link href="{{asset('assets/css/components/custom-modal.css')}} " rel="stylesheet" type="text/css" />


    <style>       
        .layout-px-spacing {
            min-height: calc(100vh - 184px)!important;
        }
       
        .bg-primary{
            background-color: #19133a !important;
        }

        .btn-primary{
            background-color: #19133a !important;
        }
        /*
            The below code is for DEMO purpose --- Use it if you are using this demo otherwise Remove it
        */
        .page-title {
            float: none;
            margin-top: 0;
            margin-bottom: 0;
            align-self: center;
            padding-right: 15px;
            border-right: 1px solid #bfc9d4;
            margin-right: 15px;
        }
        .page-title h3 {
            margin-bottom: 0;
            font-size: 20px;
        }
        .page-header {
            display: flex;
            padding: 0;
            margin-bottom: 16px;
            margin-top: 30px;
            justify-content: start;
        }
        .breadcrumb-one {
            display: inline-block;
            align-self: center;
        }
        .breadcrumb-one .breadcrumb {
            padding: 0;
            vertical-align: text-bottom;
            margin-bottom: 0;
            background: transparent;
        }
        .breadcrumb-one .breadcrumb-item {
            align-self: center;
        }
        .breadcrumb-one .breadcrumb-item a {
            color: #888ea8;
            vertical-align: text-bottom;
        }
        .breadcrumb-one .breadcrumb-item a svg {
            width: 20px;
            height: 20px;
            vertical-align: sub;
        }
        .breadcrumb-one .breadcrumb-item.active a {
            color: #1b55e2;
        }
        .breadcrumb-one .breadcrumb-item span {
            vertical-align: text-bottom;
        }
        .breadcrumb-one .breadcrumb-item.active {
            color: #1b55e2;
            font-weight: 600;
        }
        .breadcrumb-one .breadcrumb-item+.breadcrumb-item {
            padding: 0px;
        }
        .breadcrumb-one .breadcrumb-item+.breadcrumb-item::before {
            color: #515365;
            font-size: 0;
            content: url('data:image/svg+xml, <svg xmlns="http://www.w3.org/2000/svg" width="15" height="16" viewBox="0 0 24 24" fill="none" stroke="%23555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>');
            vertical-align: text-top;
            padding: 0 6px;
        }


        @media(max-width: 575px) {
            .page-header {
                display: block;
            }
            .page-title {
                margin-bottom: 20px;
                border: none;
                padding-right: 0;
                margin-right: 0;
            }
        }


        /*
            Just for demo purpose ---- Remove it.
        */
        /*<starter kit design>*/

        .widget-one {       
                .widget-one h6 {
                    font-size: 20px;
                    font-weight: 600;
                    letter-spacing: 0px;
                    margin-bottom: 22px;
                }
                .widget-one p {
                    font-size: 15px;
                    margin-bottom: 0;
                }
            }
        /*</starter kit design>*/

   
    </style>      
  
    @yield('styles')  
    
    @livewireStyles  
</head>

<body class="sidebar-noneoverflow">
@if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
@endif
    
    <!--  BEGIN NAVBAR  -->
    <div class="header-container">
       @include('layouts.theme.partials.header')
    </div>  

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

                    <!-- Menu superior -->
                @include('layouts.theme.partials.topnavbar')
                
                    <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="content">
            <div class="layout-px-spacing">              
                        @yield('content')                                                     
            </div>            
        </div>
    </div>
    <!-- END MAIN CONTAINER -->

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
   
     <script src="{{asset('assets/js/libs/jquery-3.1.1.min.js')}} "></script>

     <script>
        $(document).ready(function() {
            App.init();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>   
    <script src="{{asset('bootstrap/js/popper.min.js')}} "></script>
    <script src="{{asset('bootstrap/js/bootstrap.min.js')}} "></script>
    <script src="{{asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js')}} "></script>
    <script src="{{asset('assets/js/app.js')}} " defer></script>
  
    
    
    <script src="{{asset('assets/js/custom.js')}}"></script>
 
    {{-- <script src="{{asset('assets/js/scrollspyNav.js')}}"></script>    --}}

    @yield('scripts')   

    @livewireScripts
</body>
</html>