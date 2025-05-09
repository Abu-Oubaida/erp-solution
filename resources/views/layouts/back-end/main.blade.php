<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("page_loader").style.display = "block";
        });

        // Hide loader when the full page (including images, CSS, etc.) is fully loaded
        window.onload = function() {
            document.getElementById("page_loader").style.display = "none";
        };
    </script>
    <x-back-end._js_source_dir/>
    <title style="">{{ucwords(str_replace('.', ' ', \Route::currentRouteName()))}} | {{str_replace('-', '-', config('app.name'))}}</title>
    <link rel="icon" href="{{url("image/logo/default/icon/360.png")}}">
    <x-back-end._header-link/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include the chartjs-plugin-datalabels -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    {{--    <script>--}}
    {{--        Chart.register(ChartDataLabels);--}}
    {{--    </script>--}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
{{--<body class="sb-nav-fixed sb-sidenav-toggled">--}}
<body class="sb-nav-fixed {{ session('sidebar_toggled') ? 'sb-sidenav-toggled' : '' }}">
<div id="page_loader" style="
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.3); color: white;
    display: flex; justify-content: center; align-items: center;
    font-size: 24px; z-index: 9999;">
    Loading, please wait...
</div>
<div id='ajax_loader' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
    <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
</div>
@include("layouts.back-end._header")
{{--<div id="layoutSidenav" class="bg-image-dashboard" style="background-image: linear-gradient(to bottom, rgba(245, 246, 252, 0.52), rgba(117, 19, 93, 0.73)), url({{url("image/bg/chl-2.jpg")}});">--}}
<div id="layoutSidenav" class="bg-image-dashboard">
    <div id="layoutSidenav_nav">
        <div id="dragholder"></div>
        @include("layouts.back-end._left-sidebar")
    </div>
    <div id="layoutSidenav_content">
        <main>
            @if ($errors->any())
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show z-index-1 w-auto error-alert position-absolute end-0 position-absolute end-0" role="alert">
                        @foreach ($errors->all() as $error)
                            <div>{{$error}}</div>
                        @endforeach
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                @php session()->forget('errors'); @endphp
            @endif
            {{--                For Insert message Showing--}}
            @if (session('success'))
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show z-index-1 position-absolute end-0 w-auto error-alert" role="alert">
                        <div>{{session('success')}}</div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                @php session()->forget('success'); @endphp
            @endif
            {{--                For Insert message Showing--}}
            @if (session('error'))
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show z-index-1 position-absolute end-0 w-auto error-alert" role="alert">
                        <div>{{session('error')}}</div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                @php session()->forget('error'); @endphp
            @endif
            @if (session('warning'))
                <div class="col-12">
                    <div class="alert alert-warning alert-dismissible fade show z-index-1 position-absolute end-0 w-auto error-alert" role="alert">
                        <div>{{session('warning')}}</div>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                @php session()->forget('warning'); @endphp
            @endif
            @yield('mainContent')
        </main>
        @include("layouts.back-end._footer")
    </div>
</div>
<x-back-end._footer-script/>
@php
    $sidebarWidth = session('sidebar_width', 250); // default: 250px
@endphp
<style>
    /* Scrollbar Track */
    ::-webkit-scrollbar {
        width: 4px;   /* Change this to resize width */
    }

    /* Scrollbar Handle */
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 6px;
    }

    /* Scrollbar Track background */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    body{
        margin:0;
        padding:0;
    }
    #layoutSidenav{
        display:flex;
    }

    #layoutSidenav_nav{
        /*background: lightgreen;*/
        height: 100vh;
        width: 20%;
        position:relative;
    }

    #dragholder{
        position:absolute;
        height: 100%;
        width: 4px;
        /*background: blue;*/
        right:0;
        top:55%;
        transform: translate(-50%, -50%);
        border-radius: 80px;
        cursor: w-resize;
    }

    #dragholder:hover{
        cursor: w-resize;
        box-shadow: 10px 0 5px -2px rgba(46, 46, 46, 0.6);
        background: rgba(46, 46, 46, 0.6);
    }
    #dragholder:hover #layoutSidenav_nav {}
    #layoutSidenav_content{
        height:100vh;
        width: 80%;
    }
    .sb-sidenav-toggled #layoutSidenav #layoutSidenav_nav {
        transform: translateX(-{!! $sidebarWidth+5 !!}px);
    }
    .sb-sidenav-toggled #layoutSidenav #layoutSidenav_content {
        margin-left: -{!! $sidebarWidth+5 !!}px;
    }
    @media (max-width: 992px) {
        .sb-nav-fixed #layoutSidenav #layoutSidenav_nav {
            width: 225px!important;
        }
        .sb-sidenav-toggled #layoutSidenav #layoutSidenav_nav {
            transform: translateX(0px)!important;
        }
        #layoutSidenav #layoutSidenav_content {
            padding-left: 220px !important;
        }.sb-sidenav-toggled #layoutSidenav #layoutSidenav_content {
             margin-left: -220px!important;
         }
    }
</style>
<script>
    let sidebar = document.getElementById("layoutSidenav_nav");
    let app_logo = document.getElementById("app_logo");
    let main_content = document.getElementById("layoutSidenav_content");
    let dragholder = document.getElementById('dragholder');
    let currentSidebarWidth = {!! $sidebarWidth !!}; // Server-defined default width

    let dynamicStyle = document.createElement('style');
    document.head.appendChild(dynamicStyle);

    let saveTimeout = null; // for debouncing

    function isDesktop() {
        return window.innerWidth > 768;
    }

    function updateSidebarWidth(width) {
        sidebar.style.width = `${width}px`;
        app_logo.style.width = `${width}px`;
        main_content.style.paddingLeft = `${width}px`;

        if (isDesktop()) {
            dynamicStyle.innerHTML = `
            .sb-sidenav-toggled #layoutSidenav #layoutSidenav_nav {
                transform: translateX(-${width + 5}px) !important;
            }
            .sb-sidenav-toggled #layoutSidenav #layoutSidenav_content {
                margin-left: -${width + 5}px !important;
            }
        `;
        }
    }

    // Debounced server save function
    function saveSidebarWidth(width) {
        if (saveTimeout) {
            clearTimeout(saveTimeout);
        }
        saveTimeout = setTimeout(() => {
            fetch('/save-sidebar-width', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ width: width })
            });
        }, 500); // send to server only after 500ms of no dragging
    }

    function onMouseMove(e) {
        if (!isDesktop()) return;
        const x = e.pageX;
        if (x > 220 && x < 400) {
            currentSidebarWidth = x;
            updateSidebarWidth(x);
            localStorage.setItem('sidebarWidth', x);
            saveSidebarWidth(x); // debounced save
        }
    }

    function onMouseDown() {
        if (!isDesktop()) return;
        document.addEventListener('mousemove', onMouseMove);
    }

    function onMouseUp() {
        if (!isDesktop()) return;
        document.removeEventListener('mousemove', onMouseMove);
    }

    document.addEventListener("DOMContentLoaded", () => {
        if (!isDesktop()) return;
        const savedWidth = parseInt(localStorage.getItem('sidebarWidth'));
        if (savedWidth && savedWidth > 220 && savedWidth < 400) {
            currentSidebarWidth = savedWidth;
        }
        updateSidebarWidth(currentSidebarWidth);
        dragholder.addEventListener('mousedown', onMouseDown);
        document.addEventListener('mouseup', onMouseUp);
    });

    window.addEventListener('resize', () => {
        if (!isDesktop()) return;
        updateSidebarWidth(currentSidebarWidth);
    });
</script>
<script>
    $(document).ready(function () {
        $("#sidebarToggle").click(function () {
            if (window.innerWidth > 992) { // Only for screens larger than 992px
                let isToggled = $('body').hasClass('sb-sidenav-toggled') ? 1 : 0;

                fetch('/save-sidebar-toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ value: isToggled })
                });
            }
        });
    });
</script>
</body>
</html>
