@if(auth()->user()->hasPermission('add_voucher_type'))
    @if(Route::currentRouteName() == 'add.voucher.type')
        <a class="nav-link" href="{{route('add.voucher.type')}}">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Voucher Type
        </a>
    @else
        <a class="nav-link text-chl" href="{{route('add.voucher.type')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Voucher Type</a>
    @endif
@endif
@if(auth()->user()->hasPermission('edit_voucher_type'))
    @if(Route::currentRouteName() == 'edit.voucher.type')
        <a class="nav-link" href="{{route("edit.voucher.type",['voucherTypeID'=>\Illuminate\Support\Facades\Request::route('voucherTypeID')])}}"><div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div> Voucher Type Edit</a>
    @endif
@endif
