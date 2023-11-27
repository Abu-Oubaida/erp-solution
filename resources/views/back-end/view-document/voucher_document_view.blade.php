<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{str_replace('-', ' | ', config('app.name'))}}</title>
    <link rel="icon" href="{{url("image/logo/chl_logo.png")}}">
    <x-back-end._header-link/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body class="sb-nav-fixed" style="margin: 0 auto;padding: 0; width: 80%;">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-chl-white">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{route('root')}}"><img src="{{url("image/logo/chl_logo.png")}}" width="70%" alt="Credence Housing Limited"></a>
    <!-- Sidebar Toggle-->
{{--    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0 text-chl-important" id="sidebarToggle"><i class="fas fa-bars"></i></button>--}}
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            {{--            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />--}}
            {{--            <button class="btn btn-primary btn-chl" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>--}}
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4 bg-chl" style="border-radius: 10px;">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{!! route("login") !!}">Login</a></li>
            </ul>
        </li>
    </ul>
</nav>
<div id="" class="">
    <div id="layoutSidenav_content">
        <main>
            <br><br><br>
            @if(isset($documentEmail))
                @php
                    // Extract the file extension
                    $fileExtension = pathinfo($documentEmail->voucherDocument->filepath.$documentEmail->voucherDocument->document,PATHINFO_EXTENSION);
                @endphp

                @if (in_array($fileExtension, ['pdf', 'doc', 'txt','jpg','jpeg','png','JPG'])) <!-- Add your allowed extensions -->
                <!-- Embed the PDF using iframe -->
                <embed id="pdfViewer" type="application/pdf" oncontextmenu="return false;" src="{{ url($documentEmail->voucherDocument->filepath.$documentEmail->voucherDocument->document) }}#toolbar=0" style="width:100%; height:100vh;" />
                @elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg']))
                    <video controls style="width: 80%">
                        <source src="{{ url($documentEmail->voucherDocument->filepath.$documentEmail->voucherDocument->document) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="text-center">Sorry! This file type is not supported for preview.</h1>
                            <a class="btn btn-success text-center" href="{{ url($documentEmail->voucherDocument->filepath.$documentEmail->voucherDocument->document) }}" download>
                                Click To Download
                            </a>
                        </div>
                    </div>
                @endif
            @elseif(isset($document))
                @php
                    // Extract the file extension
                    $fileExtension = pathinfo($document->voucherDocument->filepath.$document->voucherDocument->document,PATHINFO_EXTENSION);
                @endphp

                @if (in_array($fileExtension, ['pdf', 'doc', 'txt','jpg','jpeg','png','JPG'])) <!-- Add your allowed extensions -->
                <!-- Embed the PDF using iframe -->
            @if($document->share_type == 2)
                <embed id="pdfViewer" type="application/pdf" oncontextmenu="return false;" src="{{ url($document->voucherDocument->filepath.$document->voucherDocument->document) }}" style="width:100%; height:100vh;" />
            @else
                <embed id="pdfViewer" type="application/pdf" oncontextmenu="return false;" src="{{ url($document->voucherDocument->filepath.$document->voucherDocument->document) }}#toolbar=0" style="width:100%; height:100vh;" />
            @endif

                @elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg']))
                    <video controls style="width: 80%">
                        <source src="{{ url($document->voucherDocument->filepath.$document->voucherDocument->document) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="text-center">Sorry! This file type is not supported for preview.</h1>
                            <a class="btn btn-success text-center" href="{{ url($document->voucherDocument->filepath.$document->voucherDocument->document) }}" download>
                                Click To Download
                            </a>
                        </div>
                    </div>
                @endif
            @endif
        </main>
    </div>
</div>
<x-back-end._footer-script/>
<script>
    document.getElementById('pdfViewer').addEventListener('contextmenu', function(event) {
        event.preventDefault(); // Prevent the default right-click behavior
    });
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
    document.onkeydown = (e) => {
        if (e.key == 123) {
            e.preventDefault();
        }
        if (e.ctrlKey && e.shiftKey && e.key == 'I') {
            e.preventDefault();
        }
        if (e.ctrlKey && e.shiftKey && e.key == 'C') {
            e.preventDefault();
        }
        if (e.ctrlKey && e.shiftKey && e.key == 'J') {
            e.preventDefault();
        }
        if (e.ctrlKey && e.key == 'U') {
            e.preventDefault();
        }
    };
</script>
</body>
</html>
