@if(auth()->user()->hasPermission('list_voucher_document') || auth()->user()->hasPermission('view_voucher_document'))
    @if(Route::currentRouteName() == 'uploaded.voucher.list' || Route::currentRouteName() == 'view.voucher.document' || Route::currentRouteName() == 'salary.certificate.list')
    <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#pagesUploadList" aria-expanded="true" aria-controls="pagesUploadList">
        <div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div>
        View List
        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
    </a>
    <div class="collapse show" id="pagesUploadList" aria-labelledby="headingOne" data-bs-parent="#pagesUploadList">
    @else
    <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#pagesUploadList" aria-expanded="false" aria-controls="pagesUploadList">
        <div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div>
        View List
        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
    </a>
    <div class="collapse" id="pagesUploadList" aria-labelledby="headingOne" data-bs-parent="#pagesUploadList">
    @endif
        <nav class="sb-sidenav-menu-nested nav">
        @if(auth()->user()->hasPermission('list_voucher_document'))
            @if(Route::currentRouteName() == 'uploaded.voucher.list')
                <a class="nav-link" href="{{route('uploaded.voucher.list')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-receipt"></i></div> Voucher List</a>
            @else
                <a class="nav-link text-chl" href="{{route('uploaded.voucher.list')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-receipt"></i></div> Voucher List</a>
            @endif
        @endif
        @if(auth()->user()->hasPermission('view_voucher_document'))
            @if(Route::currentRouteName() == 'view.voucher.document')
                <a class="nav-link" href="{{route('view.voucher.document',['vID'=>\Illuminate\Support\Facades\Request::route('vID')])}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-receipt"></i></div> Voucher View</a>
            @endif
        @endif
        @if(auth()->user()->hasPermission('salary_certificate_list'))
            @if(Route::currentRouteName() == 'salary.certificate.list')
                <a class="nav-link" href="{{route('salary.certificate.list')}}" title="Salary Certificate List"><div class="sb-nav-link-icon"><i class="fa-solid fa-money-check-dollar"></i></div> Salary Cert. List</a>
            @else
                <a class="nav-link text-chl" href="{{route('salary.certificate.list')}}" title="Salary Certificate List"><div class="sb-nav-link-icon"><i class="fa-solid fa-money-check-dollar"></i></div> Salary Cert. List</a>
            @endif
        @endif
            {{--                                            @if(Route::currentRouteName() == 'add.fr.info')--}}
            {{--                                                <a class="nav-link" href="{{route('add.fr.info')}}"><div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div> FR</a>--}}
            {{--                                            @else--}}
            {{--                                                <a class="nav-link text-chl" href="{{route('add.fr.info')}}"><div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div> FR</a>--}}
            {{--                                            @endif--}}

            {{--                                            @if(Route::currentRouteName() == 'add.bill.info')--}}
            {{--                                                <a class="nav-link" href="{{route('add.bill.info')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-money-bill"></i></div> Bill</a>--}}
            {{--                                            @else--}}
            {{--                                                <a class="nav-link text-chl" href="{{route('add.bill.info')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-money-bill"></i></div> Bill</a>--}}
            {{--                                            @endif--}}
        </nav>
    </div>
@endif{{--List of Documet Permission Check End--}}
