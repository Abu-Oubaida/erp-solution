<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>File manager</title>
{{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">--}}
    <link rel="stylesheet" href="{{url('bs5.1.3/bootstrap@5.1.3_dist_css_bootstrap.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
{{--    <link rel="stylesheet" href="{{url('bs5.1.3/bootstrap-icons@1.8.1_font_bootstrap-icons.css')}}">--}}
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <h1 class="text-center">Credence Housing Limited: File Manager</h1>
            <h5 class="text-center">Welcome Mr./Ms. {!! \Illuminate\Support\Facades\Auth::user()->name !!}</h5>
            <div>
                <a href="{{route('root')}}" class="btn btn-success">Home</a>
                <a href="{{route('dashboard')}}" class="btn btn-info">Dashboard</a>
                <a href="{{route('logout')}}" class="btn btn-danger float-end">Logout</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" id="fm-main-block">
                <div id="fm"></div>
            </div>
        </div>
    </div>
    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('fm-main-block').setAttribute('style', 'height:' + window.innerHeight + 'px');
            document.getElementsByClassName('fm-body')[0].setAttribute('style', 'height:' + (window.innerHeight - 200) + 'px');
            let hello = document.getElementsByClassName('bi-question-lg')[0]
            hello.parentElement.setAttribute('style','display: none')
            // alert()
            fm.$store.commit('fm/setFileCallBack', function(fileUrl) {
                window.opener.fmSetLink(fileUrl);
                window.close();
            });

            console.log(fm.$store)
        });
    </script>
</body>
</html>
