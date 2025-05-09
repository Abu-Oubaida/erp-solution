<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{(\Route::currentRouteName() == 'root')?'Home Page':ucwords(str_replace('.', ' ', \Route::currentRouteName()))}} | {{str_replace('-', '-', config('app.name'))}}</title>
    <link rel="icon" href="{{url("image/logo/default/icon/360.png")}}">
    <x-back-end._js_source_dir/>
    <x-auth._header_link/>
    <style>
        button:disabled {
            cursor: not-allowed!important; /* Show a "not-allowed" cursor */
            opacity: 0.5!important; /* Optional: to make it look visually disabled */
        }
    </style>
</head>
<body class="bg-image" style="background-image: url({{url("image/bg/chl-2.jpg")}})">
<div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
        <main>
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        @yield('content')
{{--                        <div id='ajax_loader' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">--}}
{{--                            <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </main>
    </div>
    <div id="layoutAuthentication_footer">
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; CHL {{date('Y')}}</div>
                    <div>
                        <a href="#" class="text-chl">Privacy Policy</a>
                        &middot;
                        <a href="#" class="text-chl">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
<x-auth._footer_script/>
</body>
</html>
