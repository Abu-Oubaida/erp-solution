@if(auth()->user()->hasPermission('add_voucher_document') || auth()->user()->hasPermission('edit_voucher_document') || auth()->user()->hasPermission('add_fr_document'))

    @if(Route::currentRouteName() == 'add.voucher.info' || Route::currentRouteName() == 'add.bill.info' || Route::currentRouteName() == 'add.fr.info')
        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#pagesUploadOption" aria-expanded="true" aria-controls="pagesUploadOption">
            <div class="sb-nav-link-icon"><i class="fas fa-upload" aria-hidden="true"></i></div>
            Upload Option
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse show" id="pagesUploadOption" aria-labelledby="headingOne" data-bs-parent="#pagesUploadOption">
    @else
        <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#pagesUploadOption" aria-expanded="false" aria-controls="pagesUploadOption">
            <div class="sb-nav-link-icon"><i class="fas fa-upload" aria-hidden="true"></i></div>
            Upload Option
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse" id="pagesUploadOption" aria-labelledby="headingOne" data-bs-parent="#pagesUploadOption">
   @endif
            <nav class="sb-sidenav-menu-nested nav">
                {{--                                    Upload Voucher Permission Check Start--}}
                @if(auth()->user()->hasPermission('add_voucher_document'))
                    @if(Route::currentRouteName() == 'add.voucher.info')
                        <a class="nav-link" href="{{route('add.voucher.info')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-receipt"></i></div> Voucher</a>
                    @else
                        <a class="nav-link text-chl" href="{{route('add.voucher.info')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-receipt"></i></div> Voucher</a>
                    @endif
                @endif
                {{--                                    Upload Voucher Permission Check End--}}
                {{--                                    Upload FR Permission Check Start--}}
                @if(auth()->user()->hasPermission('add_fr_document'))
                    @if(Route::currentRouteName() == 'add.fr.info')
                        <a class="nav-link" href="{{route('add.fr.info')}}"><div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div> FR</a>
                    @else
                        <a class="nav-link text-chl" href="{{route('add.fr.info')}}"><div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div> FR</a>
                    @endif
                @endif
                {{--                                    Upload FR Permission Check End--}}
                {{--                                    Upload Bill Permission Check Start--}}
                @if(auth()->user()->hasPermission('add_bill_document'))
                    @if(Route::currentRouteName() == 'add.bill.info')
                        <a class="nav-link" href="{{route('add.bill.info')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-money-bill"></i></div> Bill</a>
                    @else
                        <a class="nav-link text-chl" href="{{route('add.bill.info')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-money-bill"></i></div> Bill</a>
                    @endif
                @endif
                {{--                                    Upload Bill Permission Check End--}}
            </nav>
        </div>
@endif {{--Upload Option End Here--}}
