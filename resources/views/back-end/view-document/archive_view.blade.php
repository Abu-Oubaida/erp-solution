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
    <a class="navbar-brand ps-3 text-black" href="http://192.168.66.6"><h5><img src="http://192.168.66.6/image/logo/default/icon/360.png" alt="ERP-360˚" class="img-fluid" width="20%"> Smart Solution</h5></a>
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
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
            <div class="container-fluid px-4 mt-5">
                <h1><i class="fas fa-eye"></i> Share Archive List</h1>
                <table id="" class="table table-hover">
                    <thead>
                    <tr class="">
                        <th>SL.</th>
                        <th>Date</th>
                        <th>Company</th>
                        <th title="Reference Number">Ref. Number</th>
                        <th>Remarks</th>
                        <th>Documents</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($archiveInfo))
                        @php($sl=1)
                        @foreach($archiveInfo->voucherDocuments as $d)
                            <tr class="">
                                <td>{!! $sl++ !!}</td>
                                <td>{!! date('d-M-y', strtotime($archiveInfo->voucher_date)) !!}</td>
                                <td>{!! $archiveInfo->company->company_name !!}</td>
                                <td>{!! $archiveInfo->voucher_number !!}</td>
                                <td>{!! $archiveInfo->remarks??"-" !!}</td>
                                <td class="text-start text-left">
                                    <div>
                                        <a href="{!! url($d->filepath.'/'.$d->document) !!}" title="View on new window" target="_blank">{!! $d->document !!}</a>
                                    </div>
                                </td>
                            </tr>

                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-danger text-center">Not Found!</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>


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
